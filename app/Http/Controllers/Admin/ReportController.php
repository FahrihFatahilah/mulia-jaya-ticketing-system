<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Schedule;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Get bookings (income)
        $bookingQuery = Booking::with(['schedule.route.originBranch', 'schedule.route.destinationBranch', 'schedule.bus'])
            ->selectRaw('id, booking_code, schedule_id, type, office, total_amount, payment_status, created_at, "booking" as transaction_type');
        
        // Get cash flows (expenses)
        $cashFlowQuery = \App\Models\CashFlow::whereIn('type', ['expense', 'office_expense'])
            ->selectRaw('id, description as booking_code, null as schedule_id, type, office, amount as total_amount, "completed" as payment_status, created_at, "cashflow" as transaction_type');
        
        // Filter by date range
        if ($request->filled('start_date')) {
            $bookingQuery->whereHas('schedule', function($q) use ($request) {
                $q->whereDate('departure_date', '>=', $request->start_date);
            });
            $cashFlowQuery->whereDate('created_at', '>=', $request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $bookingQuery->whereHas('schedule', function($q) use ($request) {
                $q->whereDate('departure_date', '<=', $request->end_date);
            });
            $cashFlowQuery->whereDate('created_at', '<=', $request->end_date);
        }
        
        // Filter by route (only for bookings)
        if ($request->filled('route_id')) {
            $bookingQuery->whereHas('schedule', function($q) use ($request) {
                $q->where('route_id', $request->route_id);
            });
        }
        
        // Filter by bus (only for bookings)
        if ($request->filled('bus_id')) {
            $bookingQuery->whereHas('schedule', function($q) use ($request) {
                $q->where('bus_id', $request->bus_id);
            });
        }
        
        // Filter by office
        if ($request->filled('office')) {
            $bookingQuery->where('office', $request->office);
            $cashFlowQuery->where('office', $request->office);
        }
        
        // Combine and paginate
        $reports = $bookingQuery->union($cashFlowQuery)
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        // Load relationships for bookings
        $reports->getCollection()->transform(function ($item) {
            if ($item->transaction_type === 'booking') {
                $booking = Booking::with(['schedule.route.originBranch', 'schedule.route.destinationBranch', 'schedule.bus'])
                    ->find($item->id);
                $item->schedule = $booking->schedule ?? null;
            }
            return $item;
        });
        
        // Summary statistics
        $totalIncome = Booking::where('payment_status', 'paid')
            ->when($request->filled('start_date'), function($q) use ($request) {
                $q->whereHas('schedule', function($sq) use ($request) {
                    $sq->whereDate('departure_date', '>=', $request->start_date);
                });
            })
            ->when($request->filled('end_date'), function($q) use ($request) {
                $q->whereHas('schedule', function($sq) use ($request) {
                    $sq->whereDate('departure_date', '<=', $request->end_date);
                });
            })
            ->when($request->filled('office'), function($q) use ($request) {
                $q->where('office', $request->office);
            })
            ->sum('total_amount');
        
        $totalExpenses = \App\Models\CashFlow::whereIn('type', ['expense', 'office_expense'])
            ->when($request->filled('start_date'), function($q) use ($request) {
                $q->whereDate('created_at', '>=', $request->start_date);
            })
            ->when($request->filled('end_date'), function($q) use ($request) {
                $q->whereDate('created_at', '<=', $request->end_date);
            })
            ->when($request->filled('office'), function($q) use ($request) {
                $q->where('office', $request->office);
            })
            ->sum('amount');
        
        $netIncome = $totalIncome - $totalExpenses;
        
        $summary = [
            'total_bookings' => Booking::when($request->filled('start_date'), function($q) use ($request) {
                $q->whereHas('schedule', function($sq) use ($request) {
                    $sq->whereDate('departure_date', '>=', $request->start_date);
                });
            })
            ->when($request->filled('end_date'), function($q) use ($request) {
                $q->whereHas('schedule', function($sq) use ($request) {
                    $sq->whereDate('departure_date', '<=', $request->end_date);
                });
            })
            ->when($request->filled('office'), function($q) use ($request) {
                $q->where('office', $request->office);
            })->withCount('details')->get()->sum('details_count'),
            'total_income' => $totalIncome,
            'total_expenses' => $totalExpenses,
            'net_income' => $netIncome,
        ];
        
        $routes = \App\Models\Route::with(['originBranch', 'destinationBranch'])->get();
        $buses = \App\Models\Bus::all();
        
        return view('admin.reports.index', compact('reports', 'summary', 'routes', 'buses'));
    }

    public function showBooking($id)
    {
        $booking = Booking::with(['schedule.route.originBranch', 'schedule.route.destinationBranch', 'schedule.bus', 'details'])
            ->findOrFail($id);
        
        return view('admin.reports.booking-detail', compact('booking'));
    }

    public function showCashFlow($id)
    {
        $cashFlow = \App\Models\CashFlow::with(['schedule.route.originBranch', 'schedule.route.destinationBranch', 'schedule.bus'])
            ->findOrFail($id);
        
        return view('admin.reports.cashflow-detail', compact('cashFlow'));
    }

    public function dailyBookkeeping(Request $request)
    {
        $date = $request->get('date', now()->format('Y-m-d'));
        
        // Get bookings by office and purchase type
        $bookings = Booking::with(['schedule.route.originBranch', 'schedule.route.destinationBranch'])
            ->whereHas('schedule', function($q) use ($date) {
                $q->whereDate('departure_date', $date);
            })
            ->where('payment_status', 'paid')
            ->get()
            ->groupBy(['office', 'purchase_type']);
        
        // Get cash flows by office
        $cashFlows = \App\Models\CashFlow::whereDate('created_at', $date)
            ->where('type', 'expense')
            ->get()
            ->groupBy('office');
        
        // Calculate summary by office
        $summary = [];
        foreach(['bima', 'mataram'] as $office) {
            $officeBookings = $bookings->get($office, collect());
            $officeCashFlows = $cashFlows->get($office, collect());
            
            $summary[$office] = [
                'kantor_income' => $officeBookings->get('kantor', collect())->sum('total_amount'),
                'agent_income' => $officeBookings->get('agent', collect())->sum('total_amount'),
                'total_income' => $officeBookings->flatten()->sum('total_amount'),
                'expenses' => $officeCashFlows->sum('amount'),
                'net_income' => $officeBookings->flatten()->sum('total_amount') - $officeCashFlows->sum('amount'),
                'bookings' => $officeBookings,
                'cash_flows' => $officeCashFlows
            ];
        }
        
        return view('admin.reports.daily-bookkeeping', compact('summary', 'date'));
    }
}