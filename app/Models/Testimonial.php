<?php

namespace App\Models;

use App\Models\Concerns\Sortable;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use Sortable;

    protected $table = 'testimonials';

    protected $fillable = ['name','role','body','rating','sort','is_active'];

    protected $casts = ['is_active' => 'bool', 'rating' => 'int'];
}
