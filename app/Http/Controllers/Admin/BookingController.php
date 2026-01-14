<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['schedule.route.originBranch', 'schedule.route.destinationBranch', 'schedule.bus'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('admin.bookings.index', compact('bookings'));
    }

    public function edit(Booking $booking)
    {
        $booking->load(['schedule.route.originBranch', 'schedule.route.destinationBranch', 'schedule.bus', 'details']);
        return view('admin.bookings.edit', compact('booking'));
    }

    public function update(Request $request, Booking $booking)
    {
        $request->validate([
            'payment_status' => 'required|in:pending,paid',
            'payment_method' => 'required|in:cash,non_cash',
        ]);

        $booking->update([
            'payment_status' => $request->payment_status,
            'payment_method' => $request->payment_method,
            'payment_description' => $request->payment_description,
        ]);

        return redirect()->route('admin.bookings.index')
            ->with('success', 'Booking berhasil diupdate');
    }

    public function destroy(Booking $booking)
    {
        $booking->details()->delete();
        $booking->delete();

        return redirect()->route('admin.bookings.index')
            ->with('success', 'Booking berhasil dihapus');
    }
}
