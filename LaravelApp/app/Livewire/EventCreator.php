<?php

namespace App\Livewire;

use Illuminate\Support\Facades\DB;
use App\Models\Seat;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Event;

class EventCreator extends Component
{
    use WithFileUploads;

   
    public $event_name;
    public $description;
    public $event_dateTime;
    public $time;
    public $persons_per_row;
    public $date;
    public $venue;
    public $template_path;
    public $vip_seats;
    public $regular_seats;
    public $vip_prices;
    public $regular_prices;
    public $seat_type;
    public $terms;

    public $loading = false;
    public $seatingArrangementPreview = []; //property to store seating arrangement data


    // Properties for managing stages
    public $totalSteps = 3;
    public $currentStep = 1;
    public $eventData = [];
    
    
    public function render()
    {
        return view('livewire.event-creator',[
            'allowedSeatValues' => $this->calculateAllowedSeatValues(),
        ]);
    }
    private function calculateAllowedSeatValues()
    {
        if ($this->persons_per_row) {
            $minSeats = $this->persons_per_row;
            $maxSeats = $minSeats * 10; 

            return range($minSeats, $maxSeats, $minSeats);
        }

        return [];
    }

    
    public function generateSeatingArrangement()
{
    $vipRows = $this->vip_seats ? max(1, ceil($this->vip_seats / $this->persons_per_row)) : 0;
    $regularRows = $this->regular_seats ? max(1, ceil($this->regular_seats / $this->persons_per_row)) : 0;

    $seatingArrangement = [];

    for ($i = 1; $i <= $vipRows; $i++) {
        $seatingArrangement[] = ['type' => 'VIP', 'row' => $i, 'seats' => $this->persons_per_row];
    }

    for ($i = 1; $i <= $regularRows; $i++) {
        $seatingArrangement[] = ['type' => 'Regular', 'row' => $i, 'seats' => $this->persons_per_row];
    }

    return $seatingArrangement;
}

    

public function forceUpdate()
{
    $this->reset('vip_seats', 'regular_seats', 'vip_prices', 'regular_prices');
    $this->seatingArrangementPreview = $this->generateSeatingArrangement();
}

public function updated($propertyName)
    {
        if ($propertyName == 'persons_per_row' || $propertyName == 'seat_type') {
            $this->forceUpdate();
        }
    }




    public function mount() {
      $this->currentStep = 1;
    }
    public function increaseStep() {
        $this->resetErrorBag();
        $this->validateData();
        $this->currentStep++;
        if ($this->currentStep > $this->totalSteps) {
            $this->currentStep = $this->totalSteps;
        }
    }
    public function decreaseStep() {
        $this->resetErrorBag();
        $this->currentStep--;   
        if($this->currentStep < 1) {
            $this->currentStep = 1;
        }
    }

    public function validateData() {
        if($this->currentStep == 1) {
            $this->validate([
                'event_name' => 'required',
                'venue'=> 'required',
                'date' => 'required|date|after:' . now()->addMinutes(10)->toDateTimeString(),
                'time' => 'required|date_format:H:i',
                'description'=> 'required',
                'template_path' => 'nullable|sometimes|image|mimes:jpg,png,webp,jpeg|max:2000',

            ]);
        }
    elseif ($this->currentStep == 2) {
    $this->validate([
        'persons_per_row' => 'required|integer|min:4|max:10',
        'seat_type' => 'required',
        'regular_prices' => 'nullable|integer|min:50',
        'vip_prices' => 'nullable|integer|min:100',
        'vip_seats' => 'nullable|integer',
        'regular_seats' => 'nullable|integer',
    ]);

    // Add a condition to check if the user has the host role and has chosen both VIP and regular seats
    $this->validate([
        'regular_prices' => function ($attribute, $value, $fail) {
            if ($value >= $this->vip_prices) {
                $fail('The regular price must be less than the VIP price.');
            }
        },
    ]);
}
    }

   

    
    public function register()
    {

        $this->resetErrorBag();
    
        if ($this->currentStep == 3) {
            $this->validate([
                'terms' => 'accepted',
            ]);
    
            $templatePath = null;
    
            DB::beginTransaction();
    
            try {
                if ($this->template_path) {
                    $templatePath = $this->template_path->store('event_templates', 'public');
                }
    
                $event = Event::create([
                    'user_id' => auth()->id(),
                    'event_name' => $this->event_name,
                    'description' => $this->description,
                    'event_datetime' => $this->date . ' ' . $this->time,
                    'venue' => $this->venue,
                    'template_path' => $templatePath,
                    'persons_per_row' => $this->persons_per_row,
                    'vip_seats' => $this->vip_seats ?? 0,
                    'regular_seats' => $this->regular_seats ?? 0,
                    'vip_prices' => $this->vip_prices ?? 0,
                    'regular_prices' => $this->regular_prices ?? 0,
                ]);

    
                foreach ($this->generateSeatingArrangement() as $row) {
                    for ($seat = 1; $seat <= $row['seats']; $seat++) {
                        Seat::create([
                            'event_id' => $event->id,
                            'row_number' => $row['row'],
                            'seat_number' => $seat,
                            'seat_type' => $row['type'],
                            'status' => 'available',
                        ]);
                    }
                }
    
                DB::commit();
                return redirect()->route("host.index")->with('message', 'Event created successfully!');
            } catch (\Exception $e) {
                DB::rollBack();
                $this->addError('general', 'Error creating event and seats. Please try again.');
                dd('Error: ' . $e->getMessage());
            }
        }
        
    }
    
}

    // // Method to handle advancing to the next stage
    // public function nextStage($data)
    // {
      
    //     $this->validateAndStoreData($data);

    //     $this->currentStage++;
    //     dd($this->currentStage);
    // }
    // // public function nextStage1($data)
    // // {
    // //     $this->validateAndStoreData($data);
    // //     $this->currentStage++;

    // //     return redirect()->route('host.livewire.stage2-form');
    // // }

    // // Method to validate and store data for the current stage
    // private function validateAndStoreData($data)
    // {
    //     // Add validation logic based on the current stage if needed

    //     // Store the data for the current stage
    //     $this->eventData[$this->currentStage] = $data;
    // }

    

