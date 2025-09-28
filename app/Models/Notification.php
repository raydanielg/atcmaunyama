<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'title','message','action_label','action_url','starts_at','ends_at','is_active','views','clicks'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    // Scope for currently active for public API
    public function scopeCurrentlyActive(Builder $q): Builder
    {
        $now = now();
        return $q->where('is_active', true)
            ->when(true, function($qq) use ($now){
                $qq->where(function($w) use ($now){
                    $w->whereNull('starts_at')->orWhere('starts_at','<=',$now);
                });
                $qq->where(function($w) use ($now){
                    $w->whereNull('ends_at')->orWhere('ends_at','>=',$now);
                });
            });
    }

    public function getStatusAttribute(): string
    {
        $now = now();
        if ($this->is_active !== true) return 'inactive';
        if ($this->starts_at && $this->starts_at->isFuture()) return 'scheduled';
        if ($this->ends_at && $this->ends_at->isPast()) return 'expired';
        return 'active';
    }
}
