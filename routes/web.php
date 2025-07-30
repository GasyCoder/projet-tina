<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

// Livewire components
use App\Livewire\Dashboard;
use App\Livewire\Users\UserIndex;
use App\Livewire\Lieux\LieuxIndex;
use App\Livewire\Stocks\StockIndex;
use App\Livewire\Voyage\VoyageIndex;
use App\Livewire\Voyage\VoyageShow;
use App\Livewire\Finance\FinanceIndex;
use App\Livewire\Finance\SituationFinanciere;
use App\Livewire\Finance\DashboardSituations;
use App\Livewire\Finance\SuiviTransactions;
use App\Livewire\Finance\Depenses;
use App\Livewire\Finance\Revenus;
use App\Livewire\Products\ProductIndex;
use App\Livewire\Vehicules\VehiculeIndex;

// Authentication routes
require __DIR__ . '/auth.php';

// Redirections vers login
Route::redirect('/', '/login');
Route::redirect('/register', '/login');

Route::get('/', function () {
    return Auth::check() ? redirect('/dashboard') : redirect('/login');
});

// Routes accessibles aux utilisateurs authentifiés et vérifiés
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    // Sections principales
    Route::get('/produits', ProductIndex::class)->name('produits.index');
    Route::get('/lieux', LieuxIndex::class)->name('lieux.index');
    Route::get('/vehicules', VehiculeIndex::class)->name('vehicules.index');
    Route::get('/users', UserIndex::class)->name('users.index');

    // Voyages
    Route::get('/voyages', VoyageIndex::class)->name('voyages.index');
    Route::get('/voyages/{voyage}', VoyageShow::class)->name('voyages.show');

    // Finances
    Route::get('/finance', FinanceIndex::class)->name('finance.index');
    Route::get('/finance/situations', SituationFinanciere::class)->name('finance.situations');
    Route::get('/finance/dashboard-situations', DashboardSituations::class)->name('finance.dashboard.situations');
    // Finances - Routes existantes

    // Finances - Nouvelles routes pour les interfaces séparées
    Route::get('/finance/suivi-transactions', SuiviTransactions::class)->name('finance.suivi-transactions');
    Route::get('/finance/revenus', Revenus::class)->name('finance.revenus');
    Route::get('/finance/depenses', Depenses::class)->name('finance.depenses');

    // Gestion de profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Routes réservées aux administrateurs
Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/stocks', StockIndex::class)->name('stocks');
});

