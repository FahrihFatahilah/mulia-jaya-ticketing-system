<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CashFlow;
use App\Models\Schedule;
use Illuminate\Http\Request;

class CashFlowController extends Controller
{
    public function index()
    {
        $cashFlows = CashFlow::with(['schedule.route.originBranch', 'schedule.route.destinationBranch', 'schedule.bus'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('admin.cash-flows.index', compact('cashFlows'));
    }

    public function create()
    {
        $schedules = Schedule::with(['route.originBranch', 'route.destinationBranch', 'bus'])
            ->orderBy('departure_date', 'desc')
            ->get();
            
        return view('admin.cash-flows.create', compact('schedules'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'schedule_id' => 'nullable|exists:schedules,id',
            'type' => 'required|in:initial,income,expense,office_expense',
            'office' => 'required|in:bima,mataram',
            'description' => 'required|string',
            'amount' => 'required|string',
        ]);

        // Parse Indonesian formatted amount
        $amount = (int) str_replace('.', '', $request->amount);

        // Calculate balance
        if ($request->type === 'office_expense') {
            // Get balance from total income minus all office expenses
            $totalIncome = CashFlow::where('type', 'income')->sum('amount');
            $totalOfficeExpense = CashFlow::where('type', 'office_expense')->sum('amount');
            $balance = $totalIncome - $totalOfficeExpense - $amount;
        } else {
            $lastCashFlow = CashFlow::where('schedule_id', $request->schedule_id)
                ->orderBy('created_at', 'desc')
                ->first();
            
            $balance = $lastCashFlow ? $lastCashFlow->balance : 0;
            
            if ($request->type === 'expense') {
                $balance -= $amount;
            } else {
                $balance += $amount;
            }
        }

        CashFlow::create([
            'schedule_id' => $request->schedule_id,
            'type' => $request->type,
            'office' => $request->office,
            'description' => $request->description,
            'amount' => $amount,
            'balance' => $balance,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('admin.cash-flows.index')
            ->with('success', 'Cash flow berhasil ditambahkan');
    }

    public function show(CashFlow $cashFlow)
    {
        $cashFlow->load(['schedule.route.originBranch', 'schedule.route.destinationBranch', 'schedule.bus']);
        
        return view('admin.cash-flows.show', compact('cashFlow'));
    }

    public function edit(CashFlow $cashFlow)
    {
        $schedules = Schedule::with(['route.originBranch', 'route.destinationBranch', 'bus'])
            ->orderBy('departure_date', 'desc')
            ->get();
            
        return view('admin.cash-flows.edit', compact('cashFlow', 'schedules'));
    }

    public function update(Request $request, CashFlow $cashFlow)
    {
        $request->validate([
            'description' => 'required|string',
            'amount' => 'required|numeric',
        ]);

        $cashFlow->update($request->only(['description', 'amount']));

        return redirect()->route('admin.cash-flows.index')
            ->with('success', 'Cash flow berhasil diperbarui');
    }

    public function destroy(CashFlow $cashFlow)
    {
        $cashFlow->delete();

        return redirect()->route('admin.cash-flows.index')
            ->with('success', 'Cash flow berhasil dihapus');
    }
}