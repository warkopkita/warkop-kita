<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminExpenseController extends Controller
{
    public function index(Request $request)
    {
        $query = Expense::with(['recorder', 'approver'])->latest('expense_date');

        if ($request->has('category') && $request->category != '') {
            $query->where('category', $request->category);
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $expenses = $query->paginate(20);
        $totalApproved = Expense::where('status', 'approved')->sum('amount');
        $totalPending = Expense::where('status', 'pending')->sum('amount');

        return view('admin.expenses.index', compact('expenses', 'totalApproved', 'totalPending'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required|in:operational,raw_material,maintenance,utility,salary,cash_advance,other',
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:1',
            'expense_date' => 'required|date',
            'description' => 'nullable|string',
            'receipt_photo' => 'nullable|image|max:2048',
        ]);

        $receiptPath = null;
        if ($request->hasFile('receipt_photo')) {
            $receiptPath = $request->file('receipt_photo')->store('receipts/' . date('Y/m'), 'public');
        }

        $isOwner = Auth::user()->isOwner() || Auth::user()->isManager();

        Expense::create([
            'category' => $request->category,
            'title' => $request->title,
            'description' => $request->description,
            'amount' => $request->amount,
            'receipt_photo' => $receiptPath,
            'expense_date' => $request->expense_date,
            'status' => $isOwner ? 'approved' : 'pending',
            'recorded_by' => Auth::id(),
            'approved_by' => $isOwner ? Auth::id() : null,
        ]);

        return redirect()->route('admin.expenses.index')->with('success', 'Pengeluaran kas berhasil dicatat!');
    }

    public function approve($id)
    {
        $expense = Expense::findOrFail($id);
        $expense->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
        ]);

        return back()->with('success', 'Pengeluaran disetujui.');
    }

    public function reject($id)
    {
        $expense = Expense::findOrFail($id);
        $expense->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
        ]);

        return back()->with('success', 'Pengeluaran ditolak.');
    }
}
