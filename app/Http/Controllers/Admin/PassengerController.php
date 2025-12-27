<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Passenger;
use Illuminate\Http\Request;

class PassengerController extends Controller
{
    public function index(Request $request)
    {
        $query = Passenger::with(['bookingDetails.booking.schedule.route.originBranch', 'bookingDetails.booking.schedule.route.destinationBranch']);
        
        if ($request->search) {
            $query->where('phone', 'like', '%' . $request->search . '%')
                  ->orWhere('name', 'like', '%' . $request->search . '%');
        }
        
        $passengers = $query->orderBy('created_at', 'desc')->paginate(20);
        
        return view('admin.passengers.index', compact('passengers'));
    }
    
    public function history($phone)
    {
        $passenger = Passenger::with(['bookingDetails.booking.schedule.route.originBranch', 'bookingDetails.booking.schedule.route.destinationBranch'])
            ->findOrFail($phone);
            
        $history = $passenger->bookingDetails->map(function($detail) {
            return [
                'booking_code' => $detail->booking->booking_code,
                'route' => $detail->booking->schedule->route->originBranch->name . ' → ' . $detail->booking->schedule->route->destinationBranch->name,
                'seat_number' => $detail->seat_number,
                'price' => number_format($detail->price),
                'date' => $detail->created_at->format('d/m/Y H:i')
            ];
        })->sortByDesc('date')->values();
        
        return response()->json($history);
    }
}