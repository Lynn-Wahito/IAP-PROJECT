<?php

namespace App\Livewire;

use Illuminate\Support\Facades\DB;
use App\Models\Seat;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Event;
use Illuminate\Support\Facades\Storage;


class EditEvent extends Component
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
    public $event_id;
    public $event;


    public $seatingArrangementPreview = [];

    public $totalSteps = 3;
    public $currentStep = 1;
    public $eventData = [];

    public function render()
    {
        return view('livewire.edit-event',[
            'allowedSeatValues' => $this->calculateAllowedSeatValues(),
        ]);
    }
    

    private function calculateAllowedSeatValues()
    {
        if (!is_null($this->vip_seats) || !is_null($this->regular_seats)) {
            // Use vip_seats or regular_seats if either one is set
            $minSeats = max($this->persons_per_row, $this->vip_seats ?? 0, $this->regular_seats ?? 0);
            $maxSeats = $minSeats * 10; // Default max value
            // dd('Using vip_seats or regular_seats:', compact('minSeats', 'maxSeats'));
        } else {
            // Perform normal operations if both vip_seats and regular_seats are null
            $minSeats = $this->persons_per_row;
            $maxSeats = $minSeats * 10; // Default max value
            // dd('Normal operations:', compact('minSeats', 'maxSeats'));
        }
    
        return range($minSeats, $maxSeats, $minSeats);
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

    public function mount($event_id) {
        // dd($event_id);
        $this->currentStep = 1;
        $this->event_id = $event_id;
        $this->loadEventData();
    }
    

    public function loadEventData() {
        $this->event = Event::find($this->event_id);
    
        if ($this->event) {
            $this->event_name = $this->event->event_name;
            $this->description = $this->event->description;
            $this->venue = $this->event->venue;

            if ($this->event->event_datetime) {
                // dd($this->event->event_datetime);

                $this->date = $this->event->event_datetime->format('Y-m-d');
                $this->time = $this->event->event_datetime->format('H:i');
            }
            
            $this->template_path = $this->event->template_path;
            $this->persons_per_row = $this->event->persons_per_row;

            //Associating existing seats with the event
            $existingSeats = Seat::where('event_id', $this->event_id)->pluck('seat_type')->toArray();

            //Populating the seat_type field based on the seat types of the seats associated to the event
            if(in_array('VIP', $existingSeats) && in_array('Regular', $existingSeats)){
                $this->seat_type = "Both";
            }elseif(in_array('VIP', $existingSeats)){
                $this->seat_type = "VIP";
            }elseif(in_array('Regular', $existingSeats)){
                $this->seat_type = "Regular";
            }else {
                // Default to an empty value if no seats are found
                $this->seat_type = '';
            }
            // dd($this->seat_type);
            // dd($this->event->vip_seats);

            $this->vip_seats = $this->event->vip_seats;
            $this->regular_seats = $this->event->regular_seats;
            $this->vip_prices = $this->event->vip_prices;
            $this->regular_prices = $this->event->regular_prices;
            // dd([$this->event->vip_seats,
            // $this->vip_prices]);
            $this->seatingArrangementPreview = $this->generateSeatingArrangement();
            
            $this->forceUpdate(); // Refresh the seating arrangement preview
        }
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
                'date' => 'required|date|after:',
                'time' => 'required|date_format:H:i',
                'description'=> 'required',
                'template_path' => 'nullable|sometimes|image|mimes:jpg,png,webp,jpeg|max:2000',

            ]);
        }
        elseif($this->currentStep == 2) {
            $this->validate([
                'persons_per_row'=> 'required|integer|min:4|max:10',
                'seat_type'=> 'required',
                'regular_prices' => 'nullable|integer|min:50',
                'vip_prices'=> 'nullable|integer|min:100',
                'vip_seats' => 'nullable|integer',
                'regular_seats' => 'nullable|integer',
                
            ]);

            if($this->vip_prices > 0 ){
                $this->validate([
                    
                    'regular_prices' => function ($attribute, $value, $fail) {
                        if ($value >= $this->vip_prices) {
                            $fail('The regular price must be less than the VIP price.');
                        }
                    },
                
                ]);
            }
            
        }
        
    }

    public function updateEvent()
    {
        $this->resetErrorBag();
    
        if ($this->currentStep == 3) {
            $this->validate([
                'terms' => 'accepted',
            ]);
    
            $templatePath = null;
    
            DB::beginTransaction();
    
            try {
                $event = Event::find($this->event_id);
    
                if ($this->template_path) {
                    if ($event->template_path) {
                        Storage::disk('public')->delete($event->template_path);
                    }
                    $templatePath = $this->template_path->store('event_templates', 'public');
                } else {
                    // If no new template is provided, retain the existing template path
                    $templatePath = $event->template_path;
                }
    
                // Check and update null fields
                $this->vip_seats = is_null($this->vip_seats) ? $event->vip_seats : $this->vip_seats;
                $this->regular_seats = is_null($this->regular_seats) ? $event->regular_seats : $this->regular_seats;
                $this->vip_prices = is_null($this->vip_prices) ? $event->vip_prices : $this->vip_prices;
                $this->regular_prices = is_null($this->regular_prices) ? $event->regular_prices : $this->regular_prices;
    
                $event->update([
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

                //Update or create seats
                $seats = Seat::where('event_id', $event->id)->get();

    
                foreach ($this->generateSeatingArrangement() as $row) {
                    for ($seat = 1; $seat <= $row['seats']; $seat++) {
                        $seatData = [
                            'event_id' => $event->id,
                            'row_number' => $row['row'],
                            'seat_number' => $seat,
                            'seat_type' => $row['type'],
                            'status' => 'available', //default status
                        ];
                
                        // Find the existing seat or create a new one
                        $existingSeat = $seats
                            ->where('row_number', $row['row'])
                            ->where('seat_number', $seat)
                            ->where('seat_type', $row['type'])
                            ->first();
                
                        if ($existingSeat) {
                            // Update seatData['status'] with the status of the existing seat
                            $seatData['status'] = $existingSeat->status;
                            $existingSeat->update($seatData); // Update the existing seat
                        } else {
                            // Create a new seat
                            Seat::create($seatData);
                        }
                    }
                }
                
    
                DB::commit();
                return redirect()->route("host.index")->with('message', 'Event updated successfully!');
            } catch (\Exception $e) {
                DB::rollBack();
                $this->addError('general', 'Error creating event and seats. Please try again.');
                dd('Error: ' . $e->getMessage());
            }
        }
        
    }


    
}
