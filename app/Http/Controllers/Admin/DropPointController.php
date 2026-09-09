<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DropPoint;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DropPointController extends Controller
{
    /**
     * Display a listing of drop points.
     */
    public function index(Request $request): View
    {
        $query = DropPoint::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                    ->orWhere('address', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $dropPoints = $query->withCount('wasteDeposits')->orderBy('name')->paginate(15)->withQueryString();

        return view('admin.drop-points.index', compact('dropPoints'));
    }

    /**
     * Show the form for creating a new drop point.
     */
    public function create(): View
    {
        return view('admin.drop-points.create');
    }

    /**
     * Store a newly created drop point in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'address' => ['required', 'string', 'max:500'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        DropPoint::create($validated);

        return redirect()->route('admin.drop-points.index')
            ->with('success', "Drop point '{$validated['name']}' berhasil ditambahkan.");
    }

    /**
     * Show the form for editing the specified drop point.
     */
    public function edit(DropPoint $dropPoint): View
    {
        return view('admin.drop-points.edit', compact('dropPoint'));
    }

    /**
     * Update the specified drop point in storage.
     */
    public function update(Request $request, DropPoint $dropPoint): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'address' => ['required', 'string', 'max:500'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', false);

        $dropPoint->update($validated);

        return redirect()->route('admin.drop-points.index')
            ->with('success', "Drop point '{$dropPoint->name}' berhasil diperbarui.");
    }

    /**
     * Remove the specified drop point from storage.
     */
    public function destroy(DropPoint $dropPoint): RedirectResponse
    {
        if ($dropPoint->wasteDeposits()->exists()) {
            return back()->with('error', "Drop point '{$dropPoint->name}' tidak dapat dihapus karena sudah memiliki data setoran.");
        }

        $name = $dropPoint->name;
        $dropPoint->delete();

        return redirect()->route('admin.drop-points.index')
            ->with('success', "Drop point '{$name}' berhasil dihapus.");
    }
}
