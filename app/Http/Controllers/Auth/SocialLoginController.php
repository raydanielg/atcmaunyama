<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AdminSetting;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SocialLoginController extends Controller
{
    /**
     * Redirect to the provider auth page.
     */
    public function redirect(Request $request, string $provider): RedirectResponse
    {
        $provider = Str::lower($provider);
        abort_unless(in_array($provider, ['google', 'github', 'facebook'], true), 404);

        // Check admin settings toggle
        $enabled = $this->providerEnabled($provider);
        if (!$enabled) {
            return redirect()->route('login')->with('status', ucfirst($provider).' login is disabled.');
        }

        // Ensure Socialite is installed
        if (!class_exists(\Laravel\Socialite\Facades\Socialite::class)) {
            return redirect()->route('login')->withErrors(['email' => 'Social login not available. Please install laravel/socialite.']);
        }

        $scopes = [];
        if ($provider === 'google') {
            $scopes = ['openid','profile','email'];
        }

        return \Laravel\Socialite\Facades\Socialite::driver($provider)
            ->scopes($scopes)
            ->redirect();
    }

    /**
     * Handle the provider callback.
     */
    public function callback(Request $request, string $provider): RedirectResponse
    {
        $provider = Str::lower($provider);
        abort_unless(in_array($provider, ['google', 'github', 'facebook'], true), 404);

        if (!class_exists(\Laravel\Socialite\Facades\Socialite::class)) {
            return redirect()->route('login')->withErrors(['email' => 'Social login not available. Please install laravel/socialite.']);
        }

        try {
            $socialUser = \Laravel\Socialite\Facades\Socialite::driver($provider)->stateless()->user();
        } catch (\Throwable $e) {
            Log::warning('Social login error', ['provider' => $provider, 'error' => $e->getMessage()]);
            return redirect()->route('login')->withErrors(['email' => 'Could not authenticate with '.ucfirst($provider).'.']);
        }

        // Try to find local user by email
        $email = $socialUser->getEmail();
        if (!$email) {
            return redirect()->route('login')->withErrors(['email' => 'Your '.$provider.' account has no verified email.']);
        }

        $user = User::where('email', $email)->first();
        if (!$user) {
            // Optionally auto-register
            $user = User::create([
                'name' => $socialUser->getName() ?: ($socialUser->getNickname() ?: 'User'),
                'email' => $email,
                // Random password; user can reset later
                'password' => bcrypt(Str::random(40)),
            ]);
        }

        Auth::login($user, true);
        return redirect()->intended(route('dashboard'));
    }

    private function providerEnabled(string $provider): bool
    {
        $settings = AdminSetting::query()->first();
        $meta = (array) ($settings->meta ?? []);
        $social = (array) ($meta['social'] ?? []);
        $enabled = $social[$provider]['enabled'] ?? null;

        // If not set, enable only when client_id exists in config/services.php
        if ($enabled === null) {
            $cfg = config('services.' . $provider);
            return is_array($cfg) && !empty($cfg['client_id']);
        }
        return (bool) $enabled;
    }
}
