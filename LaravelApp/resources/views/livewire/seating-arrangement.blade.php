<div>
    <div class="flex justify-center space-x-4">
        <div class="flex flex-col items-center justify-center space-y-4">
            <!-- Keys -->
            <div class="flex justify-center mb-4 space-x-4">
            <!-- VIP Seats -->
            @if ($seats->where('seat_type', 'VIP')->count() > 0)
                <div class="flex items-center space-x-2">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <rect width="20" height="20" x="2" y="2" rx="2" ry="2" stroke-width="2"
                            class="text-blue-500 stroke-current" />
                    </svg>
                    <p>VIP Seats</p>
                </div>
            @endif

            <!-- Regular Seats -->
            @if ($seats->where('seat_type', 'Regular')->count() > 0)
                <div class="flex items-center space-x-2">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <rect width="20" height="20" x="2" y="2" rx="2" ry="2" stroke-width="2"
                            class="text-green-500 stroke-current" />
                    </svg>
                    <p>Regular Seats</p>
                </div>
            @endif

            <!-- Taken Seats -->
            @if ($seats->where('status', '!=', 'available')->count() > 0)
                <div class="flex items-center space-x-2">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <rect width="20" height="20" x="2" y="2" rx="2" ry="2" stroke-width="2"
                            class="text-gray-700 stroke-current" />
                    </svg>
                    <p>Taken Seats</p>
                </div>
            @endif

            <!-- Selected Seats -->
            <div class="flex items-center space-x-2">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <rect width="20" height="20" x="2" y="2" rx="2" ry="2" stroke-width="2"
                        class="text-yellow-500 stroke-current" />
                </svg>
                <p>Selected Seats</p>
            </div>
        </div>


            <!-- Stage Representation -->
            <div class="relative w-full h-16 bg-gray-700">
                <!-- You can customize the stage representation here -->
                <div
                    class="absolute flex items-center justify-center w-2/3 font-semibold transform -translate-x-1/2 -translate-y-1/2 bg-yellow-500 rounded-lg top-1/2 left-1/2 h-1/2 text-slate-50">
                    STAGE</div>
            </div>


           <!-- VIP Seats -->
        <div class="mb-4">
            @foreach ($seats->where('seat_type', 'VIP')->groupBy('row_number') as $rowNumber => $seatsInRow)
            <div class="flex mb-2 space-x-2">
                @foreach ($seatsInRow as $seat)
                <!-- Generate seat SVG based on $seat data -->
                <svg id="vip-seat-{{ $seat->row_number }}-{{ $seat->seat_number }}"
                    x-data="{ selected: {{ in_array("vip-seat-{$seat->row_number}-{$seat->seat_number}", $selectedSeats) ? 'true' : 'false' }} }"
                    @click="selected = !selected; $wire.selectSeat({{ $seat->row_number }}, {{ $seat->seat_number }}, '{{ $seat->seat_type }}', selected)"
                    class="w-8 h-8 cursor-pointer"
                    :class="{ 'text-yellow-500': selected, 'text-blue-500': !selected }"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <rect width="20" height="20" x="2" y="2" rx="2" ry="2" stroke-width="2" />
                </svg>

                @endforeach
            </div>
            @endforeach
        </div>

        <!-- Regular Seats -->
        <div>
            @foreach ($seats->where('seat_type', 'Regular')->groupBy('row_number') as $rowNumber => $seatsInRow)
            <div class="flex mb-2 space-x-2">
                @foreach ($seatsInRow as $seat)
                <!-- Generate seat SVG based on $seat data -->
                <svg id="reg-seat-{{ $seat->row_number }}-{{ $seat->seat_number }}"
                    x-data="{ selected: {{ in_array("reg-seat-{$seat->row_number}-{$seat->seat_number}", $selectedSeats) ? 'true' : 'false' }} }"
                    @click="selected = !selected; $wire.selectSeat({{ $seat->row_number }}, {{ $seat->seat_number }}, '{{ $seat->seat_type }}', selected)"
                    class="w-8 h-8 cursor-pointer"
                    :class="{ 'text-yellow-500': selected, 'text-green-500': !selected }"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <rect width="20" height="20" x="2" y="2" rx="2" ry="2" stroke-width="2" />
                </svg>

                @endforeach
            </div>
            @endforeach
        </div>
    </div>
 
    
<!-- Cart View -->
<div class="p-4 border-2 border-gray-200 rounded-lg bg-gray-50" style="width: 30%; height: 80vh;">
    <h2 class="mb-2 text-lg font-bold">Your Cart</h2>
    <!-- Display selected seats dynamically from the cart -->
    <div class="flex flex-col space-y-2">
        @foreach ($cart as $item)
            <div class="flex items-center justify-between">
                <p class="text-sm">{{ $item['seat_label'] }}</p>
                <p class="text-sm">${{ $item['price'] }}</p>
            </div>
        @endforeach
    </div>
    <!-- Total Price -->
    <div class="flex items-center justify-between mt-4">
        <p class="text-lg font-bold">Total:</p>
        <!-- Calculate and display the total price dynamically -->
        <p class="text-lg font-bold">${{ array_sum(array_column($cart, 'price')) }}</p>
    </div>
    <!-- Confirm Booking Button -->
    @if(count($cart) > 0)
        <button
            class="mt-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded "
            wire:click="updateBookingSummary" wire:loading.attr="disabled" onclick="showBanner()"
        >
            Confirm Booking
        </button>
        <button
            wire:click="clearCart()"
            class="px-4 py-2 mt-4 font-bold text-white bg-red-500 rounded hover:bg-red-700"
        >
            Clear Cart
        </button>
    @endif
</div>

<!-- Booking Summary Banner -->
<div id="banner" class="fixed bottom-0 left-0 w-full p-4 text-white bg-blue-500 mt-auto">
    <h2 class="text-lg font-bold">Booking Summary</h2>
    <!-- Display selected seats dynamically from the bookingSummary property -->
    <div class="flex flex-col space-y-2">
        @if(isset($bookingSummary['cart']))
            @foreach ($bookingSummary['cart'] as $item)
                <div class="flex items-center justify-between">
                    <p class="text-sm">{{ $item['seat_label'] }}</p>
                    <p class="text-sm">${{ $item['price'] }}</p>
                </div>
            @endforeach
        @endif
    </div>
    <!-- Total Price -->
    <div class="flex items-center justify-between mt-4">
        <p class="text-lg font-bold">Total:</p>
        <!-- Display the total price dynamically -->
        @if(isset($bookingSummary['totalPrice']))
            <p class="text-lg font-bold">${{ $bookingSummary['totalPrice'] }}</p>
        @endif
    </div>
    <!-- Close Button -->
    <button class="px-4 py-2 mt-4 font-bold text-white bg-gray-700 rounded hover:bg-gray-900"
        onclick="hideBanner()"
    >
        Close
    </button>
    <!-- Checkout Button -->
    <button
        class="px-4 py-2 mt-4 font-bold text-white bg-green-500 rounded hover:bg-green-700 {{ $bookingConfirmed ? '' : 'opacity-50 cursor-not-allowed' }}"
        onClick="goToPaymentPage()"
        wire:loading.attr="disabled"
        :disabled="$bookingConfirmed ? '' : 'disabled'"
    >
        Checkout
    </button>
</div>



    
    @push('scripts')
    
        <script>
           
            function clearCart() {
                    // Call Livewire method to clear the cart
                    @this.call('clearCart');

                    // Reset the seat colors
                    resetSeatColor();
                }

                function resetSeatColor() {
                    // Get all seat elements
                    var seatElements = document.querySelectorAll('[id^="vip-seat-"], [id^="reg-seat-"]');

                    // Reset the color of each seat element
                    seatElements.forEach(function (seatElement) {
                if (seatElement.classList.contains('text-yellow-500')) {
                    seatElement.classList.remove('text-yellow-500');
                    if (seatElement.id.startsWith('vip-seat-')) {
                        seatElement.classList.add('text-blue-500');
                    } else {
                        seatElement.classList.add('text-green-500');
                    }
                }

                    document.addEventListener('livewire:load', function () {
                        Livewire.on('updateCart', function (data) {
                            // Handle the updated cart data
                            updateCart(data);
                        });

                          Livewire.on('updateSessionData', function () {
                            window.livewire.emit('setSessionData');
                        });

                        Livewire.hook('message.processed', (message, component) => {
                            if (message.updateQueue && message.updateQueue.some(update => update.method == 'updated')) {
                                window.livewire.emit('updateSessionData');
                            }
                        });
                    }
                }
        

                function updateCart(cart) {
                    // Clear the existing cart view
                    var cartView = document.getElementById('cart-view');
                    cartView.innerHTML = '';

                    // Add selected seats to the cart view
                    cart.forEach(function (item) {
                        var seatLabel = item['seat_label'];
                        var price = item['price'];

                        // Create a new cart item element
                        var cartItem = document.createElement('div');
                        cartItem.className = 'flex justify-between items-center';
                        cartItem.innerHTML = '<p class="text-sm">' + seatLabel + '</p>' +
                            '<p class="text-sm">$' + price + '</p>';

                        // Append the cart item to the cart view
                        cartView.appendChild(cartItem);
                    });

                    // Update the total price
                    var totalPrice = document.getElementById('total-price');
                    var totalAmount = cart.reduce(function (total, item) {
                        return total + item['price'];
                    }, 0);
                    totalPrice.innerHTML = '$' + totalAmount;

                }

                Livewire.on('updateBookingSummary', function (data) {
                    // Handle the updated booking summary data
                    updateBookingSummary(data);
                });

                function updateBookingSummary(data) {
                    @this.call('updateBookingSummary');
                    // Clear the existing booking summary view
                    var bookingSummaryView = document.getElementById('banner');
                    bookingSummaryView.innerHTML = '';

                    // Add cart data to the booking summary view
                    data['cart'].forEach(function (item) {
                    var seatLabel = item['seat_label'];
                    var price = item['price'];

                    // Create a new booking summary item element
                    var bookingSummaryItem = document.createElement('div');
                    bookingSummaryItem.className = 'flex items-center justify-between';
                    bookingSummaryItem.innerHTML = '<p class="text-sm">' + seatLabel + '</p>' +
                        '<p class="text-sm">$' + price + '</p>';

                        // Append the booking summary item to the booking summary view
                    bookingSummaryView.appendChild(bookingSummaryItem);
                    });

                    // Update the total price
                    var totalPrice = document.createElement('div');
                    totalPrice.className = 'flex items-center justify-between mt-4';
                    totalPrice.innerHTML = '<p class="text-lg font-bold">Total:</p>' +
                        '<p class="text-lg font-bold">$' +
                        data['cart'].reduce(function (total, item) {
                            return total + item['price'];
                        }, 0) +
                        '</p>';
                    bookingSummaryView.appendChild(totalPrice);

                    // Show the banner
                    showBanner();
                }



            
            });


    
        
        </script>

        
    @endpush
    
    
    

</div>



