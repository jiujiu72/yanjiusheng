<?php

namespace App\Http\Controllers;

use App\Models\ConsumableApplication;
use App\Models\Expense;
use Illuminate\Http\Request;

class ConsumableApplicationController extends Controller
{
    public function index(Request $request)
    {
        $query = ConsumableApplication::orderBy('applied_at', 'desc');

        $consumables = $query->paginate(20);

        $summary = [
            'total_cost' => ConsumableApplication::sum('total_cost'),
            'linked_cost' => ConsumableApplication::whereNotNull('expense_id')->sum('total_cost'),
            'pending_cost' => ConsumableApplication::whereNull('expense_id')->sum('total_cost'),
        ];

        return view('consumable-applications.index', compact('consumables', 'summary'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'unit' => 'required|string|max:50',
            'unit_price' => 'required|numeric|min:0.01|max:999999',
            'applied_at' => 'required|date',
            'purpose' => 'nullable|string|max:500',
        ], [
            'name.required' => '耗材名称不能为空',
            'quantity.required' => '数量不能为空',
            'quantity.min' => '数量至少为1',
            'unit.required' => '单位不能为空',
            'unit_price.required' => '单价不能为空',
            'applied_at.required' => '申领日期不能为空',
        ]);

        $totalCost = $validated['quantity'] * $validated['unit_price'];
        $validated['total_cost'] = $totalCost;

        $expenseId = null;
        if ($request->has('create_expense')) {
            $expense = Expense::create([
                'date' => $validated['applied_at'],
                'category' => 'equipment',
                'description' => '耗材申领: ' . $validated['name'],
                'amount' => $totalCost,
            ]);
            $expenseId = $expense->id;
        }

        $validated['expense_id'] = $expenseId;
        ConsumableApplication::create($validated);

        return redirect()->route('consumable-applications.index')->with('success', '耗材申领已记录');
    }

    public function linkExpense(ConsumableApplication $consumableApplication)
    {
        if ($consumableApplication->expense_id) {
            return redirect()->route('consumable-applications.index')->with('success', '该记录已关联经费');
        }

        $expense = Expense::create([
            'date' => $consumableApplication->applied_at,
            'category' => 'equipment',
            'description' => '耗材申领: ' . $consumableApplication->name,
            'amount' => $consumableApplication->total_cost,
        ]);

        $consumableApplication->update(['expense_id' => $expense->id]);

        return redirect()->route('consumable-applications.index')->with('success', '已记入经费账目');
    }

    public function destroy(ConsumableApplication $consumableApplication)
    {
        $consumableApplication->delete();
        return redirect()->route('consumable-applications.index')->with('success', '记录已删除');
    }
}
