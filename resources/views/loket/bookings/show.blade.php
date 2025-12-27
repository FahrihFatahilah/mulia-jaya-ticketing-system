@extends('layouts.app')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Detail Pemesanan</h2>
    <a href="{{ route('loket.bookings.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center">
        <i class="fas fa-arrow-left mr-2"></i>
        Kembali
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Pemesanan</h3>
        
        <div class="space-y-3">
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
            <div class="flex justify-between">
                <span class="text-gray-600">Tipe:</span>
                <span class="px-2 py-1 text-xs rounded-full {{ $booking->type === 'passenger' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800' }}">
                    {{ $booking->type === 'passenger' ? 'Penumpang' : 'Barang' }}
                </span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Total:</span>
                <span class="font-semibold text-lg">Rp {{ number_format($booking->total_amount) }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Status:</span>
                <span class="px-2 py-1 text-xs rounded-full {{ $booking->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $booking->payment_status === 'paid' ? 'Lunas' : 'Pending' }}
                </span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Deskripsi Pembayaran:</span>
                <span class="font-semibold text-lg">{{ $booking->payment_description != null  ? $booking->payment_description : '-' }}</span>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Detail {{ $booking->type === 'passenger' ? 'Penumpang' : 'Barang' }}</h3>
        
        <div class="space-y-4">
            @foreach($booking->details as $detail)
                <div class="border border-gray-200 rounded-lg p-4">
                    @if($booking->type === 'passenger')
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="font-medium">{{ $detail->passenger_name }}</p>
                                <p class="text-sm text-gray-600">Kursi {{ $detail->seat_number }}</p>
                            </div>
                            <span class="font-semibold">Rp {{ number_format($detail->price) }}</span>
                        </div>
                    @else
                        <div>
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <p class="font-medium">{{ $detail->cargo_type }}</p>
                                    <p class="text-sm text-gray-600">{{ $detail->weight }} kg</p>
                                </div>
                                <span class="font-semibold">Rp {{ number_format($detail->price) }}</span>
                            </div>
                            <div class="text-sm text-gray-600">
                                <p>Pengirim: {{ $detail->sender_name }}</p>
                                <p>Penerima: {{ $detail->receiver_name }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</div>

@if($booking->payment_status === 'paid')
<div class="mt-6 bg-white rounded-lg shadow p-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Preview Tiket ({{ $booking->details->count() }} Tiket)</h3>
    @foreach($booking->details as $detail)
    <div class="border-2 border-black bg-white mb-6" style="width: 800px; font-family: Arial, sans-serif; font-size: 10px;">
        <!-- Header -->
        <div style="display: flex; border-bottom: 2px solid black;">
            <div style="flex: 1; padding: 10px; border-right: 1px solid black;">
                <div style="font-weight: bold;">
                    TIKET PENUMPANG<br>
                    PASSENGER TICKET<br><br>
                    NO. {{ $booking->booking_code }}-{{ $loop->iteration }}
                </div>
            </div>
            <div style="flex: 2; padding: 10px; text-align: center;">
                <div style="width: 40px; height: 40px; background: #dc2626; border-radius: 50%; display: inline-block; margin-bottom: 8px;"></div>
                <div style="font-weight: bold; font-size: 14px; margin-bottom: 4px;">P.O. MULIA JAYA</div>
                <div style="font-size: 9px; line-height: 1.3;">
                    www.muliajayadotcom<br>
                    MELAYANI : REGULER, PATAS, PAKET<br>
                    RUTE : {{ $booking->schedule->route->originBranch->name }} - {{ $booking->schedule->route->destinationBranch->name }}<br><br>
                    LAPOR TIKET : 1 JAM SEBELUM KEBERANGKATAN<br>
                    REPORT TIME : 1 HOUR BEFORE DEPARTURE<br>
                    JAM BERANGKAT : {{ $booking->schedule->bus->departure_time->format('H:i') }}<br>
                    DEPARTURE TIME : {{ $booking->schedule->departure_date->format('d/m/Y') }}<br>
                    NAIK DI : {{ $booking->schedule->route->originBranch->name }}<br>
                    DEPARTING FROM : {{ $booking->schedule->route->originBranch->name }}
                </div>
            </div>
        </div>
        
        <!-- Passenger Info -->
        <div style="padding: 8px; border-bottom: 1px solid black;">
            <div style="margin-bottom: 4px;"><span style="font-weight: bold;">NAMA</span> : {{ $detail->passenger_name ?? '' }}</div>
            <div style="margin-bottom: 4px;"><span style="font-weight: bold;">ALAMAT / TLP</span> : {{ $detail->passenger_phone ?? '' }}</div>
            <div style="margin-bottom: 4px;"><span style="font-weight: bold;">TANGGAL</span> : {{ $booking->schedule->departure_date->format('d/m/Y') }}</div>
        </div>
        
        <!-- Route Info -->
        <div style="display: flex; border-bottom: 2px solid black;">
            <div style="flex: 1; text-align: center; padding: 8px; border-right: 1px solid black;">
                <div style="font-weight: bold;">DARI</div>
                <div style="font-weight: bold;">FROM</div><br>
                {{ $booking->schedule->route->originBranch->name }}
            </div>
            <div style="flex: 1; text-align: center; padding: 8px; border-right: 1px solid black;">
                <div style="font-weight: bold;">TUJUAN</div>
                <div style="font-weight: bold;">DESTINATION</div><br>
                {{ $booking->schedule->route->destinationBranch->name }}
            </div>
            <div style="flex: 1; text-align: center; padding: 8px; border-right: 1px solid black;">
                <div style="font-weight: bold;">NO. KURSI</div>
                <div style="font-weight: bold;">SEAT NUMBER</div><br>
                {{ $detail->seat_number ?? '' }}
            </div>
            <div style="flex: 1; text-align: center; padding: 8px;">
                <div style="font-weight: bold;">NO. BUS</div>
                <div style="font-weight: bold;">BUS NUMBER</div><br>
                {{ $booking->schedule->bus->name }}
            </div>
        </div>
        
        <!-- Price -->
        <div style="padding: 8px; border-bottom: 1px solid black;">
            <div style="font-weight: bold;">HARGA : Rp. {{ number_format($detail->price) }}</div>
        </div>
        
        <!-- Bottom Section -->
        <div style="display: flex; border-bottom: 1px solid black;">
            <div style="flex: 1; padding: 8px; border-right: 1px solid black;">
                <div style="font-weight: bold;">KELAS :</div>
                <div style="font-weight: bold;">CLASS</div><br>
                {{ $booking->schedule->bus->class ?? 'EKONOMI' }}
            </div>
            <div style="flex: 1; padding: 8px;">
                <div style="font-weight: bold;">DIKELUARKAN OLEH:</div>
                <div style="font-weight: bold;">ISSUED BY</div><br>
                @if($booking->purchase_type === 'kantor')
                    {{ $booking->branch->name ?? 'LOKET' }}
                @else
                    {{ 'AGENT - ' . $booking->agent_code  }}
                @endif
            </div>
        </div>
        
        <!-- Footer -->
        <div style="background: #f8f8f8; padding: 8px; text-align: center; color: #dc2626; font-weight: bold;">
            KESELAMATAN DAN KENYAMANAN ANDA<br>
            ADALAH PRIORITAS KAMI
        </div>
    </div>
    @endforeach
</div>
@endif

<div class="mt-6 flex justify-end space-x-4">
    @if($booking->payment_status === 'pending')
        <form action="{{ route('loket.bookings.payment', $booking) }}" method="POST" class="inline">
            @csrf
            <input type="hidden" name="payment_status" value="paid">
            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg"
                    onclick="return confirm('Konfirmasi pembayaran untuk booking ini?')">
                <i class="fas fa-money-bill-wave mr-2"></i>
                Konfirmasi Pembayaran
            </button>
        </form>
    @endif
    
    @if($booking->payment_status === 'paid')
        <a href="{{ route('loket.bookings.ticket', $booking) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
            <i class="fas fa-print mr-2"></i>
            Cetak Tiket
        </a>
    @endif
</div>
@endsection