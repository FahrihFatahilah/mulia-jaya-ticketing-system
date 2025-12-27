@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
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

    @foreach($booking->details as $detail)
    <div class="border-2 border-black bg-white mb-6" style="width: 100%; font-family: Arial, sans-serif; font-size: 10px;">
        <!-- Header -->
        <div style="display: flex; border-bottom: 2px solid black; height: 25%;">
            <div style="flex: 1; padding: 8px; border-right: 1px solid black; display: flex; align-items: center;">
                <div style="font-weight: bold; font-size: 10px;">
                    TIKET PENUMPANG<br>
                    PASSENGER TICKET<br><br>
                    NO. {{ $booking->booking_code }}-{{ $loop->iteration }}
                </div>
            </div>
            <div style="flex: 2; padding: 8px; text-align: center; display: flex; flex-direction: column; justify-content: center;">
                <div style="width: 35px; height: 35px; background: #dc2626; border-radius: 50%; display: inline-block; margin-bottom: 6px;"></div>
                <div style="font-weight: bold; font-size: 14px; margin-bottom: 3px;">P.O. MULIA JAYA</div>
                <div style="font-size: 9px; line-height: 1.2;">
                    www.muliajayadotcom<br>
                    MELAYANI : REGULER, PATAS, PAKET<br>
                    RUTE : {{ $booking->schedule->route->originBranch->name }} - {{ $booking->schedule->route->destinationBranch->name }}<br>
                    LAPOR TIKET : 1 JAM SEBELUM KEBERANGKATAN<br>
                    JAM BERANGKAT : {{ $booking->schedule->bus->departure_time->format('H:i') }}<br>
                    NAIK DI : {{ $booking->schedule->route->originBranch->name }}
                </div>
            </div>
        </div>
        
        <!-- Passenger Info -->
        <div style="padding: 8px; border-bottom: 1px solid black; height: 15%;">
            <div style="margin-bottom: 3px;"><span style="font-weight: bold;">NAMA</span> : {{ $detail->passenger_name ?? '' }}</div>
            <div style="margin-bottom: 3px;"><span style="font-weight: bold;">ALAMAT / TLP</span> : {{ $detail->passenger_phone ?? '' }}</div>
            <div style="margin-bottom: 3px;"><span style="font-weight: bold;">TANGGAL</span> : {{ $booking->schedule->departure_date->format('d/m/Y') }}</div>
        </div>
        
        <!-- Route Info -->
        <div style="display: flex; border-bottom: 2px solid black; height: 20%;">
            <div style="flex: 1; text-align: center; padding: 8px; border-right: 1px solid black; display: flex; flex-direction: column; justify-content: center;">
                <div style="font-weight: bold;">DARI</div>
                <div style="font-weight: bold;">FROM</div>
                <div>{{ $booking->schedule->route->originBranch->name }}</div>
            </div>
            <div style="flex: 1; text-align: center; padding: 8px; border-right: 1px solid black; display: flex; flex-direction: column; justify-content: center;">
                <div style="font-weight: bold;">TUJUAN</div>
                <div style="font-weight: bold;">DESTINATION</div>
                <div>{{ $booking->schedule->route->destinationBranch->name }}</div>
            </div>
            <div style="flex: 1; text-align: center; padding: 8px; border-right: 1px solid black; display: flex; flex-direction: column; justify-content: center;">
                <div style="font-weight: bold;">NO. KURSI</div>
                <div style="font-weight: bold;">SEAT NUMBER</div>
                <div>{{ $detail->seat_number ?? '' }}</div>
            </div>
            <div style="flex: 1; text-align: center; padding: 8px; display: flex; flex-direction: column; justify-content: center;">
                <div style="font-weight: bold;">NO. BUS</div>
                <div style="font-weight: bold;">BUS NUMBER</div>
                <div>{{ $booking->schedule->bus->name }}</div>
            </div>
        </div>
        
        <!-- Price -->
        <div style="padding: 8px; border-bottom: 1px solid black; height: 10%; display: flex; align-items: center;">
            <div style="font-weight: bold;">HARGA : Rp. {{ number_format($detail->price) }}</div>
        </div>
        
        <!-- Bottom Section -->
        <div style="display: flex; border-bottom: 1px solid black; height: 15%;">
            <div style="flex: 1; padding: 8px; border-right: 1px solid black; display: flex; flex-direction: column; justify-content: center;">
                <div style="font-weight: bold;">KELAS :</div>
                <div style="font-weight: bold;">CLASS</div>
                <div>{{ $booking->schedule->bus->class ?? 'EKONOMI' }}</div>
            </div>
            <div style="flex: 1; padding: 8px; display: flex; flex-direction: column; justify-content: center;">
                <div style="font-weight: bold;">DIKELUARKAN OLEH:</div>
                <div style="font-weight: bold;">ISSUED BY</div>
                <div>
                    @if($booking->purchase_type === 'kantor')
                        {{ $booking->branch->name ?? 'LOKET' }}
                    @else
                        {{ 'AGENT - ' . $booking->agent_code  }}
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <div style="background: #f8f8f8; padding: 8px; text-align: center; color: #dc2626; font-weight: bold; height: 15%; display: flex; align-items: center; justify-content: center;">
            <div>
                KESELAMATAN DAN KENYAMANAN ANDA<br>
                ADALAH PRIORITAS KAMI
            </div>
        </div>
    </div>
    @endforeach
</div>

<style>
@media print {
    @page {
        size: 148mm 105mm;
        margin: 5mm;
    }
    body * {
        visibility: hidden;
    }
    .max-w-4xl, .max-w-4xl * {
        visibility: visible;
    }
    .max-w-4xl {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
    .flex.justify-between.items-center.mb-6 {
        display: none !important;
    }
    div[style*="width: 100%"] {
        width: 138mm !important;
        height: 95mm !important;
        margin: 0;
        page-break-after: always;
    }
    div[style*="width: 100%"]:last-child {
        page-break-after: avoid;
    }
}
</style>
@endsection