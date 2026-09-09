<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\PointHistoryController;
use App\Http\Controllers\RewardController;
use App\Http\Controllers\WasteDepositController;

// User / Nasabah Dashboard & Features
Route::middleware(['auth', 'verified', 'role:user'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::resource('deposits', WasteDepositController::class)->only(['index', 'create', 'store', 'show']);
    Route::get('/points', [PointHistoryController::class, 'index'])->name('points.index');

    // Reward Catalog & Redemptions
    Route::get('/rewards', [RewardController::class, 'index'])->name('rewards.index');
    Route::post('/rewards/{reward}/redeem', [RewardController::class, 'redeem'])->name('rewards.redeem');
    Route::get('/rewards/history', [RewardController::class, 'history'])->name('rewards.history');
});

use App\Http\Controllers\Petugas\PetugasDepositController;

// Petugas Drop Point Dashboard & Verifikasi
Route::middleware(['auth', 'verified', 'role:petugas,admin'])->prefix('petugas')->as('petugas.')->group(function () {
    Route::get('/dashboard', function () {
        return view('petugas.dashboard');
    })->name('dashboard');

    Route::get('/deposits', [PetugasDepositController::class, 'index'])->name('deposits.index');
    Route::get('/deposits/{deposit}', [PetugasDepositController::class, 'show'])->name('deposits.show');
    Route::post('/deposits/{deposit}/verify', [PetugasDepositController::class, 'verify'])->name('deposits.verify');
    Route::post('/deposits/{deposit}/reject', [PetugasDepositController::class, 'reject'])->name('deposits.reject');
});

use App\Http\Controllers\Admin\DropPointController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\RewardController as AdminRewardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\WasteTypeController;

// Admin Dashboard & Master Data
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->as('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Master Data: Waste Types
    Route::resource('waste-types', WasteTypeController::class)->except(['show']);

    // Master Data: Drop Points
    Route::resource('drop-points', DropPointController::class)->except(['show']);

    // Master Data: Rewards
    Route::resource('rewards', AdminRewardController::class)->except(['show']);

    // User Management
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::patch('/users/{user}/role', [UserController::class, 'updateRole'])->name('users.update-role');

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
