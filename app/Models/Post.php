<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    protected $fillable = [
        'slug',
        'title',
        'category',
        'published_at',
        'image_path',
        'alt_text',
        'excerpt',
        'body',
        'location',
        'department',
        'user_id',
        'is_approved',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'date',
            'is_approved' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getImageUrlAttribute(): string
    {
        if (str_starts_with($this->image_path, 'pending-content/posts/')) {
            return route('account.content.posts.preview', $this);
        }

        return str_starts_with($this->image_path, 'posts/')
            ? asset('storage/'.$this->image_path)
            : asset('image/'.$this->image_path);
    }
}
