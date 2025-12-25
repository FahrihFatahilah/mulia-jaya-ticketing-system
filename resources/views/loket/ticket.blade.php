@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Tiket</h2>
        <div class="space-x-2">
            <button onclick="window.print()" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-print mr-2"></i>
                Print
            </button>
            <a href="{{ route('loket.bookings.show', $booking) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-8 print:shadow-none">
        <!-- Header -->
        <div class="text-center border-b-2 border-dashed border-gray-300 pb-6 mb-6">
            <div class="flex items-center justify-center mb-4">
                <i class="fas fa-bus text-4xl text-blue-500 mr-3"></i>
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">MULIA JAYA TRANSPORT</h1>
                    <p class="text-gray-600">Sistem Tiket Bus Antar Cabang</p>
                </div>
            </div>
        </div>

        <!-- Booking Info -->
        <div class="grid grid-cols-2 gap-6 mb-6">
            <div>
                <h3 class="font-semibold text-gray-800 mb-3">Informasi Perjalanan</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Kode Booking:</span>
                        <span class="font-semibold">{{ $booking->booking_code }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Rute:</span>
                        <span>{{ $booking->schedule->route->originBranch->name }} → {{ $booking->schedule->route->destinationBranch->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Bus:</span>
                        <span>{{ $booking->schedule->bus->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tanggal:</span>
                        <span>{{ $booking->schedule->departure_date->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Jam:</span>
                        <span>{{ $booking->schedule->bus->departure_time->format('H:i') }}</span>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="font-semibold text-gray-800 mb-3">Detail Pembayaran</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Metode:</span>
                        <span>{{ $booking->payment_method === 'cash' ? 'Tunai' : 'Non Tunai' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Status:</span>
                        <span class="px-2 py-1 text-xs rounded-full {{ $booking->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $booking->payment_status === 'paid' ? 'Lunas' : 'Pending' }}
                        </span>
                    </div>
                    <div class="flex justify-between font-semibold">
                        <span class="text-gray-600">Total:</span>
                        <span>Rp {{ number_format($booking->total_amount) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Details -->
        <div class="border-t border-gray-200 pt-6">
            <h3 class="font-semibold text-gray-800 mb-4">Detail {{ $booking->type === 'passenger' ? 'Penumpang' : 'Barang' }}</h3>
            
            @if($booking->type === 'passenger')
                <div class="grid grid-cols-1 gap-4">
                    @foreach($booking->details as $detail)
                        <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-medium">{{ $detail->passenger_name }}</p>
                                <p class="text-sm text-gray-600">Kursi {{ $detail->seat_number }}</p>
                            </div>
                            <span class="font-semibold">Rp {{ number_format($detail->price) }}</span>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="space-y-4">
                    @foreach($booking->details as $detail)
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <p class="font-medium">{{ $detail->cargo_type }}</p>
                                    <p class="text-sm text-gray-600">Berat: {{ $detail->weight }} kg</p>
                                </div>
                                <span class="font-semibold">Rp {{ number_format($detail->price) }}</span>
                            </div>
                            <div class="text-sm text-gray-600">
                                <p>Pengirim: {{ $detail->sender_name }}</p>
                                <p>Penerima: {{ $detail->receiver_name }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Footer -->
        <div class="border-t-2 border-dashed border-gray-300 mt-8 pt-6 text-center text-sm text-gray-600">
            <p>Terima kasih telah menggunakan layanan Mulia Jaya Transport</p>
            <p>Simpan tiket ini sebagai bukti perjalanan</p>
            <p class="mt-2">Dicetak pada: {{ now()->format('d/m/Y H:i') }}</p>
        </div>
    </div>
</div>

<style>
@media print {
    body * {
        visibility: hidden;
    }
    .max-w-2xl, .max-w-2xl * {
        visibility: visible;
    }
    .max-w-2xl {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
    .flex.justify-between.items-center.mb-6 {
        display: none !important;
    }
}
</style>
@endsection