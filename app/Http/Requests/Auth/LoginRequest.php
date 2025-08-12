<?php

namespace App\Http\Requests\Auth;

use App\Models\LoginAttempt;
use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            // After a failed attempt, increase the attempt count and set a 15-minute decay for lockout
            RateLimiter::hit($this->throttleKey(), 15 * 60);

            // Log failed attempt
            LoginAttempt::create([
                'user_id' => optional(User::where('email', $this->string('email'))->first())->id,
                'email' => (string) $this->string('email'),
                'ip' => $this->ip(),
                'user_agent' => (string) $this->header('User-Agent'),
                'success' => false,
                'created_at' => now(),
            ]);

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        // Enforce admin-only access
        if (Auth::user()?->role !== 'admin') {
            // Log attempt as failed due to role restriction and logout
            LoginAttempt::create([
                'user_id' => optional(Auth::user())->id,
                'email' => (string) $this->string('email'),
                'ip' => $this->ip(),
                'user_agent' => (string) $this->header('User-Agent'),
                'success' => false,
                'created_at' => now(),
            ]);

            Auth::logout();

            throw ValidationException::withMessages([
                'email' => __('Only admin accounts are allowed to sign in.'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());

        // Log successful attempt
        LoginAttempt::create([
            'user_id' => optional(Auth::user())->id,
            'email' => (string) $this->string('email'),
            'ip' => $this->ip(),
            'user_agent' => (string) $this->header('User-Agent'),
            'success' => true,
            'created_at' => now(),
        ]);
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}
