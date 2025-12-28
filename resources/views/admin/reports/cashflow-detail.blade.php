@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow">
        <div class="p-4 border-b">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold">Detail Pengeluaran: {{ $cashFlow->description }}</h2>
                <a href="{{ route('admin.reports.index') }}" class="text-blue-600 hover:text-blue-800">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </div>
        
        <div class="p-4">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
                <div>
                    <h3 class="text-lg font-medium mb-3">Informasi Pengeluaran</h3>
                    <div class="space-y-2 text-sm">
                        <p><span class="font-medium">Deskripsi:</span> {{ $cashFlow->description }}</p>
                        <p><span class="font-medium">Tipe:</span> 
                            <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">
                                {{ $cashFlow->type === 'expense' ? 'Pengeluaran Operasional' : 'Pengeluaran Kantor' }}
                            </span>
                        </p>
                        <p><span class="font-medium">Kantor:</span> {{ ucfirst($cashFlow->office) }}</p>
                        <p><span class="font-medium">Jumlah:</span> Rp {{ number_format($cashFlow->amount) }}</p>
                        <p><span class="font-medium">Saldo:</span> Rp {{ number_format($cashFlow->balance) }}</p>
                        <p><span class="font-medium">Tanggal:</span> {{ $cashFlow->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
                
                @if($cashFlow->schedule)
                <div>
                    <h3 class="text-lg font-medium mb-3">Informasi Perjalanan</h3>
                    <div class="space-y-2 text-sm">
                        <p><span class="font-medium">Rute:</span> {{ $cashFlow->schedule->route->originBranch->name }} → {{ $cashFlow->schedule->route->destinationBranch->name }}</p>
                        <p><span class="font-medium">Bus:</span> {{ $cashFlow->schedule->bus->name }}</p>
                        <p><span class="font-medium">Tanggal Keberangkatan:</span> {{ $cashFlow->schedule->departure_date->format('d/m/Y') }}</p>
                        <p><span class="font-medium">Jam Keberangkatan:</span> {{ $cashFlow->schedule->bus->departure_time->format('H:i') }}</p>
                    </div>
                </div>
                @else
                <div>
                    <h3 class="text-lg font-medium mb-3">Catatan</h3>
                    <div class="text-sm text-gray-600">
                        <p>Pengeluaran ini tidak terkait dengan perjalanan tertentu.</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection