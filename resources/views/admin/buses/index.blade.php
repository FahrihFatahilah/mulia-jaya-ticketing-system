@extends('layouts.app')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Kelola Bus</h2>
    <a href="{{ route('admin.buses.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center">
        <i class="fas fa-plus mr-2"></i>
        Tambah Bus
    </a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Bus</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kursi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jam Berangkat</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rute</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($buses as $bus)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ $bus->code }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $bus->name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $bus->seat_count }} kursi
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $bus->departure_time->format('H:i') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $bus->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $bus->is_active ? 'Aktif' : 'Tidak Aktif' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $bus->route ? $bus->route->route_name : 'Belum ada rute' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.buses.edit', $bus) }}" class="text-yellow-600 hover:text-yellow-900">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.buses.destroy', $bus) }}" method="POST" class="inline" 
                                  onsubmit="return confirm('Yakin ingin menghapus bus ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">Belum ada data bus</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Kelola Rute -->
<div id="routeModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg max-w-md w-full">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">Kelola Rute Bus</h3>
                    <button onclick="closeRouteModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <form id="routeForm">
                    @csrf
                    <input type="hidden" id="busId" name="bus_id">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Rute:</label>
                        <div id="routeCheckboxes" class="space-y-2 max-h-60 overflow-y-auto">
                            @foreach(\App\Models\Route::with(['originBranch', 'destinationBranch'])->where('is_active', true)->get() as $route)
                            <label class="flex items-center">
                                <input type="checkbox" name="routes[]" value="{{ $route->id }}" class="mr-2">
                                <span class="text-sm">{{ $route->route_name }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="flex justify-end space-x-2">
                        <button type="button" onclick="closeRouteModal()" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function manageRoutes(busId) {
    document.getElementById('busId').value = busId;
    
    // Load current routes for this bus
    fetch(`/admin/buses/${busId}/routes`)
        .then(response => response.json())
        .then(routes => {
            // Uncheck all checkboxes first
            document.querySelectorAll('input[name="routes[]"]').forEach(cb => cb.checked = false);
            // Check the routes assigned to this bus
            routes.forEach(routeId => {
                const checkbox = document.querySelector(`input[name="routes[]"][value="${routeId}"]`);
                if (checkbox) checkbox.checked = true;
            });
        });
    
    document.getElementById('routeModal').classList.remove('hidden');
}

function closeRouteModal() {
    document.getElementById('routeModal').classList.add('hidden');
}

document.getElementById('routeForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const busId = document.getElementById('busId').value;
    
    fetch(`/admin/buses/${busId}/routes`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
});
</script>
@endsection