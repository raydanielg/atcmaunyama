<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MobileAppSetting;
use App\Models\MobileNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MobileAppSettingsController extends Controller
{
    public function index(Request $request)
    {
        $settings = MobileAppSetting::query()->first();
        if (!$settings) {
            $settings = new MobileAppSetting([
                'show_notifications' => true,
                'premium_enabled' => false,
            ]);
        }

        $appUrl = config('app.url');
        $googleCallback = rtrim($appUrl, '/').'/oauth/google/callback';

        return view('admin.mobile.settings', [
            'settings' => $settings,
            'googleCallback' => $googleCallback,
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'splash_headline' => 'nullable|string|max:120',
            'splash_subtext' => 'nullable|string|max:200',
            'show_notifications' => 'nullable|boolean',
            'app_update_required' => 'nullable|boolean',
            'app_update_version' => 'nullable|string|max:40',
            'app_update_notes' => 'nullable|string|max:1000',
            'app_update_grace_minutes' => 'nullable|integer|min:0|max:43200',
            'notify_users_now' => 'nullable|boolean',
            'premium_enabled' => 'nullable|boolean',
            'premium_provider' => 'nullable|string|in:selcom',
            'premium_price' => 'nullable|numeric',
            'premium_currency' => 'nullable|string|max:10',
            'premium_features' => 'nullable', // from checkboxes
            'premium_features_custom' => 'nullable|string', // from textarea
            // Selcom
            'selcom_merchant_id' => 'nullable|string|max:100',
            'selcom_api_key' => 'nullable|string|max:200',
            'selcom_env' => 'nullable|string|in:sandbox,production',
            'selcom_callback_url' => 'nullable|url',
            'app_icon' => 'nullable|file|mimes:png,ico|max:2048',
            'splash_image' => 'nullable|image|max:4096',
        ]);

        $settings = MobileAppSetting::query()->first() ?? new MobileAppSetting();

        // File uploads
        if ($request->hasFile('app_icon')) {
            $path = $request->file('app_icon')->store('public/app');
            $settings->app_icon_path = Storage::url($path);
        }
        if ($request->hasFile('splash_image')) {
            $path = $request->file('splash_image')->store('public/app');
            $settings->splash_image_path = Storage::url($path);
        }

        // Simple assignments
        $settings->splash_headline = $data['splash_headline'] ?? null;
        $settings->splash_subtext = $data['splash_subtext'] ?? null;
        $settings->show_notifications = (bool)($data['show_notifications'] ?? false);
        $settings->app_update_required = (bool)($data['app_update_required'] ?? false);
        $settings->app_update_version = $data['app_update_version'] ?? null;
        $settings->app_update_notes = $data['app_update_notes'] ?? null;
        // Force-after timestamp from grace minutes
        $grace = $data['app_update_grace_minutes'] ?? null;
        if ($settings->app_update_required && $grace !== null) {
            $settings->app_update_force_after = now()->addMinutes((int)$grace);
        } elseif (!$settings->app_update_required) {
            $settings->app_update_force_after = null;
        }

        // callback URL is generated from app.url
        $settings->google_callback_url = rtrim(config('app.url'), '/').'/oauth/google/callback';

        $settings->premium_enabled = (bool)($data['premium_enabled'] ?? false);
        $settings->premium_provider = $data['premium_provider'] ?? ($settings->premium_enabled ? 'selcom' : null);
        $settings->premium_price = $data['premium_price'] ?? null;
        $settings->premium_currency = $data['premium_currency'] ?? null;

        // Premium features: merge checkboxes and custom textarea
        $fromChecks = [];
        if (isset($data['premium_features']) && is_array($data['premium_features'])) {
            $fromChecks = array_values(array_filter(array_map('trim', $data['premium_features'])));
        }
        $fromTextarea = [];
        if (!empty($data['premium_features_custom'])) {
            $fromTextarea = array_values(array_filter(array_map('trim', preg_split('/\r?\n/', $data['premium_features_custom']))));
        }
        $merged = array_values(array_unique(array_filter(array_merge($fromChecks, $fromTextarea))));
        $settings->premium_features = $merged ?: null;

        // Selcom config
        $settings->selcom_merchant_id = $data['selcom_merchant_id'] ?? null;
        $settings->selcom_api_key = $data['selcom_api_key'] ?? null;
        $settings->selcom_env = $data['selcom_env'] ?? null;
        $settings->selcom_callback_url = $data['selcom_callback_url'] ?? null;

        $settings->save();

        // If update required and admin requests notification now, create MobileNotification
        if ($settings->app_update_required && ($data['notify_users_now'] ?? false)) {
            MobileNotification::create([
                'title' => 'App Update Available',
                'message' => trim(($settings->app_update_notes ? $settings->app_update_notes.' ' : '') . ($settings->app_update_version ? '(v'.$settings->app_update_version.')' : '')) ?: 'A new update is available.',
                'deep_link' => 'app://update',
                'status' => 'queued',
                'meta' => [
                    'type' => 'app_update',
                    'force_after' => optional($settings->app_update_force_after)->toIso8601String(),
                ],
            ]);
        }

        return redirect()->route('mobile.settings')->with('status', 'Mobile app settings saved.');
    }
}
