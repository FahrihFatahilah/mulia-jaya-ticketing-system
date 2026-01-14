@extends('layouts.app')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Kelola Bookings</h2>
</div>

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

<div class="card bg-white rounded-lg shadow overflow-hidden">
    <table class="table min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rute</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipe</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($bookings as $booking)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $booking->booking_code }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                    {{ $booking->schedule->route->originBranch->name }} → {{ $booking->schedule->route->destinationBranch->name }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $booking->schedule->departure_date->format('d/m/Y') }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="badge px-2 py-1 text-xs rounded-full {{ $booking->type === 'passenger' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ $booking->type === 'passenger' ? 'Penumpang' : 'Barang' }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="badge px-2 py-1 text-xs rounded-full {{ $booking->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $booking->payment_status === 'paid' ? 'Lunas' : 'Pending' }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                    <a href="{{ route('admin.bookings.edit', $booking) }}" class="btn text-blue-600 hover:text-blue-900">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('admin.bookings.destroy', $booking) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus booking ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn text-red-600 hover:text-red-900">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-6 py-4 text-center text-gray-500">Tidak ada data booking</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $bookings->links() }}
</div>
@endsection
