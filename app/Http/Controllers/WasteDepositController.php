<?php

namespace App\Http\Controllers;

use App\Models\DropPoint;
use App\Models\WasteDeposit;
use App\Models\WasteType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class WasteDepositController extends Controller
{
    /**
     * Display a listing of user's waste deposits.
     */
    public function index(Request $request): View
    {
        $query = WasteDeposit::with(['wasteType', 'dropPoint'])
            ->where('user_id', $request->user()->id);

        if ($request->filled('status') && in_array($request->status, ['pending', 'verified', 'rejected'])) {
            $query->where('status', $request->status);
        }

        $deposits = $query->latest()->paginate(10)->withQueryString();

        return view('deposits.index', compact('deposits'));
    }

    /**
     * Show the form for creating a new waste deposit.
     */
    public function create(): View
    {
        $wasteTypes = WasteType::where('is_active', true)->orderBy('name')->get();
        $dropPoints = DropPoint::where('is_active', true)->orderBy('name')->get();

        return view('deposits.create', compact('wasteTypes', 'dropPoints'));
    }

    /**
     * Store a newly created waste deposit in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'waste_type_id' => ['required', 'exists:waste_types,id'],
            'drop_point_id' => ['required', 'exists:drop_points,id'],
            'weight_kg' => ['required', 'numeric', 'min:0.1', 'max:1000'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('deposits', 'public');
        }

        $deposit = WasteDeposit::create([
            'user_id' => $request->user()->id,
            'drop_point_id' => $validated['drop_point_id'],
            'waste_type_id' => $validated['waste_type_id'],
            'weight_kg' => $validated['weight_kg'],
            'photo' => $photoPath,
            'status' => 'pending',
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('deposits.show', $deposit)
            ->with('success', 'Setoran sampah berhasil diajukan! Menunggu verifikasi petugas.');
    }

    /**
     * Display the specified waste deposit.
     */
    public function show(Request $request, WasteDeposit $deposit): View
    {
        // User can only view their own deposit unless they are petugas or admin
        if ($deposit->user_id !== $request->user()->id && ! $request->user()->hasRole(['petugas', 'admin'])) {
            abort(403, 'Akses ditolak: Anda bukan pemilik setoran ini.');
        }

        $deposit->load(['wasteType', 'dropPoint', 'verifier', 'pointTransaction']);

        return view('deposits.show', compact('deposit'));
    }
}
