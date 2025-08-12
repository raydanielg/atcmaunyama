<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MobileNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'message',
        'deep_link',
        'scheduled_at',
        'repeat',
        'status',
        'sent_at',
        'delivered_count',
        'opened_count',
        'clicked_count',
        'targets',
        'meta',
        'error',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
        'targets' => 'array',
        'meta' => 'array',
    ];
}
