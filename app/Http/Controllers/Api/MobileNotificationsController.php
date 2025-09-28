<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
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

    /**
     * GET /api/mobile/notification
     * Simple ping for mobile clients that just need a heartbeat.
     */
    public function ping(Request $request)
    {
        return response()->json([
            'ok' => true,
            'message' => 'Notification ping OK',
            'ts' => now()->toISOString(),
        ]);
    }

    /**
     * GET /api/mobile/notifications
     * Returns only currently active notifications (not expired; may be scheduled or active now).
     */
    public function listActive(Request $request)
    {
        $items = Notification::query()
            ->currentlyActive()
            ->orderByDesc('id')
            ->get(['id','title','message','action_label','action_url','starts_at','ends_at']);
        return response()->json(['data' => $items]);
    }

    /**
     * GET /api/mobile/notifications/{id}
     * Returns a single notification only if currently active.
     */
    public function showActive(Request $request, int $id)
    {
        $n = Notification::query()->currentlyActive()->findOrFail($id);
        return response()->json([
            'id' => $n->id,
            'title' => $n->title,
            'message' => $n->message,
            'action_label' => $n->action_label,
            'action_url' => $n->action_url,
            'starts_at' => optional($n->starts_at)->toIso8601String(),
            'ends_at' => optional($n->ends_at)->toIso8601String(),
        ]);
    }

    /**
     * POST /api/mobile/notifications/{id}/view
     * Increments views count if notification exists (active or historical). Returns 404 if not found.
     */
    public function trackView(Request $request, int $id)
    {
        $n = Notification::query()->findOrFail($id);
        $n->increment('views');
        return response()->json(['ok' => true]);
    }

    /**
     * POST /api/mobile/notifications/{id}/click
     * Increments clicks count if notification exists (active or historical). Returns 404 if not found.
     */
    public function trackClick(Request $request, int $id)
    {
        $n = Notification::query()->findOrFail($id);
        $n->increment('clicks');
        return response()->json(['ok' => true]);
    }
}
