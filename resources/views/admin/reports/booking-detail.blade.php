@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow">
        <div class="p-4 border-b">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold">Detail Booking: {{ $booking->booking_code }}</h2>
                <a href="{{ route('admin.reports.index') }}" class="text-blue-600 hover:text-blue-800">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </div>
        
        <div class="p-4">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
                <div>
                    <h3 class="text-lg font-medium mb-3">Informasi Booking</h3>
                    <div class="space-y-2 text-sm">
                        <p><span class="font-medium">Kode:</span> {{ $booking->booking_code }}</p>
                        <p><span class="font-medium">Tipe:</span> 
                            <span class="px-2 py-1 text-xs rounded-full {{ $booking->type === 'passenger' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $booking->type === 'passenger' ? 'Penumpang' : 'Barang' }}
                            </span>
                        </p>
                        <p><span class="font-medium">Kantor:</span> {{ ucfirst($booking->office) }}</p>
                        <p><span class="font-medium">Total:</span> Rp {{ number_format($booking->total_amount) }}</p>
                        <p><span class="font-medium">Status:</span> 
                            <span class="px-2 py-1 text-xs rounded-full {{ $booking->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $booking->payment_status === 'paid' ? 'Lunas' : 'Pending' }}
                            </span>
                        </p>
                        <p><span class="font-medium">Tanggal:</span> {{ $booking->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
                
                <div>
                    <h3 class="text-lg font-medium mb-3">Informasi Perjalanan</h3>
                    <div class="space-y-2 text-sm">
                        <p><span class="font-medium">Rute:</span> {{ $booking->schedule->route->originBranch->name }} → {{ $booking->schedule->route->destinationBranch->name }}</p>
                        <p><span class="font-medium">Bus:</span> {{ $booking->schedule->bus->name }}</p>
                        <p><span class="font-medium">Tanggal Keberangkatan:</span> {{ $booking->schedule->departure_date->format('d/m/Y') }}</p>
                        <p><span class="font-medium">Jam Keberangkatan:</span> {{ $booking->schedule->bus->departure_time->format('H:i') }}</p>
                    </div>
                </div>
            </div>
            
            <div>
                <h3 class="text-lg font-medium mb-3">Detail {{ $booking->type === 'passenger' ? 'Penumpang' : 'Barang' }}</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                @if($booking->type === 'passenger')
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Telepon</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Kursi</th>
                                @else
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Pengirim</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Penerima</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Jenis Barang</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Berat</th>
                                @endif
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Harga</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($booking->details as $detail)
                            <tr>
                                @if($booking->type === 'passenger')
                                    <td class="px-3 py-2 text-sm text-gray-900">{{ $detail->passenger_name }}</td>
                                    <td class="px-3 py-2 text-sm text-gray-500">{{ $detail->passenger_phone }}</td>
                                    <td class="px-3 py-2 text-sm text-gray-500">{{ $detail->seat_number }}</td>
                                @else
                                    <td class="px-3 py-2 text-sm text-gray-900">{{ $detail->sender_name }}</td>
                                    <td class="px-3 py-2 text-sm text-gray-500">{{ $detail->receiver_name }}</td>
                                    <td class="px-3 py-2 text-sm text-gray-500">{{ $detail->cargo_type }}</td>
                                    <td class="px-3 py-2 text-sm text-gray-500">{{ $detail->weight }} kg</td>
                                @endif
                                <td class="px-3 py-2 text-sm text-gray-500">Rp {{ number_format($detail->price) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection