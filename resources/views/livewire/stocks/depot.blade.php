{{-- resources/views/livewire/stocks/depot.blade.php --}}
<div>
   <!-- Messages de succès/erreur -->
    @if (session()->has('message'))
        <div class="rounded-md bg-green-50 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">{{ session('message') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="rounded-md bg-red-50 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif
    <!-- Statistiques du dépôt -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-6">
        <!-- Stock total -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-indigo-500 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Stock total</dt>
                            <dd class="text-lg font-medium text-gray-900">
                                {{ number_format($depots->sum('reste_kg'), 0, ',', ' ') }} kg
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Entrées différentes -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Entrées</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $depots->total() }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stock en attente -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v-4m0 4l-4-4m4 4l4-4m3 4v12a2 2 0 01-2 2H7a2 2 0 01-2-2V8a2 2 0 012-2h10a2 2 0 012 2z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">En attente</dt>
                            <dd class="text-lg font-medium text-gray-900">
                                {{ $depots->where('statut', 'en_attente')->count() }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Valeur du stock -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Valeur stock</dt>
                            <dd class="text-lg font-medium text-gray-900">
                                {{ number_format($depots->sum(function($depot) {
                                    return $depot->reste_kg * ($depot->prix_marche_actuel_mga ?? 0);
                                }), 0, ',', ' ') }} Ar
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Barre d'outils -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-4 py-5 sm:p-6">
            <div class="sm:flex sm:items-center sm:justify-between">
                <div class="flex-1 min-w-0">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-4">
                        <!-- Recherche -->
                        <div class="flex-1">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <input wire:model.live="search" type="text"
                                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="Rechercher un dépôt...">
                            </div>
                        </div>

                        <!-- Filtre par statut -->
                        <div class="mt-3 sm:mt-0">
                            <select wire:model.live="filterStatut"
                                class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="">Tous statuts</option>
                                <option value="en_stock">En stock</option>
                                <option value="sorti">Sorti</option>
                                <option value="en_attente">En attente</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="mt-5 sm:mt-0 sm:ml-6 sm:flex-shrink-0 sm:flex sm:items-center space-x-3">
                    <button wire:click="openEntreeModal" type="button"
                        class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Entrée Stock
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div x-data="depotDashboard()" class="space-y-6">
        <!-- Tableau des dépôts -->
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Dépôts enregistrés</h3>
                    <p class="mt-1 text-sm text-gray-500">Liste des entrées et sorties de stock</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" 
                                wire:click="sortBy('date')">
                                Date Entrée
                                @if($sortField === 'date')
                                    @if($sortDirection === 'asc') ↑ @else ↓ @endif
                                @endif
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Produit</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Origine</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Propriétaire</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Poids Entrée</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Poids Sortie</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Reste</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Statut</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($depots as $depot)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $depot->date_entree ? $depot->date_entree->format('d/m/Y') : ($depot->date ? $depot->date->format('d/m/Y') : 'N/A') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                                <span class="text-sm font-medium text-gray-700">
                                                    {{ $depot->produit ? substr($depot->produit->nom, 0, 1) : 'P' }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $depot->produit->nom ?? 'Produit inconnu' }}
                                            </div>
                                            <div class="text-sm text-gray-500">kg</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $depot->origine ?? 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $depot->proprietaire->name ?? 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ number_format($depot->poids_depart_kg ?? 0, 2, ',', ' ') }} kg
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @if($depot->reste_kg)
                                        {{ number_format($depot->reste_kg, 2, ',', ' ') }} kg
                                    @else
                                        -
                                    @endif
                                </td>
                               <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                 {{ number_format(max(0, ($depot->poids_depart_kg ?? 0) - ($depot->reste_kg ?? 0)), 2, ',', ' ') }} kg
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statut = $depot->statut ?? 'en_stock';
                                    @endphp
                                    @if($statut === 'en_stock')
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            En stock
                                        </span>
                                    @elseif($statut === 'sorti')
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                            Sorti
                                        </span>
                                    @elseif($statut === 'en_attente')
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            En attente
                                        </span>
                                    @else
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                            {{ ucfirst($statut) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        {{-- Correction: Ajouter des logs et une meilleure gestion --}}
                                        <button 
                                            wire:click="showDetails({{ $depot->id }})" 
                                            onclick="console.log('Détails cliqué pour ID: {{ $depot->id }}')"
                                            class="text-indigo-600 hover:text-indigo-900 transition-colors duration-200">
                                            Détails
                                        </button>
                                        @if(($depot->statut ?? 'en_stock') === 'en_stock' && ($depot->reste_kg ?? 0) > 0)
                                            <button 
                                                wire:click="prepareSortie({{ $depot->id }})" 
                                                onclick="console.log('Sortie cliquée pour ID: {{ $depot->id }}')"
                                                class="text-red-600 hover:text-red-900 transition-colors duration-200">
                                                Sortie
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-4 text-center text-sm text-gray-500">
                                    Aucun dépôt enregistré
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($depots->hasPages())
                <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 sm:px-6">
                    {{ $depots->links() }}
                </div>
            @endif
        </div>
    </div>
        <!-- Modal de détails -->
    @if ($showDetailsModal && $detailsDepot)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeDetailsModal"></div>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                                    Détails du dépôt
                                </h3>

                                <div class="space-y-4">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Date d'entrée</label>
                                            <div class="mt-1 text-sm text-gray-900">
                                                {{ $detailsDepot->date ? $detailsDepot->date->format('d/m/Y') : 'N/A' }}
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Référence</label>
                                            <div class="mt-1 text-sm text-gray-900">{{ $detailsDepot->reference ?? 'N/A' }}</div>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Produit</label>
                                            <div class="mt-1 text-sm text-gray-900">{{ $detailsDepot->produit->nom ?? 'Produit inconnu' }}</div>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Propriétaire</label>
                                            <div class="mt-1 text-sm text-gray-900">{{ $detailsDepot->proprietaire->name ?? 'N/A' }}</div>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Sacs pleins</label>
                                            <div class="mt-1 text-sm text-gray-900">{{ $detailsDepot->sacs_pleins_arrivee ?? 0 }}</div>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Sacs demi</label>
                                            <div class="mt-1 text-sm text-gray-900">{{ $detailsDepot->sacs_demi_arrivee ?? 0 }}</div>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Poids total</label>
                                            <div class="mt-1 text-sm text-gray-900">{{ number_format($detailsDepot->poids_arrivee_kg ?? 0, 2, ',', ' ') }} kg</div>
                                        </div>
                                    </div>

                                    @if($detailsDepot->observation)
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Observation</label>
                                            <div class="mt-1 text-sm text-gray-900">{{ $detailsDepot->observation }}</div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="closeDetailsModal" type="button"
                            class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Fermer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif


    <!-- Modal d'entrée de stock -->
    @if ($showEntreeModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
            aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                    wire:click="closeEntreeModal"></div>

                <div
                    class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                                    Nouvelle entrée de stock
                                </h3>

                                <div class="space-y-4">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Date entrée*</label>
                                            <input wire:model="formEntree.date_entree" type="date" required
                                                class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                            @error('formEntree.date_entree') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                        </div>
                                        
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Produit*</label>
                                            <select wire:model="formEntree.produit_id" required
                                                class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                                <option value="">Sélectionner un produit...</option>
                                                @foreach($produits as $produit)
                                                    <option value="{{ $produit->id }}">{{ $produit->nom }}</option>
                                                @endforeach
                                            </select>
                                            @error('formEntree.produit_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Origine*</label>
                                            <input wire:model="formEntree.origine" type="text" required
                                                class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                                placeholder="Origine du produit">
                                            @error('formEntree.origine') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                        </div>
                                        
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Dépôt*</label>
                                            <select wire:model="formEntree.depot_id" required
                                                class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                                <option value="">Sélectionner un dépôt...</option>
                                                @foreach($lieux as $lieu)
                                                    <option value="{{ $lieu->id }}">{{ $lieu->nom }}</option>
                                                @endforeach
                                            </select>
                                            @error('formEntree.depot_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Propriétaire*</label>
                                            <select wire:model="formEntree.proprietaire_id" required
                                                class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                                <option value="">Sélectionner un propriétaire...</option>
                                                @foreach($proprietaires as $proprietaire)
                                                    <option value="{{ $proprietaire->id }}">{{ $proprietaire->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('formEntree.proprietaire_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                        </div>
                                        
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Prix marché actuel (Ar/kg)*</label>
                                            <input wire:model="formEntree.prix_marche_actuel_mga" type="number" step="0.01" required
                                                class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                            @error('formEntree.prix_marche_actuel_mga') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Sacs pleins</label>
                                            <input wire:model="formEntree.sacs_pleins" type="number"
                                                class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                        </div>
                                        
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Sacs demi</label>
                                            <input wire:model="formEntree.sacs_demi" type="number"
                                                class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                        </div>
                                        
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Poids (kg)*</label>
                                            <input wire:model="formEntree.poids_entree_kg" type="number" step="0.01" required
                                                class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                            @error('formEntree.poids_entree_kg') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Observation</label>
                                        <textarea wire:model="formEntree.observation" rows="2"
                                            class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                            placeholder="Notes supplémentaires..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="saveEntree" type="button"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Enregistrer
                        </button>
                        <button wire:click="closeEntreeModal" type="button"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Annuler
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal de sortie de stock -->
    @if ($showSortieModal && $selectedDepot)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
            aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                    wire:click="$set('showSortieModal', false)"></div>

                <div
                    class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                                    Sortie de stock
                                </h3>

                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Produit</label>
                                        <input type="text" value="{{ $selectedDepot->produit->nom ?? 'Produit inconnu' }}" disabled
                                            class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 bg-gray-100 text-gray-500">
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Stock disponible</label>
                                            <input type="text" value="{{ number_format($selectedDepot->reste_kg ?? 0, 2, ',', ' ') }} kg" disabled
                                                class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 bg-gray-100 text-gray-500">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Quantité sortie (kg)*</label>
                                            <input wire:model="formSortie.poids_sortie_kg" type="number" step="0.01" required
                                                min="0.01" max="{{ $selectedDepot->reste_kg ?? 0 }}"
                                                class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-red-500 focus:border-red-500">
                                            @error('formSortie.poids_sortie_kg') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Date sortie*</label>
                                        <input wire:model="formSortie.date_sortie" type="date" required
                                            class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-red-500 focus:border-red-500">
                                        @error('formSortie.date_sortie') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Véhicule de sortie</label>
                                        <select wire:model="formSortie.vehicule_sortie_id"
                                            class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-red-500 focus:border-red-500">
                                            <option value="">Sélectionner un véhicule...</option>
                                            @foreach($vehicules as $vehicule)
                                                <option value="{{ $vehicule->id }}">{{ $vehicule->immatriculation }} - {{ $vehicule->modele }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Motif de sortie</label>
                                        <textarea wire:model="formSortie.observation" rows="2"
                                            class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-red-500 focus:border-red-500"
                                            placeholder="Raison de la sortie..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="saveSortie" type="button"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Confirmer la sortie
                        </button>
                        <button wire:click="$set('showSortieModal', false)" type="button"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Annuler
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <script>
        function depotDashboard() {
            return {
                details: {},
                showDetailsModal: false,

                ouvrirDetails(depot) {
                    this.details = {
                        date_entree: this.formatDate(depot.date_entree || depot.date),
                        produit: depot.produit ? depot.produit.nom : 'Produit inconnu',
                        origine: depot.origine || 'N/A',
                        proprietaire: depot.proprietaire ? depot.proprietaire.name : 'N/A',
                        depot: depot.depot ? depot.depot.nom : 'N/A',
                        sacs_pleins: depot.sacs_pleins || 0,
                        sacs_demi: depot.sacs_demi || 0,
                        poids_entree_kg: this.formatNumber(depot.poids_entree_kg || 0),
                        poids_sortie_kg: depot.poids_sortie_kg ? this.formatNumber(depot.poids_sortie_kg) : null,
                        reste_kg: this.formatNumber(depot.reste_kg || 0),
                        prix_marche_actuel_mga: depot.prix_marche_actuel_mga || 0,
                        decision_proprietaire: depot.decision_proprietaire || '',
                        observation: depot.observation || ''
                    };
                    this.showDetailsModal = true;
                },

                fermerDetails() {
                    this.showDetailsModal = false;
                    this.details = {};
                },

                formatNumber(n) {
                    return n ? parseFloat(n).toFixed(2).replace('.', ',') : '0,00';
                },

                formatDate(iso) {
                    if (!iso) return 'N/A';
                    const d = new Date(iso);
                    const pad = (v) => String(v).padStart(2, '0');
                    return `${pad(d.getDate())}/${pad(d.getMonth()+1)}/${d.getFullYear()}`;
                }
            }
        }
    </script>
</div>