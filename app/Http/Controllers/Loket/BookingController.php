<?php

namespace App\Http\Controllers\Loket;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingDetail;
use App\Models\Route;
use App\Models\Schedule;
use App\Models\Bus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Passenger;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['schedule.route.originBranch', 'schedule.route.destinationBranch', 'schedule.bus'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('loket.bookings.index', compact('bookings'));
    }

    public function create()
    {
        $routes = Route::with(['originBranch', 'destinationBranch'])
            ->where('is_active', true)
            ->get();
            
        return view('loket.bookings.create', compact('routes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'route_id' => 'required|exists:routes,id',
            'departure_date' => 'required|date|after_or_equal:today',
            'bus_id' => 'required|exists:buses,id',
            'type' => 'required|in:passenger,cargo',
            'details' => 'required|array|min:1',
        ]);

        DB::transaction(function () use ($request) {
            // Create or get schedule
            $schedule = Schedule::firstOrCreate([
                'route_id' => $request->route_id,
                'bus_id' => $request->bus_id,
                'departure_date' => $request->departure_date,
            ]);

            // Get data from session or request
            $ticketPrice = $request->ticket_price ?? session('ticket_price', 0);
            $purchaseType = $request->purchase_type ?? session('purchase_type', 'kantor');
            $agentCode = $request->agent_code ?? session('agent_code');
            $paymentMethod = $request->payment_method ?? session('payment_method', 'cash');
            $paymentDescription = $request->payment_description ?? session('payment_description');

            // Debug: Log the values
            \Log::info('Booking store values:', [
                'ticket_price' => $ticketPrice,
                'details_count' => count($request->details),
                'session_ticket_price' => session('ticket_price'),
                'request_ticket_price' => $request->ticket_price
            ]);

            // Calculate total amount (ticket price * number of passengers)
            $ticketPricePerPerson = (int) $ticketPrice;
            $totalAmount = $ticketPricePerPerson * count($request->details);

            // Create booking
            $booking = Booking::create([
                'schedule_id' => $schedule->id,
                'type' => $request->type,
                'purchase_type' => $purchaseType,
                'agent_code' => $agentCode,
                'office' => auth()->user()->office ?? 'bima',
                'payment_method' => $paymentMethod,
                'payment_description' => $paymentDescription,
                'total_amount' => $totalAmount,
                'created_by' => auth()->id() ?? 1,
            ]);

            // Create booking details
            foreach ($request->details as $detail) {
                // Create passenger master data only if phone doesn't exist
                if (isset($detail['passenger_phone']) && isset($detail['passenger_name'])) {
                    Passenger::firstOrCreate(
                        ['phone' => $detail['passenger_phone']],
                        ['name' => $detail['passenger_name']]
                    );
                }
                
                BookingDetail::create([
                    'booking_id' => $booking->id,
                    'passenger_name' => $detail['passenger_name'] ?? null,
                    'passenger_phone' => $detail['passenger_phone'] ?? null,
                    'seat_number' => $detail['seat_number'] ?? null,
                    'sender_name' => $detail['sender_name'] ?? null,
                    'receiver_name' => $detail['receiver_name'] ?? null,
                    'cargo_type' => $detail['cargo_type'] ?? null,
                    'weight' => $detail['weight'] ?? null,
                    'price' => $ticketPricePerPerson,
                ]);
            }
        });

        // Clear session data
        session()->forget(['ticket_price', 'purchase_type', 'agent_code', 'payment_method', 'payment_description']);

        return redirect()->route('loket.bookings.index')
            ->with('success', 'Pemesanan berhasil dibuat');
    }

    public function checkSeats(Request $request)
    {
        $schedule = Schedule::where([
            'route_id' => $request->route_id,
            'bus_id' => $request->bus_id,
            'departure_date' => $request->departure_date,
        ])->first();

        if (!$schedule) {
            $bus = Bus::find($request->bus_id);
            return response()->json([
                'available' => $bus ? $bus->seat_count : 32,
                'occupied' => 0
            ]);
        }

        $occupiedCount = count($schedule->occupied_seats);
        $available = $schedule->bus->seat_count - $occupiedCount;

        return response()->json([
            'available' => $available,
            'occupied' => $occupiedCount
        ]);
    }

    public function createSchedule(Request $request)
    {
        $request->validate([
            'route_id' => 'required|exists:routes,id',
            'bus_id' => 'required|exists:buses,id',
            'departure_date' => 'required|date|after_or_equal:today',
        ]);

        $schedule = Schedule::firstOrCreate([
            'route_id' => $request->route_id,
            'bus_id' => $request->bus_id,
            'departure_date' => $request->departure_date,
        ]);

        // Store booking data in session for seat selection
        session([
            'ticket_price' => (int) str_replace('.', '', $request->ticket_price ?? '0'),
            'purchase_type' => $request->purchase_type,
            'agent_code' => $request->agent_code,
            'payment_method' => $request->payment_method,
            'payment_description' => $request->payment_description,
        ]);

        return redirect()->route('loket.seat-selection', $schedule);
    }

    public function seatSelection(Schedule $schedule)
    {
        // Check if required session data exists
        if (!session('ticket_price') || !session('purchase_type') || !session('payment_method')) {
            return redirect()->route('loket.bookings.create')
                ->with('error', 'Data pemesanan tidak lengkap. Silakan isi form kembali.');
        }

        $schedule->load(['route.originBranch', 'route.destinationBranch', 'bus']);
        $occupiedSeats = $schedule->occupied_seats;
        
        return view('loket.seat-selection', compact('schedule', 'occupiedSeats'));
    }

    public function payment(Request $request, Booking $booking)
    {
        $request->validate([
            'payment_status' => 'required|in:paid',
        ]);

        $booking->update([
            'payment_status' => $request->payment_status
        ]);

        return redirect()->route('loket.bookings.show', $booking)
            ->with('success', 'Pembayaran berhasil dikonfirmasi');
    }

    public function ticket(Booking $booking)
    {
        $booking->load(['schedule.route.originBranch', 'schedule.route.destinationBranch', 'schedule.bus', 'details']);
        
        return view('loket.ticket', compact('booking'));
    }

    public function cancel(Booking $booking)
    {
        // Check if booking can be cancelled (only if not paid)
        if ($booking->payment_status === 'paid') {
            return redirect()->back()->with('error', 'Tiket yang sudah dibayar tidak dapat dibatalkan.');
        }

        // Delete booking and its details
        $booking->details()->delete();
        $booking->delete();

        return redirect()->route('loket.bookings.index')
            ->with('success', 'Pemesanan berhasil dibatalkan.');
    }

    public function getBusesByRoute(Request $request)
    {
        $buses = Bus::where('is_active', true)
            ->where('route_id', $request->route_id)
            ->get(['id', 'name', 'code', 'departure_time', 'seat_count'])
            ->map(function($bus) {
                return [
                    'id' => $bus->id,
                    'name' => $bus->name,
                    'code' => $bus->code,
                    'departure_time' => $bus->departure_time->format('H:i'),
                    'seat_count' => $bus->seat_count
                ];
            });
            
        return response()->json($buses);
    }

    public function show(Booking $booking)
    {
        $booking->load(['schedule.route.originBranch', 'schedule.route.destinationBranch', 'schedule.bus', 'details']);
        
        return view('loket.bookings.show', compact('booking'));
    }
}