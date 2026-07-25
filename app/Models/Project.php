<?php

namespace App\Models;

use App\Models\Concerns\Sortable;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use Sortable;

    protected $fillable = ['title', 'slug', 'tag', 'body', 'image', 'sort', 'is_active'];

    protected $casts = ['is_active' => 'bool'];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /** التصنيفات المتاحة للفلترة في صفحة الأعمال. */
    public static function tags(): array
    {
        return static::query()->active()->whereNotNull('tag')
            ->distinct()->orderBy('tag')->pluck('tag')->all();
    }
}
