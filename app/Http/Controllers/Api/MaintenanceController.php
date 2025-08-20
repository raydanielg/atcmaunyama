<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceEvent;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    /**
     * GET /api/mobile/maintenance
     * Public endpoint for the mobile app to check current maintenance status and message.
     */
    public function show(Request $request)
    {
        $latest = MaintenanceEvent::query()->latest('id')->first();
        $enabled = (bool) optional($latest)->is_enabled;

        $recentMessage = MaintenanceEvent::query()
            ->whereNotNull('message')
            ->latest('id')
            ->value('message');

        // Find the currently active window timestamps, if any
        $active = MaintenanceEvent::query()
            ->where('is_enabled', true)
            ->whereNull('ended_at')
            ->latest('id')
            ->first();

        return response()->json([
            'enabled' => $enabled,
            'message' => $recentMessage,
            'started_at' => optional($active)->started_at,
            'ended_at' => optional($latest)->ended_at,
            'updated_at' => optional($latest)->updated_at,
        ]);
    }
}
