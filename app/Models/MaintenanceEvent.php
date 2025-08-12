<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'is_enabled',
        'message',
        'started_at',
        'ended_at',
        'user_id',
        'meta',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'meta' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
