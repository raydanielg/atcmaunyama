<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MobileAppSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'app_icon_path',
        'splash_image_path',
        'splash_headline',
        'splash_subtext',
        'show_notifications',
        'app_update_required',
        'app_update_version',
        'app_update_notes',
        'app_update_force_after',
        'google_callback_url',
        'premium_enabled',
        'premium_provider',
        'premium_price',
        'premium_currency',
        'premium_features',
        'selcom_merchant_id',
        'selcom_api_key',
        'selcom_env',
        'selcom_callback_url',
        'meta',
    ];

    protected $casts = [
        'show_notifications' => 'boolean',
        'app_update_required' => 'boolean',
        'app_update_force_after' => 'datetime',
        'premium_enabled' => 'boolean',
        'premium_features' => 'array',
        'meta' => 'array',
    ];
}
