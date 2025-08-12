<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginAttempt extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'email',
        'ip',
        'user_agent',
        'success',
        'created_at',
    ];
}
