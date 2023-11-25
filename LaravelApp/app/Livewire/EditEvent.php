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

    public function mount($event_id) {
        // dd($event_id);
        $this->currentStep = 1;
        $this->event_id = $event_id;
    }

    public function increaseStep() {
        $this->resetErrorBag();
        // $this->validateData();
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

    public function loadEventData() {
        $this->event = Event::find($this->event_id);
    
        if ($this->event) {
            $this->event_name = $this->event->event_name;
            $this->description = $this->event->description;
            $this->venue = $this->event->venue;

            if ($this->event->event_datetime) {
                

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
           

            $this->vip_seats = $this->event->vip_seats;
            $this->regular_seats = $this->event->regular_seats;
            $this->vip_prices = $this->event->vip_prices;
            $this->regular_prices = $this->event->regular_prices;
          
            $this->seatingArrangementPreview = $this->generateSeatingArrangement();
            
            $this->forceUpdate(); // Refresh the seating arrangement preview
        }
    }
    

    
}
