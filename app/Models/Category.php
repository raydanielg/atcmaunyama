<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    protected $fillable = [
        'name',
        'icon',
    ];

    public function subcategories(): HasMany
    {
        return $this->hasMany(Subcategory::class);
    }

    public function materials(): HasMany
    {
        return $this->hasMany(Material::class);
    }

    /**
     * Many-to-many: types assigned to this level via pivot table.
     */
    public function types(): BelongsToMany
    {
        return $this->belongsToMany(Subcategory::class, 'category_subcategory', 'category_id', 'subcategory_id');
    }
}
