<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HeroSlide extends Model
{
    protected $fillable = [
        'image_path',
        'alt_text',
        'sort_order',
        'is_enabled',
        'user_id',
        'is_approved',
        'rejected_at',
    ];

    protected function casts(): array
    {
        return [
            'is_enabled' => 'boolean',
            'is_approved' => 'boolean',
            'rejected_at' => 'datetime',
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

    public function getImageUrlAttribute(): string
    {
        if (str_starts_with($this->image_path, 'pending-content/hero-slides/')) {
            return route('account.content.hero.preview', $this);
        }

        if (str_starts_with($this->image_path, 'hero-slides/')) {
            return asset('storage/'.$this->image_path);
        }

        return asset($this->image_path);
    }
}
