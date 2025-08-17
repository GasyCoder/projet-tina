<?php

use App\Livewire\Dashboard;
use App\Livewire\Users\UserIndex;

// Livewire components
use App\Livewire\Lieux\LieuxIndex;
use App\Livewire\Voyage\VoyageShow;
use App\Livewire\Finance\AchatIndex;
use App\Livewire\Finance\VenteIndex;
use App\Livewire\Voyage\VoyageIndex;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Livewire\Categorie\Categories;
use App\Livewire\Products\ProductIndex;
use App\Livewire\Partenaire\Partenaires;
use App\Livewire\Categorie\CategorieShow;
use App\Livewire\Vehicules\VehiculeIndex;
use App\Http\Controllers\ProfileController;
use App\Livewire\Finance\CompteManager;
use App\Livewire\Partenaire\PartenaireShow;
use App\Livewire\Finance\MouvementsFinanciers;

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
    // Route::get('/finance', AchatIndex::class)->name('finance.index');
    Route::get('/vente', VenteIndex::class)->name('vente.index');
    Route::get('/compte', CompteManager::class)->name('compte.index');
    Route::get('/finance/situations', MouvementsFinanciers::class)->name('finance.situations');
    Route::get('/partenaire', Partenaires::class)->name('partenaire.index');
    Route::get('/partenaire/{partenaire}', PartenaireShow::class)->name('partenaire.show');
    

    // Categories
    Route::get('/categories', Categories::class)->name('categories.index');
    Route::get('/categories/{categorie}', CategorieShow::class)->name('categories.show');
    Route::get('/categories/{categorie}', CategorieShow::class)->name('categorie.categories.show');

    // Gestion de profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

