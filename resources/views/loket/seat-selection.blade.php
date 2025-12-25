@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Pilih Kursi</h2>
            <p class="text-gray-600">{{ $schedule->route->route_name }} - {{ $schedule->bus->name }}</p>
            <p class="text-sm text-gray-500">{{ $schedule->departure_date->format('d/m/Y') }} - {{ $schedule->bus->departure_time->format('H:i') }}</p>
        </div>
        <a href="{{ route('loket.bookings.create') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="mb-6">
            <div class="flex items-center justify-center space-x-8 text-sm">
                <div class="flex items-center">
                    <div class="w-6 h-6 bg-green-500 rounded mr-2"></div>
                    <span>Tersedia</span>
                </div>
                <div class="flex items-center">
                    <div class="w-6 h-6 bg-red-500 rounded mr-2"></div>
                    <span>Terisi</span>
                </div>
                <div class="flex items-center">
                    <div class="w-6 h-6 bg-blue-500 rounded mr-2"></div>
                    <span>Dipilih</span>
                </div>
            </div>
        </div>

        <!-- Bus Layout -->
        <div class="max-w-md mx-auto bg-gray-100 rounded-lg p-4">
            <!-- Driver Area -->
            <div class="text-center mb-4 p-2 bg-gray-300 rounded">
                <i class="fas fa-steering-wheel text-gray-600"></i>
                <span class="text-sm text-gray-600 ml-2">Supir</span>
            </div>

            <!-- Seats Layout (32 seats, 2-2 configuration) -->
            <div class="space-y-2">
                @for($row = 1; $row <= 8; $row++)
                    <div class="flex justify-between items-center">
                        <!-- Left side seats -->
                        <div class="flex space-x-1">
                            @for($leftSeat = ($row - 1) * 4 + 1; $leftSeat <= ($row - 1) * 4 + 2; $leftSeat++)
                                @php
                                    $isOccupied = in_array($leftSeat, $occupiedSeats);
                                @endphp
                                <button type="button" 
                                        class="seat-btn w-8 h-8 rounded text-xs font-semibold transition-colors
                                               {{ $isOccupied ? 'bg-red-500 text-white cursor-not-allowed' : 'bg-green-500 text-white hover:bg-blue-500' }}"
                                        data-seat="{{ $leftSeat }}"
                                        {{ $isOccupied ? 'disabled' : '' }}>
                                    {{ $leftSeat }}
                                </button>
                            @endfor
                        </div>

                        <!-- Aisle -->
                        <div class="w-4 text-center text-xs text-gray-400">{{ $row }}</div>

                        <!-- Right side seats -->
                        <div class="flex space-x-1">
                            @for($rightSeat = ($row - 1) * 4 + 3; $rightSeat <= $row * 4; $rightSeat++)
                                @php
                                    $isOccupied = in_array($rightSeat, $occupiedSeats);
                                @endphp
                                <button type="button" 
                                        class="seat-btn w-8 h-8 rounded text-xs font-semibold transition-colors
                                               {{ $isOccupied ? 'bg-red-500 text-white cursor-not-allowed' : 'bg-green-500 text-white hover:bg-blue-500' }}"
                                        data-seat="{{ $rightSeat }}"
                                        {{ $isOccupied ? 'disabled' : '' }}>
                                    {{ $rightSeat }}
                                </button>
                            @endfor
                        </div>
                    </div>
                @endfor
            </div>
        </div>

        <!-- Booking Form -->
        <div class="mt-8">
            <form id="booking-form" action="{{ route('loket.bookings.store') }}" method="POST">
                @csrf
                <input type="hidden" name="route_id" value="{{ $schedule->route_id }}">
                <input type="hidden" name="bus_id" value="{{ $schedule->bus_id }}">
                <input type="hidden" name="departure_date" value="{{ $schedule->departure_date->format('Y-m-d') }}">
                <input type="hidden" name="type" value="passenger">
                <input type="hidden" name="ticket_price" value="{{ session('ticket_price', 0) }}">
                <input type="hidden" name="purchase_type" value="{{ session('purchase_type') }}">
                <input type="hidden" name="agent_code" value="{{ session('agent_code') }}">
                <input type="hidden" name="payment_method" value="{{ session('payment_method') }}">
                <input type="hidden" name="payment_description" value="{{ session('payment_description') }}">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kursi Dipilih</label>
                        <div id="selected-seats" class="min-h-[40px] p-3 border border-gray-300 rounded-md bg-gray-50">
                            <span class="text-gray-500">Belum ada kursi dipilih</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Total Harga</label>
                        <div class="p-3 border border-gray-300 rounded-md bg-gray-50">
                            <span id="total-price" class="font-semibold">Rp 0</span>
                        </div>
                    </div>
                </div>

                <div id="passenger-details" class="mt-6 space-y-4">
                    <!-- Passenger details will be added here dynamically -->
                </div>

                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Metode Pembayaran</label>
                    <div class="p-3 border border-gray-300 rounded-md bg-gray-50">
                        <span class="font-medium">{{ session('payment_method') == 'cash' ? 'Tunai' : 'Non Tunai' }}</span>
                    </div>
                </div>

                <div class="mt-8 flex justify-end space-x-4">
                    <a href="{{ route('loket.bookings.create') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Batal
                    </a>
                    <button type="submit" id="submit-btn" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 disabled:opacity-50" disabled>
                        Buat Pemesanan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const seatButtons = document.querySelectorAll('.seat-btn:not([disabled])');
    const selectedSeatsDiv = document.getElementById('selected-seats');
    const totalPriceDiv = document.getElementById('total-price');
    const passengerDetailsDiv = document.getElementById('passenger-details');
    const submitBtn = document.getElementById('submit-btn');
    
    let selectedSeats = [];
    // Remove seatPrice variable since we use manual input
    
    seatButtons.forEach(button => {
        button.addEventListener('click', function() {
            const seatNumber = parseInt(this.dataset.seat);
            
            if (selectedSeats.includes(seatNumber)) {
                // Remove seat
                selectedSeats = selectedSeats.filter(seat => seat !== seatNumber);
                this.classList.remove('bg-blue-500');
                this.classList.add('bg-green-500');
            } else {
                // Add seat
                selectedSeats.push(seatNumber);
                this.classList.remove('bg-green-500');
                this.classList.add('bg-blue-500');
            }
            
            updateDisplay();
        });
    });
    
    function updateDisplay() {
        // Update selected seats display
        if (selectedSeats.length > 0) {
            selectedSeatsDiv.innerHTML = selectedSeats.sort((a, b) => a - b).join(', ');
        } else {
            selectedSeatsDiv.innerHTML = '<span class="text-gray-500">Belum ada kursi dipilih</span>';
        }
        
        // Update total price
        const ticketPrice = {{ session('ticket_price', 0) }};
        const totalPrice = selectedSeats.length * ticketPrice;
        totalPriceDiv.textContent = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(totalPrice);
        
        // Update passenger details form
        updatePassengerDetails();
        
        // Enable/disable submit button
        submitBtn.disabled = selectedSeats.length === 0;
    }
    
    function updatePassengerDetails() {
        passengerDetailsDiv.innerHTML = '';
        
        selectedSeats.sort((a, b) => a - b).forEach((seat, index) => {
            const div = document.createElement('div');
            div.className = 'p-4 border border-gray-200 rounded-md';
            div.innerHTML = `
                <h4 class="font-medium text-gray-800 mb-3">Penumpang Kursi ${seat}</h4>
                <input type="hidden" name="details[${index}][seat_number]" value="${seat}">
                <div>
                    <label for="passenger_name" class="block text-sm font-medium text-gray-700 mb-1">Nama Penumpang</label>
                    <input type="text" name="details[${index}][passenger_name]" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md" 
                           placeholder="Nama lengkap penumpang" required>
                </div>
            `;
            passengerDetailsDiv.appendChild(div);
        });
    }
});
</script>
@endsection