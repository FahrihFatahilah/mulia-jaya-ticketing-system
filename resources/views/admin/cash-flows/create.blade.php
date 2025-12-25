@extends('layouts.app')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Tambah Kas Berjalan</h2>
    <a href="{{ route('admin.cash-flows.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center">
        <i class="fas fa-arrow-left mr-2"></i>
        Kembali
    </a>
</div>

<div class="bg-white rounded-lg shadow p-6">
    <form action="{{ route('admin.cash-flows.store') }}" method="POST">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="schedule_id" class="block text-sm font-medium text-gray-700 mb-2">Jadwal</label>
                <select name="schedule_id" id="schedule_id" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('schedule_id') border-red-500 @enderror">
                    <option value="">Pilih Jadwal (Kosongkan untuk pengeluaran kantor)</option>
                    @foreach($schedules as $schedule)
                        <option value="{{ $schedule->id }}" {{ old('schedule_id') == $schedule->id ? 'selected' : '' }}>
                            {{ $schedule->route->originBranch->name }} → {{ $schedule->route->destinationBranch->name }} 
                            - {{ $schedule->bus->name }} 
                            ({{ $schedule->departure_date->format('d/m/Y') }})
                        </option>
                    @endforeach
                </select>
                @error('schedule_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Tipe</label>
                <select name="type" id="type" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('type') border-red-500 @enderror" required>
                    <option value="">Pilih Tipe</option>
                    <option value="initial" {{ old('type') == 'initial' ? 'selected' : '' }}>Kas Awal</option>
                    <option value="income" {{ old('type') == 'income' ? 'selected' : '' }}>Pemasukan</option>
                    <option value="expense" {{ old('type') == 'expense' ? 'selected' : '' }}>Pengeluaran</option>
                    <option value="office_expense" {{ old('type') == 'office_expense' ? 'selected' : '' }}>Pengeluaran Kantor</option>
                </select>
                @error('type')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mt-6">
            <label for="office" class="block text-sm font-medium text-gray-700 mb-2">Kantor</label>
            <select name="office" id="office" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('office') border-red-500 @enderror" required>
                <option value="">Pilih Kantor</option>
                <option value="bima" {{ old('office', auth()->user()->office ?? 'bima') == 'bima' ? 'selected' : '' }}>Bima</option>
                <option value="mataram" {{ old('office', auth()->user()->office ?? 'bima') == 'mataram' ? 'selected' : '' }}>Mataram</option>
            </select>
            @error('office')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mt-6">
            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
            <textarea name="description" id="description" rows="3" 
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror"
                      placeholder="Deskripsi kas" required>{{ old('description') }}</textarea>
            @error('description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mt-6">
            <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">Jumlah</label>
            <div class="relative">
                <span class="absolute left-3 top-2 text-gray-500">Rp</span>
                <input type="text" name="amount" id="amount" value="{{ old('amount') }}" 
                       class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('amount') border-red-500 @enderror"
                       placeholder="0" required>
            </div>
            @error('amount')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mt-8 flex justify-end space-x-4">
            <a href="{{ route('admin.cash-flows.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                Batal
            </a>
            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                Simpan
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Format rupiah input
    document.getElementById('amount').addEventListener('input', function(e) {
        let value = e.target.value.replace(/[^\d]/g, '');
        if (value) {
            e.target.value = new Intl.NumberFormat('id-ID').format(parseInt(value));
        }
    });
});
</script>
@endsection