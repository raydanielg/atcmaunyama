<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    public function index(Request $request)
    {
        $q = Notification::query();
        if ($s = trim((string)$request->get('q', ''))) {
            $q->where(function($w) use ($s){
                $w->where('title','like',"%{$s}%")->orWhere('message','like',"%{$s}%");
            });
        }
        if ($status = $request->get('status')) {
            $now = now();
            if ($status === 'active') {
                $q->where('is_active', true)
                  ->where(function($w) use ($now){
                      $w->whereNull('starts_at')->orWhere('starts_at','<=',$now);
                  })
                  ->where(function($w) use ($now){
                      $w->whereNull('ends_at')->orWhere('ends_at','>=',$now);
                  });
            } elseif ($status === 'scheduled') {
                $q->where('is_active', true)
                  ->whereNotNull('starts_at')
                  ->where('starts_at','>', now());
            } elseif ($status === 'expired') {
                $q->where('is_active', true)
                  ->whereNotNull('ends_at')
                  ->where('ends_at','<', now());
            } elseif ($status === 'inactive') {
                $q->where('is_active', false);
            }
        }
        $items = $q->orderByDesc('id')->paginate(15)->withQueryString();
        return view('admin.notifications.index', compact('items','s','status'));
    }

    public function create()
    {
        $notification = new Notification();
        return view('admin.notifications.create', compact('notification'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required','string','max:255'],
            'message' => ['nullable','string'],
            'action_label' => ['nullable','string','max:100'],
            'action_url' => ['nullable','url','max:500'],
            'starts_at' => ['nullable','date'],
            'ends_at' => ['nullable','date','after_or_equal:starts_at'],
            'is_active' => ['nullable','boolean'],
        ]);
        $data['is_active'] = (bool)($data['is_active'] ?? false);
        $n = Notification::create($data);
        return redirect()->route('admin.notifications.show', $n)->with('status', 'Notification created.');
    }

    public function show(Notification $notification)
    {
        return view('admin.notifications.show', [ 'n' => $notification ]);
    }

    public function edit(Notification $notification)
    {
        return view('admin.notifications.edit', compact('notification'));
    }

    public function update(Request $request, Notification $notification)
    {
        $data = $request->validate([
            'title' => ['required','string','max:255'],
            'message' => ['nullable','string'],
            'action_label' => ['nullable','string','max:100'],
            'action_url' => ['nullable','url','max:500'],
            'starts_at' => ['nullable','date'],
            'ends_at' => ['nullable','date','after_or_equal:starts_at'],
            'is_active' => ['nullable','boolean'],
        ]);
        $data['is_active'] = (bool)($data['is_active'] ?? false);
        $notification->update($data);
        return redirect()->route('admin.notifications.show', $notification)->with('status', 'Notification updated.');
    }

    public function destroy(Notification $notification)
    {
        $notification->delete();
        return redirect()->route('admin.notifications.index')->with('status', 'Notification deleted.');
    }

    public function publish(Notification $notification)
    {
        $notification->update(['is_active' => true, 'starts_at' => $notification->starts_at ?? now()]);
        return back()->with('status', 'Notification published.');
    }

    public function unpublish(Notification $notification)
    {
        $notification->update(['is_active' => false]);
        return back()->with('status', 'Notification unpublished.');
    }

    public function resend(Notification $notification)
    {
        $copy = $notification->replicate(['views','clicks','created_at','updated_at']);
        $copy->starts_at = now();
        $copy->ends_at = $notification->ends_at; // optionally keep or null
        $copy->is_active = true;
        $copy->views = 0;
        $copy->clicks = 0;
        $copy->save();
        return redirect()->route('admin.notifications.show', $copy)->with('status', 'Notification resent (new copy published).');
    }
}
