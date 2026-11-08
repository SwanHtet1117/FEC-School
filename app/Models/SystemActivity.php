<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemActivity extends Model
{
    protected $fillable = [
        'user_id',
        'user_name',
        'action',
        'details',
        'status',
        'device',
        'timezone',
    ];
}
