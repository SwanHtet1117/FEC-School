<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'date',
        ];
    }

    public function getImageUrlAttribute(): string
    {
        return str_starts_with($this->image_path, 'posts/')
            ? asset('storage/'.$this->image_path)
            : asset('image/'.$this->image_path);
    }
}
