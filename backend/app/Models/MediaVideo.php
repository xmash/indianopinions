<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class MediaVideo extends Model
{
    protected $fillable = [
        'title',
        'description',
        'video_url',
        'thumbnail_url',
        'duration_seconds',
        'category',
        'sort_order',
        'featured',
        'is_published',
    ];

    protected $casts = [
        'featured' => 'boolean',
        'is_published' => 'boolean',
        'duration_seconds' => 'integer',
        'sort_order' => 'integer',
    ];

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }
}
