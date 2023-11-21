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
    public $seatingArrangementPreview = [];
    public $totalSteps = 3;
    public $currentStep = 1;
    public $eventData = [];

    public function render()
    {
        
        return view('livewire.event-creator');
    }
}

