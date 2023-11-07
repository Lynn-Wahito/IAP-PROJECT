<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('register') }}" x-data="{role_id: 1}">
            @csrf

            <div>
                <x-jet-label for="name" value="{{ __('Name') }}" />
                <x-jet-input id="name" class="block w-full mt-1" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            </div>


            <div class="mt-4">
                <x-jet-label for="email" value="{{ __('Email') }}" />
                <x-jet-input id="email" class="block w-full mt-1" type="email" name="email" :value="old('email')" required autocomplete="username" />
            </div>

            <div class="mt-4">
                <x-jet-label for="phone_no" value="{{ __('Phone_no') }}" />
                <x-jet-input id="phone_no" class="block w-full mt-1" type="text" name="phone_no" :value="old('phone_no')" required autocomplete="username" />
            </div>

            <div class="mt-4">
                <x-jet-label for="password" value="{{ __('Password') }}" />
                <x-jet-input id="password" class="block w-full mt-1" type="password" name="password" required autocomplete="new-password" />
            </div>

            <div class="mt-4">
                <x-jet-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                <x-jet-input id="password_confirmation" class="block w-full mt-1" type="password" name="password_confirmation" required autocomplete="new-password" />
            </div>

            <div class="mt-4">
                <x-jet-label for="role_id" value="{{ __('Register as:') }}" />
                <select name="role_id" x-model="role_id" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    @foreach ($roles as $role)
                <option value="">---select a role to register with ---</option>
                    <option value="{{ $role->id}}">{{ $role->name}}</option>
                    <!-- <option value="2">Host</option> -->
                    @endforeach
                </select>
            </div>

            <div class="mt-4" x-show="role_id == 4">
                <x-jet-label for="cutomer_address" value="{{ __('Customer_Address') }}" />
                <x-jet-input id="customer_address" class="block w-full mt-1" type="text" :value="old('customer_address')" name="customer_address" />
            </div>

            <div class="mt-4" x-show="role_id == 3">
                <x-jet-label for="host_address" value="{{ __('Host_Address') }}" />
                <x-jet-input id="host_address" class="block w-full mt-1" type="text" :value="old('host_address')" name="host_address" />
            </div>

            <div class="mt-4" x-show="role_id == 3">
                <x-jet-label for="businessName" value="{{ __('BusinessName') }}" />
                <x-jet-input id="businessName" class="block w-full mt-1" type="text" :value="old('businessName')" name="businessName" />
            </div>


            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                <div class="mt-4">
                    <x-label for="terms">
                        <div class="flex items-center">
                            <x-checkbox name="terms" id="terms" required />

                            <div class="ms-2">
                                {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                        'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="text-sm text-gray-600 underline rounded-md hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">'.__('Terms of Service').'</a>',
                                        'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="text-sm text-gray-600 underline rounded-md hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">'.__('Privacy Policy').'</a>',
                                ]) !!}
                            </div>
                        </div>
                    </x-label>
                </div>
            @endif

            <div class="flex items-center justify-end mt-4">
                <a class="text-sm text-gray-600 underline rounded-md hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>

                <x-button class="ms-4">
                    {{ __('Register') }}
                </x-button>
            </div>
            <div class="form-group">
    <label for="role">Choose your role:</label>
    <select name="role" class="form-control" id="role">
        <option value="user">User</option>
        <option value="admin">Admin</option>
    </select>
</div>

        </form>
    </x-authentication-card>
</x-guest-layout>
