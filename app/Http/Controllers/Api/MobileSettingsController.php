<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MobileAppSetting;
use Illuminate\Http\Request;

class MobileSettingsController extends Controller
{
    public function show(Request $request)
    {
        $settings = MobileAppSetting::query()->first();

        $appUrl = rtrim(config('app.url'), '/');
        $googleCallback = $appUrl . '/oauth/google/callback';

        // Defaults if not set yet
        $payload = [
            'branding' => [
                'appIconUrl' => $settings?->app_icon_path,
                'splashImageUrl' => $settings?->splash_image_path,
                'splashHeadline' => $settings?->splash_headline ?? '',
                'splashSubtext' => $settings?->splash_subtext ?? '',
            ],
            'notifications' => [
                'show' => $settings?->show_notifications ?? true,
            ],
            'update' => [
                'required' => $settings?->app_update_required ?? false,
                'version' => $settings?->app_update_version,
                'notes' => $settings?->app_update_notes,
                'forceAfter' => optional($settings?->app_update_force_after)->toIso8601String(),
            ],
            'oauth' => [
                'googleCallbackUrl' => $googleCallback,
            ],
            'premium' => [
                'enabled' => $settings?->premium_enabled ?? false,
                'provider' => $settings?->premium_provider,
                'price' => $settings?->premium_price,
                'currency' => $settings?->premium_currency,
                'features' => $settings?->premium_features ?? [],
                'selcom' => [
                    'merchantId' => $settings?->selcom_merchant_id,
                    'env' => $settings?->selcom_env,
                    'callbackUrl' => $settings?->selcom_callback_url,
                ],
            ],
            'meta' => $settings?->meta ?? new \stdClass(),
        ];

        return response()->json($payload);
    }
}
