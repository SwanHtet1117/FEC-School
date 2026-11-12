<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class CareerOpportunity extends Model
{
    protected $fillable = [
        'title',
        'department',
        'employment_type',
        'location',
        'description',
        'application_email',
        'closes_at',
    ];

    protected function casts(): array
    {
        return [
            'closes_at' => 'date',
        ];
    }

    public function scopeOpen(Builder $query): Builder
    {
        return $query->where(function (Builder $query): void {
            $query->whereNull('closes_at')
                ->orWhereDate('closes_at', '>=', today());
        });
    }
}
