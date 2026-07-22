<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

/** سلوك مشترك لكل جداول المحتوى المرتّبة والقابلة للتعطيل. */
trait Sortable
{
    public function scopeActive(Builder $q): Builder
    {
        return $q->where('is_active', true);
    }

    public function scopeOrdered(Builder $q): Builder
    {
        return $q->orderBy('sort')->orderBy('id');
    }

    /** العناصر الظاهرة في الموقع بترتيبها. */
    public static function live()
    {
        return static::query()->active()->ordered()->get();
    }
}
