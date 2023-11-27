<div>
    <!-- Booking Set Up: Seating Arrangement, Totals, Proceed to Checkout -->
    @livewire('seating-arrangement', ['event' => $event])


    <script>
    Livewire.on('cartUpdated', function (cart) {
    @this.updateCart(cart);
    });
    // Livewire.on('seatUpdate', function (cart) {
    // @this.updateCart(cart);
    // });


</script>

</div>


   