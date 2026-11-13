<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $fillable = [
        'parent_name',
        'student_context',
        'quote',
        'sort_order',
    ];
}
