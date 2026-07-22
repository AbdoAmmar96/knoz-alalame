<?php

namespace App\Models;

use App\Models\Concerns\Sortable;
use Illuminate\Database\Eloquent\Model;

class ProcessStep extends Model
{
    use Sortable;

    protected $table = 'process_steps';

    protected $fillable = ['title','body','icon','sort','is_active'];

    protected $casts = ['is_active' => 'bool'];
}
