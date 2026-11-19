<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GalleryPhoto extends Model
{
    public const CATEGORIES = [
        'academics' => 'Academic Activities',
        'campus-life' => 'Campus Life',
        'events' => 'School Events',
    ];

    protected $fillable = [
        'title',
        'category',
        'image_path',
        'alt_text',
        'views',
        'sort_order',
        'user_id',
        'is_approved',
    ];

    protected function casts(): array
    {
        return [
            'views' => 'integer',
            'sort_order' => 'integer',
            'is_approved' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    public function getCategoryLabelAttribute(): string
    {
        return self::CATEGORIES[$this->category] ?? $this->category;
    }

    public function getImageUrlAttribute(): string
    {
        if (str_starts_with($this->image_path, 'pending-content/photos/')) {
            return route('account.content.photos.preview', $this);
        }

        return str_starts_with($this->image_path, 'gallery-photos/')
            ? asset('storage/'.$this->image_path)
            : asset('image/'.$this->image_path);
    }
}
