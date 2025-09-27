<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Note extends Model
{
    protected $fillable = [
        'title',
        'body',
        'user_id',
        'subject_id',
        'level_id',
        'class_id',
        'semister_id',
        'file_path',
        'original_name',
        'mime_type',
        'file_size',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function semister(): BelongsTo
    {
        return $this->belongsTo(Semister::class);
    }
}
