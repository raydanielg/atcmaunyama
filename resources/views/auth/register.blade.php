<x-guest-layout>
    <div class="mb-4">
        <a href="{{ route('login') }}" class="inline-flex items-center text-sm text-indigo-600 hover:text-indigo-800">
            <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12l7.5-7.5M3 12h18" />
            </svg>
            {{ __('Back to login') }}
        </a>
    </div>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="relative">
            <x-input-label for="name" :value="__('Name')" />
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none mt-7">
                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6.75a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.25a8.25 8.25 0 1 1 15 0v.75h-15v-.75Z" />
                </svg>
            </span>
            <x-text-input id="name" class="block mt-1 w-full pl-10 h-12 text-base" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4 relative">
            <x-input-label for="email" :value="__('Email')" />
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none mt-7">
                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5A2.25 2.25 0 0 1 19.5 19.5h-15A2.25 2.25 0 0 1 2.25 17.25V6.75M21.75 6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0l-9.75 6.75L2.25 6.75" />
                </svg>
            </span>
            <x-text-input id="email" class="block mt-1 w-full pl-10 h-12 text-base" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Phone -->
        <div class="mt-4 relative">
            <x-input-label for="phone" :value="__('Phone Number')" />
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none mt-7">
                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75A2.25 2.25 0 0 1 4.5 4.5h1.372c.516 0 .977.332 1.131.824l1.105 3.571a1.125 1.125 0 0 1-.417 1.245l-1.293.97a.75.75 0 0 0-.26.832c.322 1.036.956 2.272 2.063 3.379 1.107 1.107 2.343 1.741 3.379 2.063a.75.75 0 0 0 .832-.26l.97-1.293a1.125 1.125 0 0 1 1.245-.417l3.571 1.105c.492.154.824.615.824 1.131V19.5a2.25 2.25 0 0 1-2.25 2.25h-1.5c-7.456 0-13.5-6.044-13.5-13.5v-1.5Z" />
                </svg>
            </span>
            <x-text-input id="phone" class="block mt-1 w-full pl-10 h-12 text-base" type="tel" name="phone" :value="old('phone')" autocomplete="tel" />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4 relative" x-data="{ show: false }">
            <x-input-label for="password" :value="__('Password')" />

            <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none mt-7">
                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0V10.5m-.75 0h10.5A2.25 2.25 0 0 1 19.5 12.75v6.75A2.25 2.25 0 0 1 17.25 21.75H6.75A2.25 2.25 0 0 1 4.5 19.5v-6.75A2.25 2.25 0 0 1 6.75 10.5z" />
                </svg>
            </span>

            <x-text-input id="password" class="block mt-1 w-full pl-10 h-12 text-base"
                            x-bind:type="show ? 'text' : 'password'"
                            name="password"
                            required autocomplete="new-password" />

            <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3 mt-7 text-gray-400 hover:text-gray-600" aria-label="Toggle password visibility">
                <svg x-show="!show" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.644C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                    <circle cx="12" cy="12" r="3" />
                </svg>
                <svg x-show="show" x-cloak class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223C5.704 6.036 8.641 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639a12.316 12.316 0 0 1-2.501 3.796M9.88 9.88A3 3 0 0 1 14.12 14.12M3 3l18 18" />
                </svg>
            </button>

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4 relative" x-data="{ show: false }">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none mt-7">
                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0V10.5m-.75 0h10.5A2.25 2.25 0 0 1 19.5 12.75v6.75A2.25 2.25 0 0 1 17.25 21.75H6.75A2.25 2.25 0 0 1 4.5 19.5v-6.75A2.25 2.25 0 0 1 6.75 10.5z" />
                </svg>
            </span>

            <x-text-input id="password_confirmation" class="block mt-1 w-full pl-10 h-12 text-base"
                            x-bind:type="show ? 'text' : 'password'"
                            name="password_confirmation" required autocomplete="new-password" />

            <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3 mt-7 text-gray-400 hover:text-gray-600" aria-label="Toggle password visibility">
                <svg x-show="!show" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.644C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                    <circle cx="12" cy="12" r="3" />
                </svg>
                <svg x-show="show" x-cloak class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223C5.704 6.036 8.641 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639a12.316 12.316 0 0 1-2.501 3.796M9.88 9.88A3 3 0 0 1 14.12 14.12M3 3l18 18" />
                </svg>
            </button>

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
