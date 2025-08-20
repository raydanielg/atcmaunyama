<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * GET /api/auth/me (requires Sanctum token)
     */
    public function me(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        // Return safe profile fields
        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone ?? null,
            'region_id' => $user->region_id ?? null,
            'role' => $user->role ?? null,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
        ]);
    }
}
