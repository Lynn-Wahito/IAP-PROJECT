
<div class="py-12">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="w-full overflow-hidden bg-white shadow-xl sm:rounded-lg">
             

            <form wire:submit.prevent="register" enctype="multipart/form-data" class="max-w-md p-6 mx-auto mt-8 bg-white rounded-md shadow-md">
                @if ($currentStep == 1)
                    <div class="p-4 mb-6 text-white bg-blue-500">
                        <p class="text-lg font-semibold">Event Details - Stage {{ $currentStep }}/ {{ $totalSteps}}</p>
                    </div>

                    <div class="mb-6">
                        <label for="event_name" class="block text-sm font-medium text-gray-700">Event Name</label>
                        <input
                            wire:model="event_name"
                            type="text"
                            id="event_name"
                            name="event_name"
                            class="mt-1 p-2.5 block w-full rounded-md border border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            required
                        />
                        @error('event_name') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-6">
                        <label for="venue" class="block text-sm font-medium text-gray-700">Event Venue</label>
                        <input
                            wire:model="venue"
                            type="text"
                            id="venue"
                            name="venue"
                            class="mt-1 p-2.5 block w-full rounded-md border border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            required
                        />
                        @error('venue') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="date" class="block text-sm font-medium text-gray-700">Event Date</label>
                            <input
                                wire:model="date"
                                type="date"
                                id="date"
                                name="date"
                                class="mt-1 p-2.5 block w-full rounded-md border border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                required
                            />
                            @error('date') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="time" class="block text-sm font-medium text-gray-700">Event Time</label>
                            <input
                                wire:model="time"
                                type="time"
                                id="time"
                                name="time"
                                class="mt-1 p-2.5 block w-full rounded-md border border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                required
                            />
                            @error('time') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="mb-6">
                        <label for="description" class="block text-sm font-medium text-gray-700">Event Description</label>
                        <textarea
                            wire:model="description"
                            id="description"
                            name="description"
                            class="mt-1 p-2.5 block w-full rounded-md border border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            required
                        ></textarea>
                        @error('description') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-6">
                        <label for="template_path" class="block text-sm font-medium text-gray-700">Event Template (Picture)</label>
                        <input
                            wire:model="template_path"
                            type="file"
                            accept="image/png, template_path/jpeg, template_path/jpg, template_path/webp"
                            id="template_path"
                            name="template_path"
                            class="mt-1 p-2.5 block w-full rounded-md border border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        />
                        @error('template_path') <span class="text-red-500">{{ $message }}</span> @enderror

                    </div>
                
                @endif

                    <!-- Stage -2 -->
                    @if ($currentStep == 2)
                        <div class="p-4 mb-6 text-white bg-blue-500">
                            <p class="text-lg font-semibold">Seat Configuration - Stage {{ $currentStep }}/ {{ $totalSteps}}</p>
                        </div>

                        <div class="grid grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="persons_per_row" class="block text-sm font-medium text-gray-700">Persons Per Row</label>
                                <input
                                    wire:model="persons_per_row"
                                    type="number"
                                    id="persons_per_row"
                                    name="persons_per_row"
                                    min="4"
                                    max="10"
                                    class="mt-1 p-2.5 block w-full rounded-md border border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    required
                                />
                                @error('persons_per_row') <span class="text-red-500">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="seat_type" class="block text-sm font-medium text-gray-700">Seating Type</label>
                                <select
                                    wire:model="seat_type"
                                    id="seat_type"
                                    name="seat_type"
                                    class="mt-1 p-2.5 block w-full rounded-md border border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    wire:change="forceUpdate"
                                    required
                                >
                                    <option value="">--select a seat type--</option>
                                    <option value="VIP">VIP</option>
                                    <option value="Regular">Regular</option>
                                    <option value="Both">Both</option>
                                </select>
                                @error('seat_type') <span class="text-red-500">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        @if ($seat_type == 'Both' || $seat_type == 'VIP')
                            <div class="mb-6">
                                <label for="vip_seats" class="block text-sm font-medium text-gray-700">Number of VIP Tickets</label>
                                <select
                                    wire:model="vip_seats"
                                    id="vip_seats"
                                    name="vip_seats"
                                    class="mt-1 p-2.5 block w-full rounded-md border border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    required
                                >
                                @if($persons_per_row)
                                    @foreach ($allowedSeatValues as $value)
                                        <option value="{{ $value }}">{{ $value }}</option>
                                    @endforeach
                            
                                @else
                                    <option class="text-red-500" value="">Fill persons Per row field first and re-select the seat type</option>
                                @endif
                                </select>
                                @error('vip_seats') <span class="text-red-500">{{ $message }}</span> @enderror
                            </div>
                        @endif

                        @if ($seat_type == 'Both' || $seat_type == 'Regular')
                            <div class="mb-6">
                                <label for="regular_seats" class="block text-sm font-medium text-gray-700">Number of Regular Tickets</label>
                                <select
                                    wire:model="regular_seats"
                                    id="regular_seats"
                                    name="regular_seats"
                                    class="mt-1 p-2.5 block w-full rounded-md border border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    required
                                >   @if($persons_per_row)
                                        @foreach ($allowedSeatValues as $value)
                                            <option value="{{ $value }}">{{ $value }}</option>
                                        @endforeach

                                        @else
                                        <option class="text-red-500" value="">Fill persons Per row field first and re-select the seat type</option>
                                    @endif
                                </select>
                                @error('regular_seats') <span class="text-red-500">{{ $message }}</span> @enderror
                            </div>
                        @endif
                        @if($seat_type == 'Both' || $seat_type == 'VIP')
                            <div class="mb-6">
                                <label for="vip_prices" class="block text-sm font-medium text-gray-700">VIP Ticket Price</label>
                                <input
                                    wire:model="vip_prices"
                                    type="number"
                                    id="vip_prices"
                                    name="vip_prices"
                                    min="1"
                                    class="mt-1 p-2.5 block w-full rounded-md border border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    required
                                />
                                @error('vip_prices') <span class="text-red-500">{{ $message }}</span> @enderror
                            </div>
                        @endif
                        @if($seat_type == 'Both' || $seat_type == 'Regular')
                            <div class="mb-6">
                                <label for="regular_prices" class="block text-sm font-medium text-gray-700">Regular Ticket Price</label>
                                <input
                                    wire:model="regular_prices"
                                    type="number"
                                    id="regular_prices"
                                    name="regular_prices"
                                    min="1"
                                    class="mt-1 p-2.5 block w-full rounded-md border border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    required
                                />
                                @error('regular_prices') <span class="text-red-500">{{ $message }}</span> @enderror
                            </div>
                        @endif
                        <div class="mb-6" wire:loading>
                            Loading...
                        </div>
                        <div class="mb-6" wire:loading.remove wire:change="forceUpdate">
                            <label class="block text-sm font-medium text-gray-700">Seating Arrangement Preview</label>
                            <div class="grid grid-cols-{{ $persons_per_row }} gap-2">
                                @foreach ($this->generateSeatingArrangement() as $row)
                                    <div class="flex items-center justify-between p-2 bg-gray-200">
                                        <span>{{ $row['type'] }} Row {{ $row['row'] }}</span>
                                        @for ($seat = 1; $seat <= $row['seats']; $seat++)
                                            <div class="{{ $row['type'] == 'VIP' ? 'bg-yellow-300' : 'bg-green-300' }} p-1 rounded-full">{{ $seat }}</div>
                                        @endfor
                                    </div>
                                @endforeach
                            </div>
                        </div>

                
                    @endif

                @if ($currentStep == 3)
                     <div class="p-4 mb-6 text-white bg-green-500">
                        <p class="text-lg font-semibold">Completion - Stage {{ $currentStep }}/ {{ $totalSteps}}</p>
                    </div>

                    <div class="max-w-md p-6 mx-auto mt-8 bg-white rounded-md shadow-md">
                        @if ($loading)
                            <div class="flex items-center justify-center mb-6">
                                <svg class="w-5 h-5 mr-3 text-blue-500 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="#2196F3" d="M4 12a8 8 0 018-8V1.75A.75.75 0 0011.25 1h1.5a.75.75 0 00.75-.75V0a1 1 0 012 0v1.25a.75.75 0 00.75.75h1.5a.75.75 0 00.75-.75V1a1 1 0 112 0v2a8 8 0 01-8 8z"></path>
                                </svg>
                                <span class="text-blue-500">Submitting...</span>
                            </div>
                        @else
                            <p class="mb-6 text-lg text-gray-800">Thank you! Your event has been successfully created.</p>
                        @endif

                        <div class="mb-6">
                            <label for="terms" class="flex items-center">
                                <input type="checkbox" id="terms" wire:model="terms" class="mr-2 text-blue-500 focus:ring-blue-400">
                                <span class="text-gray-700">You must agree with our <a href="#" class="text-blue-500">Terms and Conditions</a></span>
                            </label>
                            <span class="text-red-500">@error('terms'){{ $message }}@enderror</span>
                        </div>
                    </div>

                    
                @endif
                
                <div class="flex justify-between mt-8">
                    @if ($currentStep == 1)
                          <div></div>
                    @endif

                    @if ($currentStep == 1)
                        
                            <button type="button" class="w-full px-4 py-2 text-white bg-blue-500 rounded-full hover:bg-blue-700" wire:click="increaseStep()">Next</button>
                        
                    @endif


                    @if ($currentStep == 2 || $currentStep == 3)
                    <button type="button" class="px-4 py-2 text-white bg-gray-500 rounded-full hover:bg-gray-700" wire:click="decreaseStep()">Back</button>
                    @endif
                    
                    @if ($currentStep == 2)
                    <button type="button" class="px-4 py-2 text-white bg-blue-500 rounded-full hover:bg-blue-700" wire:click="increaseStep()">Next</button>
                    @endif

                    @if ($currentStep == 3)
                    <button type="submit" class="px-4 py-2 text-white bg-green-500 rounded-full hover:bg-green-700">Submit</button>
                    @endif

                   
                </div>

            </form>

           
        </div>
    </div>
</div>

