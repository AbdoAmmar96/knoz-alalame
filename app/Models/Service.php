<?php

namespace App\Models;

use App\Models\Concerns\Sortable;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use Sortable;

    protected $fillable = [
        'title', 'slug', 'body', 'long_body', 'image', 'features', 'sort', 'is_active',
    ];

    protected $casts = [
        'features' => 'array',
        'is_active' => 'bool',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
