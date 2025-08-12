<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminSetting extends Model
{
    protected $fillable = [
        'site_name',
        'site_url',
        'site_icon_path',
        'favicon_path',
        'footer_text',
        'contact_email',
        'meta',
        'mail_host',
        'mail_port',
        'mail_username',
        'mail_password',
        'mail_encryption',
        'mail_from_address',
        'mail_from_name',
    ];

    protected $casts = [
        'meta' => 'array',
    ];
}
