<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaintenanceController extends Controller
{
    public function index(Request $request)
    {
        $latest = MaintenanceEvent::query()->latest('id')->first();
        $enabled = (bool) optional($latest)->is_enabled;

        $recentMessage = MaintenanceEvent::query()
            ->whereNotNull('message')
            ->latest('id')
            ->value('message');

        $history = MaintenanceEvent::query()
            ->latest('id')
            ->take(20)
            ->get();

        return view('admin.mobile.maintenance', [
            'enabled' => $enabled,
            'message' => $recentMessage,
            'history' => $history,
            'now' => now(),
        ]);
    }

    public function toggle(Request $request)
    {
        $data = $request->validate([
            'enabled' => 'required|boolean',
        ]);

        $userId = Auth::id();
        $enabled = (bool) $data['enabled'];

        // Find active event (enabled with no ended_at)
        $active = MaintenanceEvent::query()
            ->where('is_enabled', true)
            ->whereNull('ended_at')
            ->latest('id')
            ->first();

        if ($enabled) {
            if (!$active) {
                MaintenanceEvent::create([
                    'is_enabled' => true,
                    'started_at' => now(),
                    'user_id' => $userId,
                ]);
            }
            return redirect()->route('mobile.maintenance')->with('status', 'Maintenance mode enabled.');
        } else {
            if ($active) {
                $active->update([
                    'ended_at' => now(),
                ]);
            }
            MaintenanceEvent::create([
                'is_enabled' => false,
                'user_id' => $userId,
            ]);
            return redirect()->route('mobile.maintenance')->with('status', 'Maintenance mode disabled.');
        }
    }

    public function saveMessage(Request $request)
    {
        $data = $request->validate([
            'message' => 'required|string|max:500',
        ]);

        $latest = MaintenanceEvent::query()->latest('id')->first();
        $current = (bool) optional($latest)->is_enabled;

        MaintenanceEvent::create([
            'is_enabled' => $current,
            'message' => $data['message'],
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('mobile.maintenance')->with('status', 'Maintenance message saved.');
    }
}
