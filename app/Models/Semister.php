<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Semister extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'is_active',
        'description'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean'
    ];

    /**
     * Get the notes for this semester
     */
    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    /**
     * Scope to get only active semesters
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
