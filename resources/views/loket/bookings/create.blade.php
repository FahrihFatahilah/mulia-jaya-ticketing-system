@extends('layouts.app')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Pemesanan Baru</h2>
    <a href="{{ route('loket.dashboard') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center">
        <i class="fas fa-arrow-left mr-2"></i>
        Kembali
    </a>
</div>

<div class="bg-white rounded-lg shadow p-6">
    <form id="booking-form" action="{{ route('loket.bookings.store') }}" method="POST">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="route_id" class="block text-sm font-medium text-gray-700 mb-2">Pilih Rute</label>
                <select name="route_id" id="route_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="">Pilih Rute</option>
                    @foreach($routes as $route)
                        <option value="{{ $route->id }}" data-price="{{ $route->price }}">
                            {{ $route->originBranch->name }} → {{ $route->destinationBranch->name }} 
                            
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="departure_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Keberangkatan</label>
                <input type="date" name="departure_date" id="departure_date" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                       min="{{ date('Y-m-d') }}" required>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
            <div>
                <label for="bus_id" class="block text-sm font-medium text-gray-700 mb-2">Pilih Bus</label>
                <select name="bus_id" id="bus_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="">Pilih Bus</option>
                    @foreach(\App\Models\Bus::where('is_active', true)->get() as $bus)
                        <option value="{{ $bus->id }}" data-time="{{ $bus->departure_time->format('H:i') }}" data-seats="{{ $bus->seat_count }}">
                            {{ $bus->name }} ({{ $bus->code }}) - {{ $bus->departure_time->format('H:i') }}
                        </option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-500 mt-1">Setiap bus memiliki jam keberangkatan tetap</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Info Bus</label>
                <div id="bus-info" class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-600 text-sm">
                    <div id="departure-time-display">Jam: Pilih bus terlebih dahulu</div>
                    <div id="seat-info">Kursi: -</div>
                    <div id="available-seats">Tersedia: -</div>
                </div>
            </div>
        </div>

        <div class="mt-6">
            <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Tipe Pemesanan</label>
            <select name="type" id="type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                <option value="">Pilih Tipe</option>
                <option value="passenger">Penumpang</option>
                <option value="cargo">Barang</option>
            </select>
        </div>

        <div class="mt-6">
            <label for="purchase_type" class="block text-sm font-medium text-gray-700 mb-2">Tipe Pembelian</label>
            <select name="purchase_type" id="purchase_type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                <option value="kantor">Kantor</option>
                <option value="agent">Agent</option>
            </select>
        </div>

        <div id="agent-field" class="mt-6 hidden">
            <label for="agent_code" class="block text-sm font-medium text-gray-700 mb-2">Kode Agent</label>
            <select name="agent_code" id="agent_code" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Pilih Agent</option>
                <option value="MN">MN</option>
                <option value="IR">IR</option>
                <option value="WY">WY</option>
                <option value="KTR">KTR</option>
            </select>
        </div>

        <div class="mt-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Kantor Pembelian</label>
            <div class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-600">
                {{ auth()->user()->office ? ucfirst(auth()->user()->office) : 'Bima' }}
            </div>
        </div>

        <!-- Passenger Form -->
        <div id="passenger-form" class="mt-6 hidden">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Detail Penumpang</h3>
            <div class="bg-blue-50 p-4 rounded-lg mb-4">
                <p class="text-sm text-blue-700">
                    <i class="fas fa-info-circle mr-2"></i>
                    Untuk pemesanan penumpang, Anda akan diarahkan ke halaman pemilihan kursi setelah mengisi form ini.
                </p>
            </div>
        </div>

        <!-- Cargo Form -->
        <div id="cargo-form" class="mt-6 hidden">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Detail Barang</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="sender_name" class="block text-sm font-medium text-gray-700 mb-2">Nama Pengirim</label>
                    <input type="text" name="sender_name" id="sender_name" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Nama pengirim">
                </div>

                <div>
                    <label for="receiver_name" class="block text-sm font-medium text-gray-700 mb-2">Nama Penerima</label>
                    <input type="text" name="receiver_name" id="receiver_name" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Nama penerima">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                <div>
                    <label for="cargo_type" class="block text-sm font-medium text-gray-700 mb-2">Jenis Barang</label>
                    <input type="text" name="cargo_type" id="cargo_type" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Contoh: Elektronik, Pakaian, dll">
                </div>

                <div>
                    <label for="weight" class="block text-sm font-medium text-gray-700 mb-2">Berat (kg)</label>
                    <input type="number" name="weight" id="weight" step="0.1" min="0.1"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="0.0">
                </div>
            </div>

            <div class="mt-4">
                <label for="cargo_price" class="block text-sm font-medium text-gray-700 mb-2">Biaya Pengiriman</label>
                <input type="number" name="cargo_price" id="cargo_price" min="0"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="0">
            </div>
        </div>

        <div class="mt-6">
            <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">Metode Pembayaran</label>
            <select name="payment_method" id="payment_method" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                <option value="">Pilih Metode Pembayaran</option>
                <option value="cash">Tunai</option>
                <option value="non_cash">Non Tunai</option>
            </select>
        </div>

        <div class="mt-6">
            <label for="ticket_price" class="block text-sm font-medium text-gray-700 mb-2">Harga Tiket/Barang</label>
            <div class="relative">
                <span class="absolute left-3 top-2 text-gray-500">Rp</span>
                <input type="text" name="ticket_price" id="ticket_price"
                       class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="0" required>
            </div>
        </div>

        <div class="mt-6">
            <label for="payment_description" class="block text-sm font-medium text-gray-700 mb-2">Keterangan Pembayaran</label>
            <textarea name="payment_description" id="payment_description" rows="2" 
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                      placeholder="Keterangan tambahan untuk pembayaran"></textarea>
        </div>

        <div class="mt-8 flex justify-end space-x-4">
            <a href="{{ route('loket.dashboard') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                Batal
            </a>
            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                <span id="submit-text">Lanjutkan</span>
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('type');
    const passengerForm = document.getElementById('passenger-form');
    const cargoForm = document.getElementById('cargo-form');
    const submitText = document.getElementById('submit-text');
    const bookingForm = document.getElementById('booking-form');
    const busSelect = document.getElementById('bus_id');
    const departureTimeDisplay = document.getElementById('departure-time-display');

    // Format rupiah input
    document.getElementById('ticket_price').addEventListener('input', function(e) {
        let value = e.target.value.replace(/[^\d]/g, '');
        if (value) {
            e.target.value = new Intl.NumberFormat('id-ID').format(parseInt(value));
        }
    });

    // Handle purchase type selection
    document.getElementById('purchase_type').addEventListener('change', function() {
        const agentField = document.getElementById('agent-field');
        if (this.value === 'agent') {
            agentField.classList.remove('hidden');
            document.getElementById('agent_code').required = true;
        } else {
            agentField.classList.add('hidden');
            document.getElementById('agent_code').required = false;
        }
    });

    // Handle bus selection to show departure time and seat info
    busSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const routeSelect = document.getElementById('route_id');
        const dateSelect = document.getElementById('departure_date');
        
        if (selectedOption.value && routeSelect.value && dateSelect.value) {
            const time = selectedOption.dataset.time;
            const seats = selectedOption.dataset.seats;
            
            document.getElementById('departure-time-display').textContent = 'Jam: ' + time;
            document.getElementById('seat-info').textContent = 'Kursi: ' + seats + ' seat';
            
            // Check available seats via AJAX
            fetch(`/loket/check-seats?route_id=${routeSelect.value}&bus_id=${selectedOption.value}&departure_date=${dateSelect.value}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('available-seats').textContent = 'Tersedia: ' + data.available + '/' + seats;
                })
                .catch(() => {
                    document.getElementById('available-seats').textContent = 'Tersedia: ' + seats + '/' + seats;
                });
        } else {
            document.getElementById('departure-time-display').textContent = 'Jam: Pilih bus terlebih dahulu';
            document.getElementById('seat-info').textContent = 'Kursi: -';
            document.getElementById('available-seats').textContent = 'Tersedia: -';
        }
    });
    
    // Also check when route or date changes
    document.getElementById('route_id').addEventListener('change', function() {
        if (busSelect.value) busSelect.dispatchEvent(new Event('change'));
    });
    
    document.getElementById('departure_date').addEventListener('change', function() {
        if (busSelect.value) busSelect.dispatchEvent(new Event('change'));
    });

    typeSelect.addEventListener('change', function() {
        if (this.value === 'passenger') {
            passengerForm.classList.remove('hidden');
            cargoForm.classList.add('hidden');
            submitText.textContent = 'Pilih Kursi';
        } else if (this.value === 'cargo') {
            passengerForm.classList.add('hidden');
            cargoForm.classList.remove('hidden');
            submitText.textContent = 'Buat Pemesanan';
        } else {
            passengerForm.classList.add('hidden');
            cargoForm.classList.add('hidden');
            submitText.textContent = 'Lanjutkan';
        }
    });

    bookingForm.addEventListener('submit', function(e) {
        const type = typeSelect.value;
        
        if (type === 'passenger') {
            e.preventDefault();
            
            // Create schedule first, then redirect to seat selection
            const formData = new FormData(this);
            const routeId = formData.get('route_id');
            const busId = formData.get('bus_id');
            const departureDate = formData.get('departure_date');
            
            // Create schedule via form submission to get the schedule ID
            const scheduleForm = document.createElement('form');
            scheduleForm.method = 'POST';
            scheduleForm.action = '{{ route("loket.bookings.create-schedule") }}';
            scheduleForm.style.display = 'none';
            
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';
            scheduleForm.appendChild(csrfInput);
            
            const routeInput = document.createElement('input');
            routeInput.type = 'hidden';
            routeInput.name = 'route_id';
            routeInput.value = routeId;
            scheduleForm.appendChild(routeInput);
            
            const busInput = document.createElement('input');
            busInput.type = 'hidden';
            busInput.name = 'bus_id';
            busInput.value = busId;
            scheduleForm.appendChild(busInput);
            
            const dateInput = document.createElement('input');
            dateInput.type = 'hidden';
            dateInput.name = 'departure_date';
            dateInput.value = departureDate;
            scheduleForm.appendChild(dateInput);
            
            document.body.appendChild(scheduleForm);
            scheduleForm.submit();
        } else if (type === 'cargo') {
            // Add cargo details to form data
            const cargoDetails = {
                sender_name: document.getElementById('sender_name').value,
                receiver_name: document.getElementById('receiver_name').value,
                cargo_type: document.getElementById('cargo_type').value,
                weight: document.getElementById('weight').value,
                price: document.getElementById('ticket_price').value.replace(/\./g, '')
            };
            
            // Add hidden inputs for cargo details
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'details[0][sender_name]';
            hiddenInput.value = cargoDetails.sender_name;
            this.appendChild(hiddenInput);
            
            const hiddenInput2 = document.createElement('input');
            hiddenInput2.type = 'hidden';
            hiddenInput2.name = 'details[0][receiver_name]';
            hiddenInput2.value = cargoDetails.receiver_name;
            this.appendChild(hiddenInput2);
            
            const hiddenInput3 = document.createElement('input');
            hiddenInput3.type = 'hidden';
            hiddenInput3.name = 'details[0][cargo_type]';
            hiddenInput3.value = cargoDetails.cargo_type;
            this.appendChild(hiddenInput3);
            
            const hiddenInput4 = document.createElement('input');
            hiddenInput4.type = 'hidden';
            hiddenInput4.name = 'details[0][weight]';
            hiddenInput4.value = cargoDetails.weight;
            this.appendChild(hiddenInput4);
            
            const hiddenInput5 = document.createElement('input');
            hiddenInput5.type = 'hidden';
            hiddenInput5.name = 'details[0][price]';
            hiddenInput5.value = document.getElementById('ticket_price').value.replace(/\./g, '');
            this.appendChild(hiddenInput5);
        }
    });
});
</script>
@endsection