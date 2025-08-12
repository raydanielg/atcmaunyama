<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    protected $fillable = [
        'name',
        'description',
        'icon',
    ];

    public function classes()
    {
        return $this->hasMany(SchoolClass::class, 'level_id');
    }
}
