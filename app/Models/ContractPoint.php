<?php

namespace App\Models;

use App\Models\Concerns\Sortable;
use Illuminate\Database\Eloquent\Model;

class ContractPoint extends Model
{
    use Sortable;

    protected $table = 'contract_points';

    protected $fillable = ['title','sort','is_active'];

    protected $casts = ['is_active' => 'bool'];
}
