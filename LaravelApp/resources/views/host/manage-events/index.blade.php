<x-host-layout>
@livewireStyles


    <div class="w-full py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                

            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <div class="flex justify-end p-2">
                    <a href="{{ route('host.multi-step-form')}}" class="px-4 py-2 bg-green-700 rounded-md hover:bg-green-500 text-slate-100">+ Upload Event</a>

                </div>
                @if($events->isEmpty())
                    <img src="{{ asset('storage/images/no-data.webp') }}" alt="Placeholder Image" class="mx-auto mt-8">
                    <p class="text-center text-gray-500 dark:text-gray-400">You haven't created any events yet.</p>
                @else
                <table class="w-full text-sm text-left text-gray-500 rtl:text-right dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-16 py-3">
                                <span class="sr-only">Image</span>
                            </th>
                            <th scope="col" class="px-6 py-3">
                               Event Name
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Desc
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Venue
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Time Remaining
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($events as $event)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td class="p-4">
                                <img src="{{  asset('storage/' . $event->template_path) }}" class="w-16 max-w-full max-h-full md:w-32" alt="{{ $event->event_name}}">
                            </td>
                            <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                                {{ $event->event_name }}
                            </td>
                            <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                            {{ $event->truncateDescription() }}
                            </td>
                            <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                            {{ $event->venue }}
                            </td>
                            
                            <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                            {{ $event->calculateRemainingTime() }}
                            </td>
                            <td class="flex justify-between grid-cols-2 px-6 py-4">
                           
                             <a href="{{ route('host.manage-events.edit', $event->id) }}" 
                                class="px-2 font-medium text-blue-600 dark:text-blue-500 hover:underline" 
                                wire:click.prevent="$emitTo('edit-event', 'mount', {{ $event->id }}, 'event_id')">Edit</a>

                                <form class="flex justify-end font-medium text-red-600 dark:text-red-500 hover:underline" method="POST" action="{{ route('host.manage-events.destroy', $event->id)}}" onsubmit="return confirm('Are you sure you want to delete ' .{{$event->event_name}}. '?');">
                                @csrf
                                @method("DELETE")
                                <button type="submit">Delete </button>

                                </form>



                                <!-- <a href="#" class="flex justify-end font-medium text-red-600 dark:text-red-500 hover:underline ">Remove</a> -->
                            </td>
                        </tr>
                        @endforeach
                        
                    </tbody>
                </table>
                @endif

               
            </div>

        </div>
    </div>
    @livewireScripts
</x-host-layout>