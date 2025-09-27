<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Material extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'subcategory_id',
        'sub_subcategory_id',
        'level_id',
        'subject_id',
        'class_id',
        'path',
        'url',
        'mime',
        'size',
        'user_id',
    ];

    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(Subcategory::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Optional learning relations (nullable)
    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class, 'level_id');
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function class(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function subSubcategory(): BelongsTo
    {
        return $this->belongsTo(SubSubcategory::class, 'sub_subcategory_id');
    }
}
