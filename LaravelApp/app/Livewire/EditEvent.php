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
    

    
}
