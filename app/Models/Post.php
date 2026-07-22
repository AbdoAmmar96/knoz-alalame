<?php

namespace App\Models;

use App\Models\Concerns\Sortable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use Sortable;

    protected $fillable = [
        'title', 'slug', 'excerpt', 'body', 'tag', 'image',
        'source_url', 'published_at', 'sort', 'is_active',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_active' => 'bool',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /** المنشور فعلاً: مفعّل وتاريخ نشره حلّ. */
    public function scopePublished(Builder $q): Builder
    {
        return $q->active()->where(function ($q) {
            $q->whereNull('published_at')->orWhere('published_at', '<=', now());
        });
    }

    /** المقالات المنقولة من الموقع القديم بلا نص كامل تُفتح على مصدرها. */
    public function getUrlAttribute(): string
    {
        return $this->body ? route('blog.show', $this) : ($this->source_url ?: route('blog.index'));
    }

    public function getIsExternalAttribute(): bool
    {
        return ! $this->body && (bool) $this->source_url;
    }
}
