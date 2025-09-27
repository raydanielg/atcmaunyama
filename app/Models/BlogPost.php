<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class BlogPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'title', 'slug', 'image_path', 'excerpt', 'content', 'views',
    ];

    protected static function booted(): void
    {
        static::creating(function (BlogPost $post) {
            if (empty($post->slug)) {
                $post->slug = Str::slug($post->title) . '-' . Str::random(5);
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(BlogComment::class, 'post_id')->latest('id');
    }

    public function reactions(): HasMany
    {
        return $this->hasMany(BlogReaction::class, 'post_id');
    }

    public function likesCount(): int
    {
        return (int) $this->reactions()->where('type','like')->count();
    }

    public function dislikesCount(): int
    {
        return (int) $this->reactions()->where('type','dislike')->count();
    }
}
