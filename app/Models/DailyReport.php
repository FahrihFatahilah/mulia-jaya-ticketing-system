<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyReport extends Model
{
    protected $fillable = [
        'report_date', 'total_bookings', 'passenger_bookings', 'cargo_bookings',
        'agent_bookings', 'kantor_bookings', 'total_income', 'agent_income', 'kantor_income'
    ];

    protected $casts = [
        'report_date' => 'date',
        'total_income' => 'decimal:2',
        'agent_income' => 'decimal:2',
        'kantor_income' => 'decimal:2',
    ];

    public static function generateDaily($date)
    {
        // Bookings based on departure date, not booking date
        $bookings = Booking::whereHas('schedule', function($q) use ($date) {
            $q->whereDate('departure_date', $date);
        })->where('payment_status', 'paid')->get();
        
        $report = static::updateOrCreate(['report_date' => $date], [
            'total_bookings' => $bookings->count(),
            'passenger_bookings' => $bookings->where('type', 'passenger')->count(),
            'cargo_bookings' => $bookings->where('type', 'cargo')->count(),
            'agent_bookings' => $bookings->where('purchase_type', 'agent')->count(),
            'kantor_bookings' => $bookings->where('purchase_type', 'kantor')->count(),
            'total_income' => $bookings->sum('total_amount'),
            'agent_income' => $bookings->where('purchase_type', 'agent')->sum('total_amount'),
            'kantor_income' => $bookings->where('purchase_type', 'kantor')->sum('total_amount'),
        ]);
        
        return $report;
    }
}