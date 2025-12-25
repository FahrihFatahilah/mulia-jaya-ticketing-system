<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Route;
use App\Models\Branch;
use Illuminate\Http\Request;

class RouteController extends Controller
{
    public function index()
    {
        $routes = Route::with(['originBranch', 'destinationBranch'])
            ->orderBy('id')
            ->get();
        
        return view('admin.routes.index', compact('routes'));
    }

    public function create()
    {
        $branches = Branch::where('is_active', true)
            ->orderBy('name')
            ->get();
            
        return view('admin.routes.create', compact('branches'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'origin_branch_id' => 'required|exists:branches,id',
            'destination_branch_id' => 'required|exists:branches,id|different:origin_branch_id',
            'price' => 'required|numeric|min:0',
        ]);

        Route::create($request->all());

        return redirect()->route('admin.routes.index')
            ->with('success', 'Rute berhasil ditambahkan');
    }

    public function show(Route $route)
    {
        $route->load(['originBranch', 'destinationBranch']);
        
        return view('admin.routes.show', compact('route'));
    }

    public function edit(Route $route)
    {
        $branches = Branch::where('is_active', true)
            ->orderBy('name')
            ->get();
            
        $route->load(['originBranch', 'destinationBranch']);
        
        return view('admin.routes.edit', compact('route', 'branches'));
    }

    public function update(Request $request, Route $route)
    {
        $request->validate([
            'origin_branch_id' => 'required|exists:branches,id',
            'destination_branch_id' => 'required|exists:branches,id|different:origin_branch_id',
            'price' => 'required|numeric|min:0',
        ]);

        $route->update($request->all());

        return redirect()->route('admin.routes.index')
            ->with('success', 'Rute berhasil diperbarui');
    }

    public function destroy(Route $route)
    {
        // Check if route is used in schedules
        if ($route->schedules()->exists()) {
            return redirect()->route('admin.routes.index')
                ->with('error', 'Rute tidak dapat dihapus karena masih digunakan dalam jadwal');
        }
        
        $route->delete();

        return redirect()->route('admin.routes.index')
            ->with('success', 'Rute berhasil dihapus');
    }
}