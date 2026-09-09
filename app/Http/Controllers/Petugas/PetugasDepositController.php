<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\DropPoint;
use App\Models\PointTransaction;
use App\Models\User;
use App\Models\WasteDeposit;
use App\Notifications\DepositRejectedNotification;
use App\Notifications\DepositVerifiedNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PetugasDepositController extends Controller
{
    /**
     * Display a listing of deposits for verification.
     */
    public function index(Request $request): View
    {
        $status = $request->get('status', 'pending');
        $dropPoints = DropPoint::where('is_active', true)->orderBy('name')->get();

        $query = WasteDeposit::with(['user', 'wasteType', 'dropPoint', 'verifier']);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($request->filled('drop_point_id')) {
            $query->where('drop_point_id', $request->drop_point_id);
        }

        $deposits = $query->latest()->paginate(10)->withQueryString();

        // Count pending
        $pendingCount = WasteDeposit::where('status', 'pending')->count();

        return view('petugas.deposits.index', compact('deposits', 'dropPoints', 'status', 'pendingCount'));
    }

    /**
     * Display the detail of a deposit for verification review.
     */
    public function show(WasteDeposit $deposit): View
    {
        $deposit->load(['user', 'wasteType', 'dropPoint', 'verifier', 'pointTransaction']);

        return view('petugas.deposits.show', compact('deposit'));
    }

    /**
     * Verify the deposit, adjust actual weight, credit points, and notify user.
     */
    public function verify(Request $request, WasteDeposit $deposit): RedirectResponse
    {
        if ($deposit->status !== 'pending') {
            return back()->with('error', 'Setoran ini sudah diproses sebelumnya.');
        }

        $validated = $request->validate([
            'actual_weight_kg' => ['required', 'numeric', 'min:0.1', 'max:1000'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        DB::transaction(function () use ($validated, $deposit) {
            $actualWeight = (float) $validated['actual_weight_kg'];
            $pointsPerKg = $deposit->wasteType->points_per_kg;
            $pointsEarned = (int) floor($actualWeight * $pointsPerKg);

            // Update deposit
            $deposit->update([
                'weight_kg' => $actualWeight,
                'status' => 'verified',
                'verified_by' => auth()->id(),
                'verified_at' => now(),
                'notes' => $validated['notes'] ?? $deposit->notes,
            ]);

            // Create credit transaction
            PointTransaction::create([
                'user_id' => $deposit->user_id,
                'type' => 'credit',
                'amount' => $pointsEarned,
                'reference_type' => WasteDeposit::class,
                'reference_id' => $deposit->id,
                'description' => "Setoran {$deposit->wasteType->name} ({$actualWeight} kg) di {$deposit->dropPoint?->name}",
            ]);

            // Increment user points balance
            $nasabah = User::where('id', $deposit->user_id)->lockForUpdate()->first();
            if ($nasabah) {
                $nasabah->increment('points_balance', $pointsEarned);
                $nasabah->notify(new DepositVerifiedNotification($deposit, $pointsEarned));
            }
        });

        return redirect()->route('petugas.deposits.index')
            ->with('success', "Setoran #{$deposit->id} berhasil diverifikasi! Poin telah ditambahkan ke nasabah.");
    }

    /**
     * Reject the deposit with required reason and notify user.
     */
    public function reject(Request $request, WasteDeposit $deposit): RedirectResponse
    {
        if ($deposit->status !== 'pending') {
            return back()->with('error', 'Setoran ini sudah diproses sebelumnya.');
        }

        $validated = $request->validate([
            'rejection_reason' => ['required', 'string', 'min:3', 'max:500'],
        ]);

        DB::transaction(function () use ($validated, $deposit) {
            $deposit->update([
                'status' => 'rejected',
                'verified_by' => auth()->id(),
                'verified_at' => now(),
                'notes' => $validated['rejection_reason'],
            ]);

            $nasabah = User::find($deposit->user_id);
            if ($nasabah) {
                $nasabah->notify(new DepositRejectedNotification($deposit));
            }
        });

        return redirect()->route('petugas.deposits.index')
            ->with('warning', "Setoran #{$deposit->id} telah ditolak.");
    }
}
