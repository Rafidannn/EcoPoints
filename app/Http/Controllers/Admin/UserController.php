<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request): View
    {
        $query = User::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                    ->orWhere('email', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->withCount(['wasteDeposits', 'pointTransactions'])
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        $roles = UserRole::cases();

        return view('admin.users.index', compact('users', 'roles'));
    }

    /**
     * Update the specified user's role.
     */
    public function updateRole(Request $request, User $user): RedirectResponse
    {
        // Prevent admin from changing their own role
        if ($user->id === $request->user()->id) {
            return back()->with('error', 'Anda tidak dapat mengubah role akun Anda sendiri.');
        }

        $validated = $request->validate([
            'role' => ['required', 'in:user,petugas,admin'],
        ]);

        $user->update(['role' => $validated['role']]);

        return back()->with('success', "Role pengguna '{$user->name}' berhasil diubah menjadi {$validated['role']}.");
    }
}
