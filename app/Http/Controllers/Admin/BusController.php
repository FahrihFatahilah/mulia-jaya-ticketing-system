<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bus;
use Illuminate\Http\Request;

class BusController extends Controller
{
    public function index()
    {
        $buses = Bus::with('route.originBranch', 'route.destinationBranch')->orderBy('code')->get();
        
        return view('admin.buses.index', compact('buses'));
    }

    public function create()
    {
        return view('admin.buses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:20|unique:buses',
            'name' => 'required|string|max:255',
            'seat_count' => 'required|integer|min:1|max:50',
            'departure_time' => 'required',
        ]);

        Bus::create($request->all());

        return redirect()->route('admin.buses.index')
            ->with('success', 'Bus berhasil ditambahkan');
    }

    public function show(Bus $bus)
    {
        return view('admin.buses.show', compact('bus'));
    }

    public function edit(Bus $bus)
    {
        return view('admin.buses.edit', compact('bus'));
    }

    public function update(Request $request, Bus $bus)
    {
        $request->validate([
            'code' => 'required|string|max:20|unique:buses,code,' . $bus->id,
            'name' => 'required|string|max:255',
            'seat_count' => 'required|integer|min:1|max:50',
            'departure_time' => 'required',
        ]);

        $bus->update($request->all());

        return redirect()->route('admin.buses.index')
            ->with('success', 'Bus berhasil diperbarui');
    }

    public function destroy(Bus $bus)
    {
        // Check if bus is used in schedules
        if ($bus->schedules()->exists()) {
            return redirect()->route('admin.buses.index')
                ->with('error', 'Bus tidak dapat dihapus karena masih digunakan dalam jadwal');
        }
        
        $bus->delete();

        return redirect()->route('admin.buses.index')
            ->with('success', 'Bus berhasil dihapus');
    }
}