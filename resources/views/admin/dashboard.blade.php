@extends('layouts.app')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-500 text-white">
                <i class="fas fa-map-marker-alt text-xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-semibold text-gray-700">Cabang</h3>
                <p class="text-2xl font-bold text-blue-600">{{ $stats['branches'] ?? 0 }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-500 text-white">
                <i class="fas fa-bus text-xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-semibold text-gray-700">Bus</h3>
                <p class="text-2xl font-bold text-green-600">{{ $stats['buses'] ?? 0 }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-yellow-500 text-white">
                <i class="fas fa-route text-xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-semibold text-gray-700">Rute</h3>
                <p class="text-2xl font-bold text-yellow-600">{{ $stats['routes'] ?? 0 }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-purple-500 text-white">
                <i class="fas fa-users text-xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-semibold text-gray-700">Pengguna</h3>
                <p class="text-2xl font-bold text-purple-600">{{ $stats['users'] ?? 0 }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Statistik Penjualan -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-indigo-500 text-white">
                <i class="fas fa-calendar-day text-xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-sm font-semibold text-gray-700">Booking Hari Ini</h3>
                <p class="text-2xl font-bold text-indigo-600">{{ $stats['today_bookings'] ?? 0 }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-emerald-500 text-white">
                <i class="fas fa-money-bill-wave text-xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-sm font-semibold text-gray-700">Pendapatan Hari Ini</h3>
                <p class="text-lg font-bold text-emerald-600">Rp {{ number_format($stats['today_income'] ?? 0) }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-orange-500 text-white">
                <i class="fas fa-calendar-alt text-xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-sm font-semibold text-gray-700">Booking Bulan Ini</h3>
                <p class="text-2xl font-bold text-orange-600">{{ $stats['month_bookings'] ?? 0 }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-teal-500 text-white">
                <i class="fas fa-chart-line text-xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-sm font-semibold text-gray-700">Pendapatan Bulan Ini</h3>
                <p class="text-lg font-bold text-teal-600">Rp {{ number_format($stats['month_income'] ?? 0) }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-purple-500 text-white">
                <i class="fas fa-calendar text-xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-sm font-semibold text-gray-700">Booking Tahun Ini</h3>
                <p class="text-2xl font-bold text-purple-600">{{ $stats['year_bookings'] ?? 0 }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-pink-500 text-white">
                <i class="fas fa-chart-bar text-xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-sm font-semibold text-gray-700">Pendapatan Tahun Ini</h3>
                <p class="text-lg font-bold text-pink-600">Rp {{ number_format($stats['year_income'] ?? 0) }}</p>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b">
            <h3 class="text-lg font-semibold">Menu Admin</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-2 gap-4">
                <a href="{{ route('admin.branches.index') }}" class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
                    <i class="fas fa-map-marker-alt text-blue-500 text-xl mr-3"></i>
                    <span class="font-medium">Kelola Cabang</span>
                </a>
                <a href="{{ route('admin.buses.index') }}" class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition">
                    <i class="fas fa-bus text-green-500 text-xl mr-3"></i>
                    <span class="font-medium">Kelola Bus</span>
                </a>
                <a href="{{ route('admin.routes.index') }}" class="flex items-center p-4 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition">
                    <i class="fas fa-route text-yellow-500 text-xl mr-3"></i>
                    <span class="font-medium">Kelola Rute</span>
                </a>
                <a href="{{ route('admin.users.index') }}" class="flex items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition">
                    <i class="fas fa-users text-purple-500 text-xl mr-3"></i>
                    <span class="font-medium">Kelola User</span>
                </a>
                <a href="{{ route('admin.passengers.index') }}" class="flex items-center p-4 bg-pink-50 rounded-lg hover:bg-pink-100 transition">
                    <i class="fas fa-user-friends text-pink-500 text-xl mr-3"></i>
                    <span class="font-medium">Master Penumpang</span>
                </a>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b">
            <h3 class="text-lg font-semibold">Laporan & Kas</h3>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                <a href="{{ route('admin.reports.index') }}" class="flex items-center p-4 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition">
                    <i class="fas fa-chart-bar text-indigo-500 text-xl mr-3"></i>
                    <span class="font-medium">Laporan Penjualan</span>
                </a>
                <a href="{{ route('admin.cash-flows.index') }}" class="flex items-center p-4 bg-red-50 rounded-lg hover:bg-red-100 transition">
                    <i class="fas fa-money-bill-wave text-red-500 text-xl mr-3"></i>
                    <span class="font-medium">Kelola Kas</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Monthly Report Chart -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b">
            <h3 class="text-lg font-semibold">Laporan Bulanan {{ date('Y') }}</h3>
        </div>
        <div class="p-6">
            <div class="space-y-3">
                @foreach($monthlyReport as $month)
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                        <div>
                            <span class="font-medium text-gray-800">{{ $month['month'] }}</span>
                            <p class="text-sm text-gray-600">{{ $month['bookings'] }} booking</p>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-green-600">Rp {{ number_format($month['income']) }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Recent Bookings -->
<div class="mt-8 bg-white rounded-lg shadow">
    <div class="p-6 border-b">
        <h3 class="text-lg font-semibold">Pemesanan Terbaru</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode Booking</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rute</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bus</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($recent_bookings as $booking)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ $booking->booking_code }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $booking->schedule->route->originBranch->name }} → {{ $booking->schedule->route->destinationBranch->name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $booking->schedule->bus->name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $booking->type === 'passenger' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ $booking->type === 'passenger' ? 'Penumpang' : 'Barang' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        Rp {{ number_format($booking->total_amount) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $booking->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $booking->payment_status === 'paid' ? 'Lunas' : 'Pending' }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">Belum ada pemesanan</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection