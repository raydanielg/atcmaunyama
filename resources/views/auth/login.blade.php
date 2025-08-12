<x-guest-layout>
    @php
        $settings = \App\Models\AdminSetting::query()->first();
        $meta = (array) ($settings->meta ?? []);
        $social = (array) ($meta['social'] ?? []);
        $googleEnabled = ($social['google']['enabled'] ?? null);
        $githubEnabled = ($social['github']['enabled'] ?? null);
        $facebookEnabled = ($social['facebook']['enabled'] ?? null);

        // If admin did not toggle explicitly, enable when client_id exists
        $googleEnabled = $googleEnabled === null ? !empty(config('services.google.client_id')) : (bool)$googleEnabled;
        $githubEnabled = $githubEnabled === null ? !empty(config('services.github.client_id')) : (bool)$githubEnabled;
        $facebookEnabled = $facebookEnabled === null ? !empty(config('services.facebook.client_id')) : (bool)$facebookEnabled;
        $anySocial = $googleEnabled || $githubEnabled || $facebookEnabled;
    @endphp
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <h2 class="login-title">Sign in to your account</h2>
    <p class="login-help" style="margin-top:.5rem">Use your email and password to continue.</p>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="relative">
            <x-input-label for="email" :value="__('Email')" />
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none mt-7">
                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5A2.25 2.25 0 0 1 19.5 19.5h-15A2.25 2.25 0 0 1 2.25 17.25V6.75M21.75 6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0l-9.75 6.75L2.25 6.75" />
                </svg>
            </span>
            <x-text-input id="email" class="block mt-1 w-full pl-10 h-12 text-base" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4 relative">
            <x-input-label for="password" :value="__('Password')" />

            <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none mt-7">
                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0V10.5m-.75 0h10.5A2.25 2.25 0 0 1 19.5 12.75v6.75A2.25 2.25 0 0 1 17.25 21.75H6.75A2.25 2.25 0 0 1 4.5 19.5v-6.75A2.25 2.25 0 0 1 6.75 10.5z" />
                </svg>
            </span>

            <x-text-input id="password" class="block mt-1 w-full pl-10 h-12 text-base"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        @if ($anySocial)
            <div class="mt-6">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center" aria-hidden="true">
                        <div class="w-full border-t border-gray-200"></div>
                    </div>
                    <div class="relative flex justify-center">
                        <span class="bg-white px-3 text-sm text-gray-500">or continue with</span>
                    </div>
                </div>
                <div class="mt-4 grid grid-cols-1 sm:grid-cols-3 gap-3">
                    @if ($googleEnabled)
                        <a href="{{ route('oauth.redirect', ['provider' => 'google']) }}" class="inline-flex w-full justify-center items-center gap-2 rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" class="h-5 w-5"><path fill="#FFC107" d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12 s5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C33.036,6.053,28.727,4,24,4C12.955,4,4,12.955,4,24 s8.955,20,20,20s20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z"/><path fill="#FF3D00" d="M6.306,14.691l6.571,4.819C14.655,16.15,18.961,14,24,14c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657 C33.036,6.053,28.727,4,24,4C16.318,4,9.656,8.337,6.306,14.691z"/><path fill="#4CAF50" d="M24,44c4.646,0,8.868-1.783,12.071-4.693l-5.571-4.727C28.426,36.091,26.309,37,24,37 c-5.202,0-9.619-3.315-11.283-7.946l-6.49,5.004C9.545,39.556,16.227,44,24,44z"/><path fill="#1976D2" d="M43.611,20.083H42V20H24v8h11.303c-0.793,2.238-2.225,4.2-4.116,5.627 c0.002-0.001,0.003-0.002,0.005-0.003l6.492,5.004C36.289,39.205,44,34,44,24C44,22.659,43.862,21.35,43.611,20.083z"/></svg>
                            Google
                        </a>
                    @endif
                    @if ($githubEnabled)
                        <a href="{{ route('oauth.redirect', ['provider' => 'github']) }}" class="inline-flex w-full justify-center items-center gap-2 rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50">
                            <svg viewBox="0 0 16 16" version="1.1" aria-hidden="true" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.013 8.013 0 0016 8c0-4.42-3.58-8-8-8z"></path></svg>
                            GitHub
                        </a>
                    @endif
                    @if ($facebookEnabled)
                        <a href="{{ route('oauth.redirect', ['provider' => 'facebook']) }}" class="inline-flex w-full justify-center items-center gap-2 rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5" fill="#1877F2"><path d="M24 12.073C24 5.405 18.627 0 12 0S0 5.405 0 12.073C0 18.1 4.388 23.094 10.125 24v-8.437H7.078V12.07h3.047V9.412c0-3.007 1.791-4.668 4.533-4.668 1.312 0 2.686.235 2.686.235v2.953h-1.513c-1.49 0-1.953.925-1.953 1.874v2.266h3.328l-.532 3.493h-2.796V24C19.612 23.094 24 18.1 24 12.073z"/></svg>
                            Facebook
                        </a>
                    @endif
                </div>
            </div>
        @endif

        <div class="flex items-center justify-end mt-6">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            @if (Route::has('register'))
                <a href="{{ route('register') }}" class="ms-3 inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    {{ __('Register') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
