<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    /**
     * Users enrolled in this gym class (Many-to-Many).
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'gym_class_user', 'gym_class_id', 'user_id')
            ->withTimestamps();
    }
}
