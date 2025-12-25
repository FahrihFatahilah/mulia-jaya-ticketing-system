@extends('layouts.app')

@section('content')
<div class="text-center py-12">
    <h1 class="text-4xl font-bold text-gray-800 mb-4">Selamat Datang di Sistem Tiket Bus Mulia Jaya</h1>
    <p class="text-xl text-gray-600 mb-8">Pilih menu sesuai dengan peran Anda</p>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg p-8 hover:shadow-xl transition-shadow">
            <div class="text-center">
                <div class="w-20 h-20 bg-blue-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-tachometer-alt text-white text-3xl"></i>
                </div>
                <h3 class="text-2xl font-semibold text-gray-800 mb-4">Admin Panel</h3>
                <p class="text-gray-600 mb-6">Kelola master data, laporan, dan kas berjalan</p>
                <a href="{{ route('admin.dashboard') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg inline-flex items-center">
                    <i class="fas fa-arrow-right mr-2"></i>
                    Masuk Admin
                </a>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-8 hover:shadow-xl transition-shadow">
            <div class="text-center">
                <div class="w-20 h-20 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-ticket-alt text-white text-3xl"></i>
                </div>
                <h3 class="text-2xl font-semibold text-gray-800 mb-4">Loket</h3>
                <p class="text-gray-600 mb-6">Buat pemesanan tiket dan kelola transaksi</p>
                <a href="{{ route('loket.dashboard') }}" class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-lg inline-flex items-center">
                    <i class="fas fa-arrow-right mr-2"></i>
                    Masuk Loket
                </a>
            </div>
        </div>
    </div>

    <div class="mt-12 bg-white rounded-lg shadow p-6 max-w-2xl mx-auto">
        <h4 class="text-lg font-semibold text-gray-800 mb-4">Fitur Sistem</h4>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
            <div class="text-center">
                <i class="fas fa-map-marker-alt text-blue-500 text-xl mb-2"></i>
                <p>Kelola Cabang</p>
            </div>
            <div class="text-center">
                <i class="fas fa-bus text-green-500 text-xl mb-2"></i>
                <p>Kelola Bus</p>
            </div>
            <div class="text-center">
                <i class="fas fa-route text-yellow-500 text-xl mb-2"></i>
                <p>Kelola Rute</p>
            </div>
            <div class="text-center">
                <i class="fas fa-chair text-purple-500 text-xl mb-2"></i>
                <p>Layout 32 Kursi</p>
            </div>
        </div>
    </div>
</div>
@endsection