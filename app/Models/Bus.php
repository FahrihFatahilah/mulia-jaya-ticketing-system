<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bus extends Model
{
    protected $fillable = ['code', 'name', 'seat_count', 'departure_time', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
        'seat_count' => 'integer',
        'departure_time' => 'datetime:H:i',
    ];

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }
}