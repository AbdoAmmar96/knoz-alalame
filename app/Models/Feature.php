<?php

namespace App\Models;

use App\Models\Concerns\Sortable;
use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    use Sortable;

    protected $table = 'features';

    protected $fillable = ['title','body','icon','sort','is_active'];

    protected $casts = ['is_active' => 'bool'];
}
