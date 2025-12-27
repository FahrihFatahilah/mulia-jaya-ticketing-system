@extends('layouts.app')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Master Data Penumpang</h2>
</div>

<div class="bg-white rounded-lg shadow">
    <div class="p-6 border-b border-gray-200">
        <form method="GET" class="flex gap-4">
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="Cari nama atau nomor HP..." 
                   class="flex-1 px-3 py-2 border border-gray-300 rounded-md">
            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                Cari
            </button>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nomor HP</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Pembelian</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Terakhir Beli</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($passengers as $passenger)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap font-medium">{{ $passenger->phone }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $passenger->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $passenger->bookingDetails->count() }} tiket</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $passenger->bookingDetails->sortByDesc('created_at')->first()?->created_at?->format('d/m/Y H:i') ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <button onclick="showHistory('{{ $passenger->phone }}')" 
                                    class="text-blue-600 hover:text-blue-900">
                                Lihat Riwayat
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">Tidak ada data penumpang</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-6 py-4 border-t border-gray-200">
        {{ $passengers->links() }}
    </div>
</div>

<!-- Modal Riwayat -->
<div id="historyModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg max-w-4xl w-full max-h-96 overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">Riwayat Pembelian</h3>
                    <button onclick="closeHistory()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div id="historyContent"></div>
            </div>
        </div>
    </div>
</div>

<script>
function showHistory(phone) {
    fetch(`/admin/passengers/${phone}/history`)
        .then(response => response.json())
        .then(data => {
            let content = '<div class="space-y-4">';
            data.forEach(booking => {
                content += `
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <p class="font-medium">${booking.booking_code}</p>
                                <p class="text-sm text-gray-600">${booking.route}</p>
                            </div>
                            <span class="text-sm text-gray-500">${booking.date}</span>
                        </div>
                        <div class="text-sm">
                            <p>Kursi: ${booking.seat_number}</p>
                            <p>Harga: Rp ${booking.price}</p>
                        </div>
                    </div>
                `;
            });
            content += '</div>';
            document.getElementById('historyContent').innerHTML = content;
            document.getElementById('historyModal').classList.remove('hidden');
        });
}

function closeHistory() {
    document.getElementById('historyModal').classList.add('hidden');
}
</script>
@endsection