<?php

namespace App\Http\Controllers;

use App\Models\PointTransaction;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PointHistoryController extends Controller
{
    /**
     * Display current points balance and mutation history with filters.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $query = PointTransaction::with('reference')
            ->where('user_id', $user->id);

        if ($request->filled('type') && in_array($request->type, ['credit', 'debit'])) {
            $query->where('type', $request->type);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $transactions = $query->latest()->paginate(15)->withQueryString();

        $totalCredit = PointTransaction::where('user_id', $user->id)->where('type', 'credit')->sum('amount');
        $totalDebit = PointTransaction::where('user_id', $user->id)->where('type', 'debit')->sum('amount');

        return view('points.index', compact('transactions', 'totalCredit', 'totalDebit'));
    }
}
