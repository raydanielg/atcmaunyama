<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MobileNotificationsController extends Controller
{
    /**
     * GET /api/mobile/notifications/update-app
     * Lightweight public endpoint the mobile app can call to check for update signal.
     * Returns a JSON payload. Does not trigger any admin action.
     */
    public function updateApp(Request $request)
    {
        // In future, read version/update info from settings or config.
        Log::info('API update-app ping', [
            'ip' => $request->ip(),
            'ua' => $request->userAgent(),
        ]);

        return response()->json([
            'ok' => true,
            'message' => 'Update check OK',
            'ts' => now()->toISOString(),
        ]);
    }
}
