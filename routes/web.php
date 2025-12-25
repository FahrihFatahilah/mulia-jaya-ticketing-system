<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Admin\BusController;
use App\Http\Controllers\Admin\RouteController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\CashFlowController;
use App\Http\Controllers\Loket\BookingController;
use Illuminate\Support\Facades\Route;
use App\Models\Branch;
use App\Models\Bus;
use App\Models\Route as BusRoute;
use App\Models\User;
use App\Models\Booking;

// Auth Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('loket.dashboard');
        }
    }
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        $today = today();
        $currentMonth = $today->month;
        $currentYear = $today->year;
        
        $stats = [
            'branches' => Branch::count(),
            'buses' => Bus::count(),
            'routes' => BusRoute::count(),
            'users' => User::count(),
            'today_bookings' => Booking::whereHas('schedule', function($q) use ($today) {
                $q->whereDate('departure_date', $today);
            })->count(),
            'today_income' => Booking::whereHas('schedule', function($q) use ($today) {
                $q->whereDate('departure_date', $today);
            })->where('payment_status', 'paid')->sum('total_amount'),
            'month_bookings' => Booking::whereHas('schedule', function($q) use ($currentMonth, $currentYear) {
                $q->whereMonth('departure_date', $currentMonth)->whereYear('departure_date', $currentYear);
            })->count(),
            'month_income' => Booking::whereHas('schedule', function($q) use ($currentMonth, $currentYear) {
                $q->whereMonth('departure_date', $currentMonth)->whereYear('departure_date', $currentYear);
            })->where('payment_status', 'paid')->sum('total_amount'),
            'year_bookings' => Booking::whereHas('schedule', function($q) use ($currentYear) {
                $q->whereYear('departure_date', $currentYear);
            })->count(),
            'year_income' => Booking::whereHas('schedule', function($q) use ($currentYear) {
                $q->whereYear('departure_date', $currentYear);
            })->where('payment_status', 'paid')->sum('total_amount'),
        ];
        
        // Monthly report for current year based on departure date
        $monthlyReport = [];
        for ($month = 1; $month <= 12; $month++) {
            $monthlyReport[] = [
                'month' => date('M', mktime(0, 0, 0, $month, 1)),
                'bookings' => Booking::whereHas('schedule', function($q) use ($month, $currentYear) {
                    $q->whereMonth('departure_date', $month)->whereYear('departure_date', $currentYear);
                })->count(),
                'income' => Booking::whereHas('schedule', function($q) use ($month, $currentYear) {
                    $q->whereMonth('departure_date', $month)->whereYear('departure_date', $currentYear);
                })->where('payment_status', 'paid')->sum('total_amount'),
            ];
        }
        
        $recent_bookings = Booking::with(['schedule.route.originBranch', 'schedule.route.destinationBranch', 'schedule.bus'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
            
        return view('admin.dashboard', compact('stats', 'recent_bookings', 'monthlyReport'));
    })->name('dashboard');
    
    Route::resource('branches', BranchController::class);
    Route::resource('buses', BusController::class);
    Route::resource('routes', RouteController::class);
    Route::resource('users', UserController::class);
    Route::resource('cash-flows', CashFlowController::class);
    
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/daily-bookkeeping', [ReportController::class, 'dailyBookkeeping'])->name('reports.daily-bookkeeping');
    Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');
});

// Loket Routes
Route::middleware(['auth'])->prefix('loket')->name('loket.')->group(function () {
    Route::get('/dashboard', function () {
        if (!auth()->user()->isLoket()) {
            return redirect()->route('admin.dashboard');
        }
        $today = today();
        $stats = [
            'today_tickets' => Booking::whereHas('schedule', function($q) use ($today) {
                $q->whereDate('departure_date', $today);
            })->where('type', 'passenger')->count(),
            'today_income' => Booking::whereHas('schedule', function($q) use ($today) {
                $q->whereDate('departure_date', $today);
            })->where('payment_status', 'paid')->sum('total_amount'),
            'today_cargo' => Booking::whereHas('schedule', function($q) use ($today) {
                $q->whereDate('departure_date', $today);
            })->where('type', 'cargo')->count(),
        ];
        $recent_bookings = Booking::with(['schedule.route.originBranch', 'schedule.route.destinationBranch'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        return view('loket.dashboard', compact('stats', 'recent_bookings'));
    })->name('dashboard');
    
    Route::middleware('role:loket')->group(function () {
        Route::resource('bookings', BookingController::class);
        Route::post('/create-schedule', [BookingController::class, 'createSchedule'])->name('bookings.create-schedule');
        Route::get('/check-seats', [BookingController::class, 'checkSeats'])->name('check-seats');
        Route::get('/seat-selection/{schedule}', [BookingController::class, 'seatSelection'])->name('seat-selection');
        Route::post('/bookings/{booking}/payment', [BookingController::class, 'payment'])->name('bookings.payment');
        Route::delete('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
        Route::get('/bookings/{booking}/ticket', [BookingController::class, 'ticket'])->name('bookings.ticket');
    });
});