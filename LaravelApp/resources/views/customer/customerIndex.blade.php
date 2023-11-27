<x-customer-layout>
    <div class="w-full py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="w-full overflow-hidden bg-white shadow-xl sm:rounded-lg">
                Customer Content
            </div>
         
            <div class="container mx-auto px-4 py-16">
        <h2 class="text-4xl font-bold mb-10 text-center">Event Posters</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="bg-white rounded-lg overflow-hidden shadow-md transform transition-transform duration-500 hover:scale-105">
                <img src="https://www.ticketsasa.com/components/com_enmasse/upload/Screenshot_2023-11-14_at_10.35.29.png1699947406.jpg" alt="Event Poster" class="w-full h-64 object-cover">
                <div class="p-6">
                    <h3 class="text-xl font-semibold mb-2">Tis The Season 2023 Show: A Very Kenyan Christmas Show!</h3>
                    <p class="text-gray-500 mb-4">t's beginning to look a lot like Christmas...yup, it's just about time for 'Tis The Season!   This Year 'Tis The Season is a Festival with a Christmas pop up market, food, fun for the kids and individual performances by your TTS faves..the main show will start at 6:00pm!   Looking forward to celebrating A Very Kenyan Christmas with you.</p>
                    <a href="#" class="bg-blue-500 text-white px-6 py-2 rounded-md hover:bg-blue-600 transition-colors duration-200">More Info</a>
                </div>
            </div>
            <div class="w-full py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

            <div class="w-full overflow-hidden text-4xl font-sans mb-4 capitalize">
                <span style="color: #3490dc; font-weight: bold;">Just for you:</span>
            </div>

            <div class="flex flex-wrap justify-between">

                <!-- Iterate over upcoming events -->
                @foreach($upcomingEvents as $event)
                    <div class="max-w-sm flex-grow mb-4">
                        <div class="bg-white shadow-md border border-gray-200 rounded-lg max-w-sm dark:bg-gray-800 dark:border-gray-700">
                            <a href="{{ route('users.bookingPage', ['event' => $event->id]) }}"
                                wire:click.prevent="$emitTo('booking-page', 'mount', {{ $event->id }}, 'event_id')">
                                <img class="rounded-t-lg" src="{{  asset('storage/' . $event->template_path) }}" alt="Event Image">
                            </a>
                            <div class="p-5">
                                <a href="{{ route('users.bookingPage', ['event' => $event->id]) }}"
                                wire:click.prevent="$emitTo('booking-page', 'mount', {{ $event->id }}, 'event_id')">
                                    <h5 class="text-gray-900 font-bold text-2xl tracking-tight mb-2 dark:text-white">{{ $event->event_name }}</h5>
                                </a>
                                <h5 class="text-gray-900 font-bold text-2xl tracking-tight mb-2 dark:text-white">Venue: {{ $event->venue }}</h5>
                                <p class="font-normal text-gray-700 mb-3 dark:text-gray-400">{{ $event->truncateDescription() }}</p>
                                <a href="#" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-3 py-2 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                    Event Details
                                    <svg class="-mr-1 ml-2 h-4 w-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>

        </div>
</div>

</x-customer-layout>

