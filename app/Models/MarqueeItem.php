<?php

namespace App\Models;

use App\Models\Concerns\Sortable;
use Illuminate\Database\Eloquent\Model;

class MarqueeItem extends Model
{
    use Sortable;

    protected $table = 'marquee_items';

    protected $fillable = ['title','sort','is_active'];

    protected $casts = ['is_active' => 'bool'];
}
