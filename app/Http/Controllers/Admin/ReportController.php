<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PointTransaction;
use App\Models\RewardRedemption;
use App\Models\User;
use App\Models\WasteDeposit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReportController extends Controller
{
    /**
     * Display the reports overview.
     */
    public function index(Request $request): View
    {
        $period = $request->get('period', '30');
        $startDate = now()->subDays((int) $period)->startOfDay();

        // --- Overview statistics ---
        $totalUsers = User::where('role', 'user')->count();
        $totalDeposits = WasteDeposit::count();
        $totalVerifiedDeposits = WasteDeposit::where('status', 'verified')->count();
        $totalPendingDeposits = WasteDeposit::where('status', 'pending')->count();
        $totalWeightKg = WasteDeposit::where('status', 'verified')->sum('weight_kg');
        $totalPointsIssued = PointTransaction::where('type', 'credit')->sum('amount');
        $totalPointsRedeemed = PointTransaction::where('type', 'debit')->sum('amount');
        $totalRedemptions = RewardRedemption::count();

        // --- Period statistics ---
        $depositsInPeriod = WasteDeposit::where('created_at', '>=', $startDate)->count();
        $verifiedInPeriod = WasteDeposit::where('status', 'verified')->where('created_at', '>=', $startDate)->count();
        $weightInPeriod = WasteDeposit::where('status', 'verified')->where('created_at', '>=', $startDate)->sum('weight_kg');
        $pointsIssuedInPeriod = PointTransaction::where('type', 'credit')->where('created_at', '>=', $startDate)->sum('amount');

        // --- Deposit by waste type (top 10) ---
        $depositsByWasteType = WasteDeposit::where('status', 'verified')
            ->select('waste_type_id', DB::raw('COUNT(*) as total_deposits'), DB::raw('SUM(weight_kg) as total_weight_kg'))
            ->with('wasteType:id,name')
            ->groupBy('waste_type_id')
            ->orderByDesc('total_weight_kg')
            ->limit(10)
            ->get();

        // --- Deposit by drop point ---
        $depositsByDropPoint = WasteDeposit::where('status', 'verified')
            ->select('drop_point_id', DB::raw('COUNT(*) as total_deposits'), DB::raw('SUM(weight_kg) as total_weight_kg'))
            ->with('dropPoint:id,name')
            ->groupBy('drop_point_id')
            ->orderByDesc('total_deposits')
            ->limit(10)
            ->get();

        // --- Top redeemed rewards ---
        $topRewards = RewardRedemption::select('reward_id', DB::raw('COUNT(*) as total_redeemed'))
            ->with('reward:id,name,point_cost')
            ->groupBy('reward_id')
            ->orderByDesc('total_redeemed')
            ->limit(10)
            ->get();

        // --- Monthly deposit trend (last 6 months) ---
        $monthlyTrend = WasteDeposit::where('status', 'verified')
            ->where('created_at', '>=', now()->subMonths(6)->startOfMonth())
            ->select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as total_deposits'),
                DB::raw('SUM(weight_kg) as total_weight_kg')
            )
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        // --- Top users by points earned ---
        $topUsersByPoints = User::where('role', 'user')
            ->orderByDesc('points_balance')
            ->limit(10)
            ->get(['id', 'name', 'email', 'points_balance']);

        return view('admin.reports.index', compact(
            'period',
            'totalUsers',
            'totalDeposits',
            'totalVerifiedDeposits',
            'totalPendingDeposits',
            'totalWeightKg',
            'totalPointsIssued',
            'totalPointsRedeemed',
            'totalRedemptions',
            'depositsInPeriod',
            'verifiedInPeriod',
            'weightInPeriod',
            'pointsIssuedInPeriod',
            'depositsByWasteType',
            'depositsByDropPoint',
            'topRewards',
            'monthlyTrend',
            'topUsersByPoints'
        ));
    }
}
