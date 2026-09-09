<?php

namespace App\Http\Controllers;

use App\Models\PointTransaction;
use App\Models\Reward;
use App\Models\RewardRedemption;
use App\Models\User;
use App\Notifications\RewardRedeemedNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class RewardController extends Controller
{
    /**
     * Display active rewards catalog.
     */
    public function index(Request $request): View
    {
        $rewards = Reward::where('is_active', true)
            ->orderBy('point_cost')
            ->get();

        $user = $request->user();

        return view('rewards.index', compact('rewards', 'user'));
    }

    /**
     * Process reward redemption using user's points.
     */
    public function redeem(Request $request, Reward $reward): RedirectResponse
    {
        $user = $request->user();

        if (! $reward->is_active) {
            return back()->with('error', 'Reward ini sedang tidak aktif.');
        }

        if ($reward->stock < 1) {
            return back()->with('error', 'Mohon maaf, stok reward ini telah habis.');
        }

        if ($user->points_balance < $reward->point_cost) {
            return back()->with('error', 'Saldo poin Anda tidak mencukupi untuk menukar reward ini.');
        }

        $redemption = null;

        DB::transaction(function () use ($reward, $user, &$redemption) {
            $lockedReward = Reward::where('id', $reward->id)->lockForUpdate()->firstOrFail();
            $lockedUser = User::where('id', $user->id)->lockForUpdate()->firstOrFail();

            if ($lockedReward->stock < 1) {
                throw new \Exception('Stok reward telah habis.');
            }

            if ($lockedUser->points_balance < $lockedReward->point_cost) {
                throw new \Exception('Saldo poin tidak mencukupi.');
            }

            // Deduct stock and points
            $lockedReward->decrement('stock', 1);
            $lockedUser->decrement('points_balance', $lockedReward->point_cost);

            // Record redemption
            $redemption = RewardRedemption::create([
                'user_id' => $lockedUser->id,
                'reward_id' => $lockedReward->id,
                'points_used' => $lockedReward->point_cost,
                'status' => 'completed',
                'notes' => 'Penukaran reward berhasil diproses.',
            ]);

            // Record debit transaction
            PointTransaction::create([
                'user_id' => $lockedUser->id,
                'type' => 'debit',
                'amount' => $lockedReward->point_cost,
                'reference_type' => RewardRedemption::class,
                'reference_id' => $redemption->id,
                'description' => "Penukaran reward: {$lockedReward->name}",
            ]);

            // Notify user
            $lockedUser->notify(new RewardRedeemedNotification($redemption));
        });

        return redirect()->route('rewards.history')
            ->with('success', "Selamat! Penukaran reward '{$reward->name}' berhasil. Poin Anda telah dipotong.");
    }

    /**
     * Display user's redemption history.
     */
    public function history(Request $request): View
    {
        $redemptions = RewardRedemption::with('reward')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(10);

        return view('rewards.history', compact('redemptions'));
    }
}
