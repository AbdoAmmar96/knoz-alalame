<?php

namespace App\Models;

use App\Models\Concerns\Sortable;
use Illuminate\Database\Eloquent\Model;

class Sector extends Model
{
    use Sortable;

    protected $table = 'sectors';

    protected $fillable = ['number','title','body','image','icon','sort','is_active'];

    protected $casts = ['is_active' => 'bool'];
}
