<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
 

class Subcategory extends Model
{
    protected $fillable = [
        'name',
        'year',
        'icon',
    ];
    // Relations to Category and SubSubcategory removed (modules deprecated)
}
