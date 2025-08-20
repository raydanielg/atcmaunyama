<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    /**
     * Exchange Google ID token for Sanctum token.
     * Request: { id_token: string, name?: string, avatar?: string }
     * Response: { token, user }
     */
    public function exchange(Request $request)
    {
        $data = $request->validate([
            'id_token' => 'required|string',
            'name' => 'nullable|string|max:255',
            'avatar' => 'nullable|url|max:1024',
        ]);

        $idToken = $data['id_token'];
        $clientId = config('services.google.client_id');
        if (!$clientId) {
            return response()->json(['message' => 'Server not configured for Google login'], 500);
        }

        // Verify using Google tokeninfo endpoint (simple, server-side)
        $resp = Http::asForm()->get('https://oauth2.googleapis.com/tokeninfo', [
            'id_token' => $idToken,
        ]);
        if (!$resp->ok()) {
            return response()->json(['message' => 'Invalid Google token'], 401);
        }
        $payload = $resp->json();

        // Basic checks
        $aud = $payload['aud'] ?? $payload['azp'] ?? null;
        $email = $payload['email'] ?? null;
        $emailVerified = filter_var($payload['email_verified'] ?? false, FILTER_VALIDATE_BOOLEAN);
        $sub = $payload['sub'] ?? null; // Google user id
        $name = $data['name'] ?? ($payload['name'] ?? null);
        $picture = $data['avatar'] ?? ($payload['picture'] ?? null);

        if (!$aud || $aud !== $clientId) {
            return response()->json(['message' => 'Google client mismatch'], 401);
        }
        if (!$email) {
            return response()->json(['message' => 'Google account missing email'], 422);
        }
        if (!$emailVerified) {
            return response()->json(['message' => 'Google email not verified'], 422);
        }

        // Find or create user by email
        $user = User::query()->where('email', $email)->first();
        if (!$user) {
            $user = new User();
            $user->name = $name ?: Str::before($email, '@');
            $user->email = $email;
            // Generate a random strong password so the account exists; not used for Google sign-in
            $user->password = bcrypt(Str::random(40));
            // Optional fields if exist
            if (schema_has_column('users', 'role')) { $user->role = $user->role ?: 'user'; }
            if (schema_has_column('users', 'email_verified_at')) { $user->email_verified_at = now(); }
            try { $user->save(); } catch (\Throwable $e) {
                Log::error('User create failed (google)', ['err' => $e->getMessage()]);
                return response()->json(['message' => 'Could not create user'], 500);
            }
        } else {
            // Mark verified if model supports it
            try {
                if (schema_has_column('users', 'email_verified_at') && !$user->email_verified_at) {
                    $user->email_verified_at = now();
                }
                if ($name && !$user->name) { $user->name = $name; }
                $user->save();
            } catch (\Throwable $e) {
                // Non-fatal
            }
        }

        // Issue Sanctum token
        $token = $user->createToken('mobile')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone ?? null,
                'region_id' => $user->region_id ?? null,
                'role' => $user->role ?? null,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ],
        ], 200);
    }
}

if (!function_exists('schema_has_column')) {
    function schema_has_column(string $table, string $column): bool {
        try {
            return \Illuminate\Support\Facades\Schema::hasColumn($table, $column);
        } catch (\Throwable $e) { return false; }
    }
}
