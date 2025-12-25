@extends('layouts.app')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Kelola Kas Berjalan</h2>
    <a href="{{ route('admin.cash-flows.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center">
        <i class="fas fa-plus mr-2"></i>
        Tambah Kas
    </a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rute</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bus</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Saldo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($cashFlows as $cashFlow)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        @if($cashFlow->schedule)
                            {{ $cashFlow->schedule->departure_date->format('d/m/Y') }}
                        @else
                            {{ $cashFlow->created_at->format('d/m/Y') }}
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        @if($cashFlow->schedule)
                            {{ $cashFlow->schedule->route->originBranch->name }} → {{ $cashFlow->schedule->route->destinationBranch->name }}
                        @else
                            <span class="text-gray-400">Pengeluaran Kantor</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        @if($cashFlow->schedule)
                            {{ $cashFlow->schedule->bus->name }}
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $cashFlow->type === 'income' ? 'bg-green-100 text-green-800' : ($cashFlow->type === 'expense' || $cashFlow->type === 'office_expense' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800') }}">
                            {{ $cashFlow->type === 'office_expense' ? 'Office Expense' : ucfirst($cashFlow->type) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        {{ $cashFlow->description }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <span class="{{ $cashFlow->type === 'expense' || $cashFlow->type === 'office_expense' ? 'text-red-600' : 'text-green-600' }}">
                            {{ $cashFlow->type === 'expense' || $cashFlow->type === 'office_expense' ? '-' : '+' }}Rp {{ number_format($cashFlow->amount, 0, ',', '.') }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                        Rp {{ number_format($cashFlow->balance, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.cash-flows.edit', $cashFlow) }}" class="text-yellow-600 hover:text-yellow-900">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.cash-flows.destroy', $cashFlow) }}" method="POST" class="inline" 
                                  onsubmit="return confirm('Yakin ingin menghapus cash flow ini?')">
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
                    <td colspan="8" class="px-6 py-4 text-center text-gray-500">Belum ada data kas</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($cashFlows->hasPages())
        <div class="px-6 py-4 border-t">
            {{ $cashFlows->links() }}
        </div>
    @endif
</div>
@endsection