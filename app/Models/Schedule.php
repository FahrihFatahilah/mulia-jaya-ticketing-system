<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Schedule extends Model
{
    protected $fillable = ['route_id', 'bus_id', 'departure_date', 'is_active'];

    protected $casts = [
        'departure_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function route(): BelongsTo
    {
        return $this->belongsTo(Route::class);
    }

    public function bus(): BelongsTo
    {
        return $this->belongsTo(Bus::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function cashFlows(): HasMany
    {
        return $this->hasMany(CashFlow::class);
    }

    public function getOccupiedSeatsAttribute(): array
    {
        return $this->bookings()
            ->where('type', 'passenger')
            ->where('payment_status', 'paid')
            ->with('details')
            ->get()
            ->flatMap(fn($booking) => $booking->details->pluck('seat_number'))
            ->filter()
            ->values()
            ->toArray();
    }

    public function getAvailableSeatsCountAttribute(): int
    {
        return $this->bus->seat_count - count($this->occupied_seats);
    }

    public function getDepartureTimeAttribute()
    {
        return $this->bus->departure_time;
    }
}