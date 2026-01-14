@extends('layouts.app')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Edit Booking</h2>
    <a href="{{ route('admin.bookings.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
        <i class="fas fa-arrow-left mr-2"></i>Kembali
    </a>
</div>

<div class="bg-white rounded-lg shadow p-6">
    <div class="mb-6 pb-6 border-b">
        <h3 class="text-lg font-semibold mb-4">Informasi Booking</h3>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-600">Kode Booking</p>
                <p class="font-semibold">{{ $booking->booking_code }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Rute</p>
                <p class="font-semibold">{{ $booking->schedule->route->originBranch->name }} → {{ $booking->schedule->route->destinationBranch->name }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Tanggal Keberangkatan</p>
                <p class="font-semibold">{{ $booking->schedule->departure_date->format('d/m/Y') }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Bus</p>
                <p class="font-semibold">{{ $booking->schedule->bus->name }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Tipe</p>
                <p class="font-semibold">{{ $booking->type === 'passenger' ? 'Penumpang' : 'Barang' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Total Amount</p>
                <p class="font-semibold">Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.bookings.update', $booking) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="payment_status" class="block text-sm font-medium text-gray-700 mb-2">Status Pembayaran</label>
                <select name="payment_status" id="payment_status" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                    <option value="pending" {{ $booking->payment_status === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="paid" {{ $booking->payment_status === 'paid' ? 'selected' : '' }}>Lunas</option>
                </select>
            </div>

            <div>
                <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">Metode Pembayaran</label>
                <select name="payment_method" id="payment_method" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                    <option value="cash" {{ $booking->payment_method === 'cash' ? 'selected' : '' }}>Tunai</option>
                    <option value="non_cash" {{ $booking->payment_method === 'non_cash' ? 'selected' : '' }}>Non Tunai</option>
                </select>
            </div>
        </div>

        <div class="mt-6">
            <label for="payment_description" class="block text-sm font-medium text-gray-700 mb-2">Keterangan Pembayaran</label>
            <textarea name="payment_description" id="payment_description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md">{{ $booking->payment_description }}</textarea>
        </div>

        <div class="mt-8 flex justify-end space-x-4">
            <a href="{{ route('admin.bookings.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                Batal
            </a>
            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
