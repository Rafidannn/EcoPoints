<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WasteType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WasteTypeController extends Controller
{
    /**
     * Display a listing of waste types.
     */
    public function index(Request $request): View
    {
        $query = WasteType::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $wasteTypes = $query->orderBy('name')->paginate(15)->withQueryString();

        return view('admin.waste-types.index', compact('wasteTypes'));
    }

    /**
     * Show the form for creating a new waste type.
     */
    public function create(): View
    {
        return view('admin.waste-types.create');
    }

    /**
     * Store a newly created waste type in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:waste_types,name'],
            'unit_price_per_kg' => ['required', 'numeric', 'min:0'],
            'points_per_kg' => ['required', 'integer', 'min:1'],
            'description' => ['nullable', 'string', 'max:500'],
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        WasteType::create($validated);

        return redirect()->route('admin.waste-types.index')
            ->with('success', "Jenis sampah '{$validated['name']}' berhasil ditambahkan.");
    }

    /**
     * Show the form for editing the specified waste type.
     */
    public function edit(WasteType $wasteType): View
    {
        return view('admin.waste-types.edit', compact('wasteType'));
    }

    /**
     * Update the specified waste type in storage.
     */
    public function update(Request $request, WasteType $wasteType): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:waste_types,name,'.$wasteType->id],
            'unit_price_per_kg' => ['required', 'numeric', 'min:0'],
            'points_per_kg' => ['required', 'integer', 'min:1'],
            'description' => ['nullable', 'string', 'max:500'],
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', false);

        $wasteType->update($validated);

        return redirect()->route('admin.waste-types.index')
            ->with('success', "Jenis sampah '{$wasteType->name}' berhasil diperbarui.");
    }

    /**
     * Remove the specified waste type from storage.
     */
    public function destroy(WasteType $wasteType): RedirectResponse
    {
        if ($wasteType->wasteDeposits()->exists()) {
            return back()->with('error', "Jenis sampah '{$wasteType->name}' tidak dapat dihapus karena sudah digunakan di data setoran.");
        }

        $name = $wasteType->name;
        $wasteType->delete();

        return redirect()->route('admin.waste-types.index')
            ->with('success', "Jenis sampah '{$name}' berhasil dihapus.");
    }
}
