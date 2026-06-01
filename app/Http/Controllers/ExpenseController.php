<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $query = Expense::orderBy('date', 'desc');

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('status')) {
            $query->where('is_reimbursed', $request->status === 'reimbursed');
        }

        $expenses = $query->paginate(20);

        $summary = [
            'total' => Expense::sum('amount'),
            'reimbursed' => Expense::where('is_reimbursed', true)->sum('reimbursed_amount'),
            'pending' => Expense::where('is_reimbursed', false)->sum('amount'),
        ];

        $categoryLabels = [
            'research' => '科研经费',
            'travel' => '差旅费',
            'equipment' => '设备耗材',
            'books' => '图书资料',
            'printing' => '打印复印',
            'other' => '其他',
        ];

        return view('expenses.index', compact('expenses', 'summary', 'categoryLabels'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'category' => 'required|in:research,travel,equipment,books,printing,other',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01|max:999999',
            'receipt_note' => 'nullable|string|max:255',
        ]);

        Expense::create($validated);

        return redirect()->route('expenses.index')->with('success', '支出已记录');
    }

    public function toggleReimbursed(Expense $expense, Request $request)
    {
        $validated = $request->validate([
            'reimbursed_amount' => 'nullable|numeric|min:0',
        ]);

        $expense->update([
            'is_reimbursed' => !$expense->is_reimbursed,
            'reimbursed_amount' => $expense->is_reimbursed ? 0 : ($validated['reimbursed_amount'] ?? $expense->amount),
            'reimbursed_at' => $expense->is_reimbursed ? null : today(),
        ]);

        return redirect()->route('expenses.index')->with('success', '报销状态已更新');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();
        return redirect()->route('expenses.index')->with('success', '记录已删除');
    }
}
