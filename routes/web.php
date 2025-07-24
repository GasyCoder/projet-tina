<?php

use App\Livewire\Dashboard;
use App\Livewire\Users\UserIndex;
use App\Livewire\Lieux\LieuxIndex;
use App\Livewire\Voyage\VoyageShow;
use App\Livewire\Voyage\VoyageIndex;
use Illuminate\Support\Facades\Route;
use App\Livewire\Finance\FinanceIndex;
use App\Livewire\Products\ProductIndex;
use App\Livewire\Vehicules\VehiculeIndex;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('welcome');
});


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/produits', ProductIndex::class)->name('produits.index');
    Route::get('/lieux',    LieuxIndex::class)->name('lieux.index');
    Route::get('/vehicules', VehiculeIndex::class)->name('vehicules.index');
    Route::get('/users',    UserIndex::class)->name('users.index');
    
    // Routes voyages
    Route::get('/voyages', VoyageIndex::class)->name('voyages.index');
    Route::get('/voyages/{voyage}', VoyageShow::class)->name('voyages.show');

    // Finances
    Route::get('/finance', FinanceIndex::class)->name('finance.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
