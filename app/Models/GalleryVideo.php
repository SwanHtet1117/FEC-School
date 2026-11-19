<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GalleryVideo extends Model
{
    public const CATEGORIES = [
        'events' => 'School Events',
        'campus-life' => 'Campus Life',
        'academics' => 'Academic Activities',
    ];

    protected $fillable = [
        'title',
        'category',
        'youtube_id',
        'duration',
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

    public function getThumbnailUrlAttribute(): ?string
    {
        return $this->youtube_id
            ? "https://i.ytimg.com/vi/{$this->youtube_id}/hqdefault.jpg"
            : null;
    }
}
