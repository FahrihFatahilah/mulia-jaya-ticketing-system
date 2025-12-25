<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Booking extends Model
{
    protected $fillable = [
        'booking_code', 'schedule_id', 'type', 'purchase_type', 'agent_code', 'office', 'payment_method', 
        'payment_status', 'payment_description', 'total_amount', 'created_by'
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
    ];

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function details(): HasMany
    {
        return $this->hasMany(BookingDetail::class);
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($booking) {
            $booking->booking_code = static::generateBookingCode();
        });
        
        static::updated(function ($booking) {
            if ($booking->wasChanged('payment_status') && $booking->payment_status === 'paid') {
                // Generate daily report based on departure date
                \App\Models\DailyReport::generateDaily($booking->schedule->departure_date->toDateString());
            }
        });
    }

    public static function generateBookingCode(): string
    {
        do {
            $code = 'TK' . date('Ymd') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (static::where('booking_code', $code)->exists());
        
        return $code;
    }
}