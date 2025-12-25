@extends('layouts.app')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Laporan Penjualan</h2>
    <div class="flex space-x-2">
        <a href="{{ route('admin.reports.daily-bookkeeping') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center">
            <i class="fas fa-book mr-2"></i>
            Pembukuan Harian
        </a>
        <a href="{{ route('admin.reports.export') }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg flex items-center">
            <i class="fas fa-download mr-2"></i>
            Export
        </a>
    </div>
</div>

<!-- Filter Form -->
<div class="bg-white rounded-lg shadow p-6 mb-6">
    <form method="GET" action="{{ route('admin.reports.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
            <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        
        <div>
            <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Akhir</label>
            <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        
        <div>
            <label for="route_id" class="block text-sm font-medium text-gray-700 mb-2">Rute</label>
            <select name="route_id" id="route_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Semua Rute</option>
                @foreach($routes as $route)
                    <option value="{{ $route->id }}" {{ request('route_id') == $route->id ? 'selected' : '' }}>
                        {{ $route->originBranch->name }} → {{ $route->destinationBranch->name }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <div>
            <label for="bus_id" class="block text-sm font-medium text-gray-700 mb-2">Bus</label>
            <select name="bus_id" id="bus_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Semua Bus</option>
                @foreach($buses as $bus)
                    <option value="{{ $bus->id }}" {{ request('bus_id') == $bus->id ? 'selected' : '' }}>
                        {{ $bus->name }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <div>
            <label for="office" class="block text-sm font-medium text-gray-700 mb-2">Kantor</label>
            <select name="office" id="office" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Semua Kantor</option>
                <option value="bima" {{ request('office') == 'bima' ? 'selected' : '' }}>Bima</option>
                <option value="mataram" {{ request('office') == 'mataram' ? 'selected' : '' }}>Mataram</option>
            </select>
        </div>
        
        <div class="md:col-span-5 flex space-x-2">
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-search mr-2"></i>Filter
            </button>
            <a href="{{ route('admin.reports.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-refresh mr-2"></i>Reset
            </a>
        </div>
    </form>
</div>

<!-- Summary Statistics -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-500 text-white">
                <i class="fas fa-ticket-alt text-xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-sm font-semibold text-gray-700">Total Booking</h3>
                <p class="text-2xl font-bold text-blue-600">{{ $summary['total_bookings'] }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-500 text-white">
                <i class="fas fa-money-bill-wave text-xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-sm font-semibold text-gray-700">Total Pendapatan</h3>
                <p class="text-lg font-bold text-green-600">Rp {{ number_format($summary['total_income'], 0, ',', '.') }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-red-500 text-white">
                <i class="fas fa-minus-circle text-xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-sm font-semibold text-gray-700">Total Pengeluaran</h3>
                <p class="text-lg font-bold text-red-600">Rp {{ number_format($summary['total_expenses'], 0, ',', '.') }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-indigo-500 text-white">
                <i class="fas fa-calculator text-xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-sm font-semibold text-gray-700">Pendapatan Bersih</h3>
                <p class="text-lg font-bold {{ $summary['net_income'] >= 0 ? 'text-indigo-600' : 'text-red-600' }}">Rp {{ number_format($summary['net_income'], 0, ',', '.') }}</p>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode/Deskripsi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rute/Kategori</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kantor</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($reports as $item)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $item->created_at->format('d/m/Y H:i') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ $item->booking_code }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        @if($item->transaction_type === 'booking' && $item->schedule)
                            {{ $item->schedule->route->originBranch->name }} → {{ $item->schedule->route->destinationBranch->name }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <span class="px-2 py-1 text-xs rounded-full {{ $item->office == 'bima' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                            {{ ucfirst($item->office ?? 'bima') }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($item->transaction_type === 'booking')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $item->type === 'passenger' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $item->type === 'passenger' ? 'Penumpang' : 'Barang' }}
                            </span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                Pengeluaran
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm {{ $item->transaction_type === 'booking' ? 'text-green-600' : 'text-red-600' }}">
                        {{ $item->transaction_type === 'booking' ? '+' : '-' }} Rp {{ number_format($item->total_amount) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($item->transaction_type === 'booking')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $item->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $item->payment_status === 'paid' ? 'Lunas' : 'Pending' }}
                            </span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                Selesai
                            </span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">Tidak ada data laporan</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($reports->hasPages())
        <div class="px-6 py-4 border-t">
            {{ $reports->appends(request()->query())->links() }}
        </div>
    @endif
</div>
@endsection