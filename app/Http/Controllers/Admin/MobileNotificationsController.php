<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\MobileNotification;

class MobileNotificationsController extends Controller
{
    public function index(Request $request)
    {
        $notifications = MobileNotification::query()
            ->latest()
            ->take(20)
            ->get();
        return view('admin.mobile.notifications.index', [
            'notifications' => $notifications,
            'now' => now(),
        ]);
    }

    public function send(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:120',
            'message' => 'required|string|max:1000',
            'deep_link' => 'nullable|string|max:255',
            'schedule_date' => 'nullable|date',
            'schedule_time' => 'nullable',
            'repeat' => 'nullable|string|in:none,hourly,daily,weekly,monthly',
        ]);
        // Build scheduled_at from date + time if provided
        $scheduledAt = null;
        if (!empty($data['schedule_date'])) {
            $time = $data['schedule_time'] ?? '09:00';
            $scheduledAt = \Carbon\Carbon::parse($data['schedule_date'].' '.$time);
        }

        $notification = MobileNotification::create([
            'title' => $data['title'],
            'message' => $data['message'],
            'deep_link' => $data['deep_link'] ?? null,
            'scheduled_at' => $scheduledAt,
            'repeat' => $data['repeat'] ?? 'none',
            'status' => $scheduledAt ? 'scheduled' : 'queued',
            'targets' => null,
            'meta' => null,
        ]);

        Log::info('Mobile notification saved', ['id' => $notification->id]);
        return redirect()->route('mobile.notifications.index')->with('status', 'Notification saved. Dispatch integration pending.');
    }

    public function updateApp(Request $request)
    {
        // Placeholder: trigger app update flag. Implement real mechanism later.
        Log::info('Mobile app update triggered');
        return redirect()->route('mobile.notifications.index')->with('status', 'App update signal sent (placeholder).');
    }
}
