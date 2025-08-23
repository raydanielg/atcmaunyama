<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Material extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'category_id',
        'subcategory_id',
        'sub_subcategory_id',
        'path',
        'url',
        'mime',
        'size',
        'user_id',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(Subcategory::class);
    }

    public function subSubcategory(): BelongsTo
    {
        return $this->belongsTo(SubSubcategory::class, 'sub_subcategory_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
