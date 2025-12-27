<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Passenger extends Model
{
    protected $primaryKey = 'phone';
    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = ['phone', 'name'];
    
    public function bookingDetails(): HasMany
    {
        return $this->hasMany(BookingDetail::class, 'passenger_phone', 'phone');
    }
}
