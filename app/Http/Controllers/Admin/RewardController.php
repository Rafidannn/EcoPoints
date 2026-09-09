<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reward;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class RewardController extends Controller
{
    /**
     * Display a listing of rewards.
     */
    public function index(Request $request): View
    {
        $query = Reward::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $rewards = $query->withCount('redemptions')->orderBy('point_cost')->paginate(15)->withQueryString();

        return view('admin.rewards.index', compact('rewards'));
    }

    /**
     * Show the form for creating a new reward.
     */
    public function create(): View
    {
        return view('admin.rewards.create');
    }

    /**
     * Store a newly created reward in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:1000'],
            'point_cost' => ['required', 'integer', 'min:1'],
            'stock' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('rewards', 'public');
        }

        Reward::create($validated);

        return redirect()->route('admin.rewards.index')
            ->with('success', "Reward '{$validated['name']}' berhasil ditambahkan.");
    }

    /**
     * Show the form for editing the specified reward.
     */
    public function edit(Reward $reward): View
    {
        return view('admin.rewards.edit', compact('reward'));
    }

    /**
     * Update the specified reward in storage.
     */
    public function update(Request $request, Reward $reward): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:1000'],
            'point_cost' => ['required', 'integer', 'min:1'],
            'stock' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', false);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($reward->image) {
                Storage::disk('public')->delete($reward->image);
            }
            $validated['image'] = $request->file('image')->store('rewards', 'public');
        }

        $reward->update($validated);

        return redirect()->route('admin.rewards.index')
            ->with('success', "Reward '{$reward->name}' berhasil diperbarui.");
    }

    /**
     * Remove the specified reward from storage.
     */
    public function destroy(Reward $reward): RedirectResponse
    {
        if ($reward->redemptions()->exists()) {
            return back()->with('error', "Reward '{$reward->name}' tidak dapat dihapus karena sudah memiliki riwayat penukaran.");
        }

        $name = $reward->name;

        if ($reward->image) {
            Storage::disk('public')->delete($reward->image);
        }

        $reward->delete();

        return redirect()->route('admin.rewards.index')
            ->with('success', "Reward '{$name}' berhasil dihapus.");
    }
}
