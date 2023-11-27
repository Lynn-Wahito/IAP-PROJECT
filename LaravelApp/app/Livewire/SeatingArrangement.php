<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Seat;


class SeatingArrangement extends Component
{

    public $event;
    public $seats; 
    public $selectedSeats = [];
    public $cart = [];
    public $showBanner =false;
    public $bookingSummary = [];
    public $bookingConfirmed = false;
   
    public function mount($event) {
        $this->event = $event;
        $this->seats = Seat::where('event_id', $event->id)
        ->orderBy('row_number')
        ->orderBy('seat_number')
        ->get();

        $this->setSessionData();
    }
    public function render()
    {
        return view('livewire.seating-arrangement');
    }

    public function selectSeat($row, $seat, $type, $selected)
    {
        $seatKey = "{$type}-seat-{$row}-{$seat}";

        if ($selected) {
            $this->selectedSeats[] = $seatKey;

            // Add seat to cart 
            $this->cart[] = [
                'seat_label' => $this->getSeatLabel($row, $seat, $type),
                'price' => $this->getSeatPrice($row, $seat, $type),
            ];
            $this->updateSessionData();
        } else {
            // Remove seat from selectedSeats array
            $this->selectedSeats = array_diff($this->selectedSeats, [$seatKey]);

            // Find the index of the seat in the cart
            foreach ($this->cart as $index => $item) {
                if ($item['seat_label'] == $seatKey) {
                    // Remove seat from cart
                    unset($this->cart[$index]);
                    // Reset array keys after unset
                    $this->cart = array_values($this->cart);
                    break;
                }
            }

            $this->updateSessionData();
        }
        // $this->emit('cartUpdated', $this->cart);
    }

    private function updateSessionData()
    {
        session([
            'selectedSeats' => $this->selectedSeats,
            'cart' => $this->cart,
        ]);
    }

    public function setSessionData()
    {
        $this->selectedSeats = session('selectedSeats', []);
        $this->cart = session('cart', []);
    }

    public function clearSessionData()
    {
        session()->forget(['selectedSeats', 'cart']);
    }

    public function updated($propertyName)
    {
        if ($propertyName == 'cart') {
            // Execute JavaScript after the cart property is updated
            $this->dispatchBrowserEvent('updateCart', ['cart' => $this->cart]);
        }
    }

protected function getSeatLabel($row, $seat, $type)
{
    return "{$type}-{$row}-{$seat}";
}


protected function getSeatPrice($row, $seat, $type)
{
    $event = $this->event;

    // Eager load seats to ensure they are available
    $event->load('seats');

    // Check if the event has prices columns
    if ($event && $event->vip_prices && $event->regular_prices) {
        // Retrieve the seat price based on the seat type
        $seatPrice = ($type === 'VIP') ? $event->vip_prices : $event->regular_prices;

        return $seatPrice;
    }

    return null;
}

public function clearCart()
{

   
    $this->cart = [];
    $this->selectedSeats = [];
    $this->clearSessionData();
    //$this->dispatchBrowserEvent('resetSeatColors');
}

    protected $listeners = ['updateBookingSummary' => 'setBookingSummary'];

    public function setBookingSummary()
    {
        // You can add logic here to update the booking summary
        $cart = $this->cart;

        // Calculate total price
        $totalPrice = array_sum(array_column($cart, 'price'));

        // Update Livewire properties directly
        $this->bookingSummary = [
            'cart' => $cart,
            'totalPrice' => $totalPrice,
        ];

        $this->showBanner = true;
    }


    public function updateBookingSummary()
    {
        // Include logic to update the cart or any other necessary data
        $this->bookingConfirmed = true; 
        $this->setBookingSummary(); // Call a method to update the booking summary
    }






   

}