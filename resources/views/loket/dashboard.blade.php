@extends('layouts.app')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-500 text-white">
                <i class="fas fa-ticket-alt text-xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-semibold text-gray-700">Tiket Hari Ini</h3>
                <p class="text-2xl font-bold text-blue-600">{{ $stats['today_tickets'] ?? 0 }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-500 text-white">
                <i class="fas fa-money-bill-wave text-xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-semibold text-gray-700">Pendapatan Hari Ini</h3>
                <p class="text-2xl font-bold text-green-600">Rp {{ number_format($stats['today_income'] ?? 0) }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-yellow-500 text-white">
                <i class="fas fa-box text-xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-semibold text-gray-700">Barang Hari Ini</h3>
                <p class="text-2xl font-bold text-yellow-600">{{ $stats['today_cargo'] ?? 0 }}</p>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow">
    <div class="p-6 border-b">
        <h3 class="text-lg font-semibold">Menu Loket</h3>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <a href="{{ route('loket.bookings.create') }}" class="flex items-center p-6 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
                <i class="fas fa-plus-circle text-blue-500 text-2xl mr-4"></i>
                <div>
                    <h4 class="font-semibold text-gray-800">Pemesanan Baru</h4>
                    <p class="text-sm text-gray-600">Buat tiket penumpang atau barang</p>
                </div>
            </a>
            
            <a href="{{ route('loket.bookings.index') }}" class="flex items-center p-6 bg-green-50 rounded-lg hover:bg-green-100 transition">
                <i class="fas fa-list text-green-500 text-2xl mr-4"></i>
                <div>
                    <h4 class="font-semibold text-gray-800">Daftar Pemesanan</h4>
                    <p class="text-sm text-gray-600">Lihat semua pemesanan</p>
                </div>
            </a>
            
            <a href="#" class="flex items-center p-6 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition">
                <i class="fas fa-search text-yellow-500 text-2xl mr-4"></i>
                <div>
                    <h4 class="font-semibold text-gray-800">Cari Tiket</h4>
                    <p class="text-sm text-gray-600">Cari berdasarkan kode booking</p>
                </div>
            </a>
        </div>
    </div>
</div>

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
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($recent_bookings ?? [] as $booking)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ $booking->booking_code }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $booking->schedule->route->route_name }}
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
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">Belum ada pemesanan</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection