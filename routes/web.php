<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\CabinetController;
use Illuminate\Support\Facades\Route;

//  Redirection vers login
Route::get('/', function () {
    return redirect()->route('login');
});

// Route par défaut de Breeze (sera redirigée par le middleware)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// ========================================
// ROUTES POUR CHAQUE RÔLE
// ========================================

// 🔵 ADMIN (SÉCURISÉ)
Route::prefix('admin')->middleware(['auth', 'verified', 'role:1'])->group(function () {
    Route::get('/dashboard', \App\Livewire\Admin\Dashboard::class)->name('admin.dashboard');

    Route::get('cabinets', \App\Livewire\Admin\CabinetManager::class)->name('admin.cabinets.index');
    Route::get('utilisateurs', \App\Livewire\Admin\UserManager::class)->name('admin.users.index');
    Route::get('clients', \App\Livewire\Admin\ClientManager::class)->name('admin.clients.index');
    Route::get('cotisations', \App\Livewire\Admin\CotisationManager::class)->name('admin.cotisations.index');
    Route::get('retraits', \App\Livewire\Admin\RetraitManager::class)->name('admin.retraits.index');
    Route::get('rapports', \App\Livewire\Admin\RapportManager::class)->name('admin.rapports.index');
    Route::get('parametres', \App\Livewire\Admin\ParametresManager::class)->name('admin.parametres.index');
});

// 🟢 CAISSIER
Route::prefix('caissier')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('caissier.dashboard');
    })->name('caissier.dashboard');
});

// 🟡 COLLECTEUR
Route::prefix('collecteur')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('collecteur.dashboard');
    })->name('collecteur.dashboard');
});

// 🟠 COMPTABLE
Route::prefix('comptable')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('comptable.dashboard');
    })->name('comptable.dashboard');
});

// 🔴 CLIENT
Route::prefix('client')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('client.dashboard');
    })->name('client.dashboard');
});

// ========================================
// ROUTES COMMUNES (PROFIL)
// ========================================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
