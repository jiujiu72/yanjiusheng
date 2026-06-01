<?php

namespace App\Http\Controllers;

use App\Models\ItemBorrowing;
use Illuminate\Http\Request;

class ItemBorrowingController extends Controller
{
    public function index(Request $request)
    {
        ItemBorrowing::where('status', 'borrowed')
            ->where('expected_return_date', '<', today())
            ->update(['status' => 'overdue']);

        $query = ItemBorrowing::orderBy('borrow_date', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $borrowings = $query->paginate(20);

        $summary = [
            'total' => ItemBorrowing::count(),
            'active' => ItemBorrowing::whereIn('status', ['borrowed', 'overdue'])->count(),
            'overdue' => ItemBorrowing::where('status', 'overdue')->count(),
            'returned' => ItemBorrowing::where('status', 'returned')->count(),
        ];

        $overdueItems = ItemBorrowing::where('status', 'overdue')->get();

        return view('item-borrowings.index', compact('borrowings', 'summary', 'overdueItems'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'borrower' => 'required|string|max:255',
            'borrow_date' => 'required|date',
            'expected_return_date' => 'required|date|after_or_equal:borrow_date',
            'notes' => 'nullable|string|max:500',
        ], [
            'item_name.required' => '物品名称不能为空',
            'borrower.required' => '借用对象不能为空',
            'borrow_date.required' => '借用日期不能为空',
            'expected_return_date.required' => '预计归还日期不能为空',
            'expected_return_date.after_or_equal' => '归还日期不能早于借用日期',
        ]);

        $validated['status'] = 'borrowed';

        ItemBorrowing::create($validated);

        return redirect()->route('item-borrowings.index')->with('success', '借用记录已添加');
    }

    public function markReturned(ItemBorrowing $itemBorrowing)
    {
        $itemBorrowing->update([
            'status' => 'returned',
            'actual_return_date' => today(),
        ]);

        return redirect()->route('item-borrowings.index')->with('success', '物品已标记归还');
    }

    public function destroy(ItemBorrowing $itemBorrowing)
    {
        $itemBorrowing->delete();
        return redirect()->route('item-borrowings.index')->with('success', '记录已删除');
    }
}
