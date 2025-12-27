@extends('layouts.app')

@section('content')
<div class="text-center py-16">
    <div class="max-w-4xl mx-auto">
        <div class="mb-8">
            <i class="fas fa-bus text-6xl text-blue-500 mb-4"></i>
            <h1 class="text-5xl font-bold text-gray-800 mb-4">PO. Bus Mulia Jaya</h1>
            <p class="text-xl text-gray-600 mb-8">Sistem Pembelian Tiket dan Pengiriman Barang Bus Antar Cabang</p>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6">Fitur Utama Sistem</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="text-center p-4">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-users text-blue-500 text-2xl"></i>
                    </div>
                    <h3 class="font-semibold text-gray-800 mb-2">Multi Role</h3>
                    <p class="text-sm text-gray-600">Admin dan Loket dengan hak akses berbeda</p>
                </div>

                <div class="text-center p-4">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-chair text-green-500 text-2xl"></i>
                    </div>
                    <h3 class="font-semibold text-gray-800 mb-2">Layout 32 Kursi</h3>
                    <p class="text-sm text-gray-600">Visualisasi kursi 2-2 dengan status real-time</p>
                </div>

                <div class="text-center p-4">
                    <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-route text-yellow-500 text-2xl"></i>
                    </div>
                    <h3 class="font-semibold text-gray-800 mb-2">Multi Rute</h3>
                    <p class="text-sm text-gray-600">Kelola rute antar cabang dengan harga berbeda</p>
                </div>

                <div class="text-center p-4">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-clock text-purple-500 text-2xl"></i>
                    </div>
                    <h3 class="font-semibold text-gray-800 mb-2">Jadwal Fleksibel</h3>
                    <p class="text-sm text-gray-600">Setiap bus memiliki jam keberangkatan sendiri</p>
                </div>

                <div class="text-center p-4">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-box text-red-500 text-2xl"></i>
                    </div>
                    <h3 class="font-semibold text-gray-800 mb-2">Cargo & Passenger</h3>
                    <p class="text-sm text-gray-600">Tiket penumpang dan pengiriman barang</p>
                </div>

                <div class="text-center p-4">
                    <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-chart-bar text-indigo-500 text-2xl"></i>
                    </div>
                    <h3 class="font-semibold text-gray-800 mb-2">Laporan & Kas</h3>
                    <p class="text-sm text-gray-600">Laporan lengkap dan kelola kas berjalan</p>
                </div>
            </div>
        </div>

        <div class="flex justify-center space-x-4">
            <a href="{{ route('admin.dashboard') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-8 py-3 rounded-lg text-lg font-semibold flex items-center">
                <i class="fas fa-tachometer-alt mr-2"></i>
                Admin Panel
            </a>
            <a href="{{ route('loket.dashboard') }}" class="bg-green-500 hover:bg-green-600 text-white px-8 py-3 rounded-lg text-lg font-semibold flex items-center">
                <i class="fas fa-ticket-alt mr-2"></i>
                Loket
            </a>
        </div>

        <div class="mt-12 text-sm text-gray-500">
            <p>© 2024 PO. Bus Mulia Jaya. Sistem Internal untuk Admin dan Loket.</p>
        </div>
    </div>
</div>
@endsection