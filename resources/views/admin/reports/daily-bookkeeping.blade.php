@extends('layouts.app')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Pembukuan Harian</h2>
    <form method="GET" class="flex items-center space-x-2">
        <input type="date" name="date" value="{{ $date }}" class="px-3 py-2 border border-gray-300 rounded-md">
        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
            <i class="fas fa-search mr-2"></i>Filter
        </button>
    </form>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    @foreach(['bima' => 'Kantor Bima', 'mataram' => 'Kantor Mataram'] as $office => $officeName)
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4">{{ $officeName }}</h3>
        
        <!-- Summary Cards -->
        <div class="grid grid-cols-2 gap-4 mb-6">
            <div class="bg-green-50 p-4 rounded-lg">
                <h4 class="text-sm font-medium text-green-700">Penjualan Kantor</h4>
                <p class="text-lg font-bold text-green-600">Rp {{ number_format($summary[$office]['kantor_income']) }}</p>
            </div>
            <div class="bg-blue-50 p-4 rounded-lg">
                <h4 class="text-sm font-medium text-blue-700">Penjualan Agent</h4>
                <p class="text-lg font-bold text-blue-600">Rp {{ number_format($summary[$office]['agent_income']) }}</p>
            </div>
            <div class="bg-red-50 p-4 rounded-lg">
                <h4 class="text-sm font-medium text-red-700">Pengeluaran</h4>
                <p class="text-lg font-bold text-red-600">Rp {{ number_format($summary[$office]['expenses']) }}</p>
            </div>
            <div class="bg-indigo-50 p-4 rounded-lg">
                <h4 class="text-sm font-medium text-indigo-700">Saldo Bersih</h4>
                <p class="text-lg font-bold {{ $summary[$office]['net_income'] >= 0 ? 'text-indigo-600' : 'text-red-600' }}">
                    Rp {{ number_format($summary[$office]['net_income']) }}
                </p>
            </div>
        </div>

        <!-- Bookings Detail -->
        @if($summary[$office]['bookings']->count() > 0)
        <div class="mb-4">
            <h4 class="font-medium text-gray-700 mb-2">Detail Pemesanan</h4>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-2 text-left">Kode</th>
                            <th class="px-3 py-2 text-left">Tipe</th>
                            <th class="px-3 py-2 text-left">Agent</th>
                            <th class="px-3 py-2 text-left">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($summary[$office]['bookings']->flatten() as $booking)
                        <tr>
                            <td class="px-3 py-2">{{ $booking->booking_code }}</td>
                            <td class="px-3 py-2">
                                <span class="px-2 py-1 text-xs rounded-full {{ $booking->purchase_type === 'kantor' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ $booking->purchase_type === 'kantor' ? 'Kantor' : $booking->agent_code }}
                                </span>
                            </td>
                            <td class="px-3 py-2">{{ $booking->agent_code ?? '-' }}</td>
                            <td class="px-3 py-2 text-green-600">+Rp {{ number_format($booking->total_amount) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- Cash Flows Detail -->
        @if($summary[$office]['cash_flows']->count() > 0)
        <div>
            <h4 class="font-medium text-gray-700 mb-2">Detail Pengeluaran</h4>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-2 text-left">Deskripsi</th>
                            <th class="px-3 py-2 text-left">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($summary[$office]['cash_flows'] as $cashFlow)
                        <tr>
                            <td class="px-3 py-2">{{ $cashFlow->description }}</td>
                            <td class="px-3 py-2 text-red-600">-Rp {{ number_format($cashFlow->amount) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>
    @endforeach
</div>
@endsection