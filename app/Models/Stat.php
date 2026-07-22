<?php

namespace App\Models;

use App\Models\Concerns\Sortable;
use Illuminate\Database\Eloquent\Model;

class Stat extends Model
{
    use Sortable;

    protected $table = 'stats';

    protected $fillable = ['value','suffix','is_counter','label','sort','is_active'];

    protected $casts = ['is_active' => 'bool', 'is_counter' => 'bool'];
}
