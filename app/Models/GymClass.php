<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GymClass extends Model
{
    protected $fillable = [
        'name',
        'description',
        'location',
        'price',
        'duration',
        'start_time',
        'end_time',
        'max_participants',
    ];
}
