<?php

namespace App\Models;

use App\Models\Concerns\Sortable;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use Sortable;

    protected $table = 'faqs';

    protected $fillable = ['question','answer','sort','is_active'];

    protected $casts = ['is_active' => 'bool'];
}
