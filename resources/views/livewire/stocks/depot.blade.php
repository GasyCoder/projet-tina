{{-- resources/views/livewire/stocks/depot.blade.php --}}
<div>
    <!-- Statistiques du dépôt -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-6">
        <!-- Stock total -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-indigo-500 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Stock total</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ number_format($stockTotal ?? 15420, 0, ',', ' ') }} kg</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Produits différents -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Produits</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $nombreProduits ?? '8' }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stock critique -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-red-500 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.996-.833-2.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Stock critique</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stockCritique ?? '2' }}</dd>
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
                        <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Valeur stock</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ number_format($valeurStock ?? 8547200, 0, ',', ' ') }} Ar</dd>
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
                                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <input wire:model.live="search" type="text" 
                                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="Rechercher un produit...">
                            </div>
                        </div>
                        
                        <!-- Filtre par catégorie -->
                        <div class="mt-3 sm:mt-0">
                            <select wire:model.live="filterCategorie" 
                                class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="">Toutes catégories</option>
                                <option value="cereales">Céréales</option>
                                <option value="legumineuses">Légumineuses</option>
                                <option value="tubercules">Tubercules</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="mt-5 sm:mt-0 sm:ml-6 sm:flex-shrink-0 sm:flex sm:items-center space-x-3">
                    <button wire:click="$toggle('showAjustementModal')" type="button" 
                        class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Ajustement
                    </button>
                    <button wire:click="$toggle('showEntreeModal')" type="button" 
                        class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Entrée Stock
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Deux colonnes : Stock + Historique -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Colonne Stock (2/3) -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow overflow-hidden sm:rounded-md">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Niveaux de stock</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">État actuel des stocks par produit</p>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produit</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catégorie</th>
                                <th wire:click="sortBy('stock_actuel')" class="cursor-pointer px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hover:bg-gray-100">
                                    Stock actuel
                                    @if($sortField == 'stock_actuel')
                                        <span class="ml-1">{{ $sortDirection == 'asc' ? '↑' : '↓' }}</span>
                                    @endif
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Seuil min</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prix/kg</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($stocks ?? $this->getDummyStocks() as $stock)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 flex-shrink-0">
                                                <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                                    <span class="text-sm font-medium text-gray-700">{{ substr($stock['nom'], 0, 1) }}</span>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $stock['nom'] }}</div>
                                                <div class="text-sm text-gray-500">{{ $stock['unite'] ?? 'kg' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ ucfirst($stock['categorie']) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ number_format($stock['stock_actuel'], 0, ',', ' ') }} kg
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ number_format($stock['seuil_min'], 0, ',', ' ') }} kg
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $pourcentage = $stock['seuil_min'] > 0 ? ($stock['stock_actuel'] / $stock['seuil_min']) * 100 : 100;
                                            if ($pourcentage <= 50) {
                                                $statutColor = 'bg-red-100 text-red-800';
                                                $statut = 'Critique';
                                            } elseif ($pourcentage <= 100) {
                                                $statutColor = 'bg-yellow-100 text-yellow-800';
                                                $statut = 'Bas';
                                            } else {
                                                $statutColor = 'bg-green-100 text-green-800';
                                                $statut = 'Normal';
                                            }
                                        @endphp
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statutColor }}">
                                            {{ $statut }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ number_format($stock['prix_unitaire'], 0, ',', ' ') }} Ar
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <button wire:click="ajusterStock({{ $stock['id'] }})" class="text-blue-600 hover:text-blue-900">Ajuster</button>
                                            <button wire:click="voirHistorique({{ $stock['id'] }})" class="text-green-600 hover:text-green-900">Historique</button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                                        Aucun produit en stock
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Colonne Historique (1/3) -->
        <div class="lg:col-span-1">
            <div class="bg-white shadow overflow-hidden sm:rounded-md">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Historique récent</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">Derniers mouvements de stock</p>
                </div>
                
                <div class="flow-root">
                    <ul class="divide-y divide-gray-200">
                        @forelse($historique ?? $this->getDummyHistorique() as $mouvement)
                            <li class="py-4 px-4">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        @if($mouvement['type'] === 'entree')
                                            <div class="h-8 w-8 rounded-full bg-green-100 flex items-center justify-center">
                                                <svg class="h-4 w-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                </svg>
                                            </div>
                                        @else
                                            <div class="h-8 w-8 rounded-full bg-red-100 flex items-center justify-center">
                                                <svg class="h-4 w-4 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ $mouvement['produit'] }}
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            {{ $mouvement['type'] === 'entree' ? '+' : '-' }}{{ number_format($mouvement['quantite'], 0, ',', ' ') }} kg
                                        </p>
                                        <p class="text-xs text-gray-400">
                                            {{ \Carbon\Carbon::parse($mouvement['date'])->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li class="py-4 px-4 text-center text-sm text-gray-500">
                                Aucun mouvement récent
                            </li>
                        @endforelse
                    </ul>
                </div>
                
                <div class="px-4 py-3">
                    <button class="w-full text-center text-sm text-indigo-600 hover:text-indigo-900">
                        Voir tout l'historique
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal d'entrée de stock -->
    @if($showEntreeModal ?? false)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="$toggle('showEntreeModal')"></div>
                
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                                    Entrée de stock
                                </h3>
                                
                                <form wire:submit.prevent="saveEntree" class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Produit</label>
                                        <select wire:model="formEntree.produit_id" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                            <option value="">Sélectionner un produit...</option>
                                            <option value="1">Riz local</option>
                                            <option value="2">Maïs jaune</option>
                                            <option value="3">Haricot rouge</option>
                                            <option value="4">Patate douce</option>
                                        </select>
                                    </div>
                                    
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Quantité (kg)</label>
                                            <input wire:model="formEntree.quantite" type="number" step="0.01"
                                                class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                        </div>
                                        
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Prix unitaire (Ar)</label>
                                            <input wire:model="formEntree.prix_unitaire" type="number"
                                                class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Fournisseur</label>
                                        <input wire:model="formEntree.fournisseur" type="text"
                                            class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Notes</label>
                                        <textarea wire:model="formEntree.notes" rows="2"
                                            class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                            placeholder="Notes sur la livraison..."></textarea>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="saveEntree" type="button" 
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Enregistrer
                        </button>
                        <button wire:click="$toggle('showEntreeModal')" type="button" 
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Annuler
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal d'ajustement -->
    @if($showAjustementModal ?? false)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="$toggle('showAjustementModal')"></div>
                
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                                    Ajustement de stock
                                </h3>
                                
                                <form wire:submit.prevent="saveAjustement" class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Produit</label>
                                        <select wire:model="formAjustement.produit_id" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-yellow-500 focus:border-yellow-500">
                                            <option value="">Sélectionner un produit...</option>
                                            <option value="1">Riz local</option>
                                            <option value="2">Maïs jaune</option>
                                            <option value="3">Haricot rouge</option>
                                        </select>
                                    </div>
                                    
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Stock actuel</label>
                                            <input type="text" value="2,450 kg" disabled
                                                class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 bg-gray-100 text-gray-500">
                                        </div>
                                        
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Nouveau stock (kg)</label>
                                            <input wire:model="formAjustement.nouveau_stock" type="number" step="0.01"
                                                class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-yellow-500 focus:border-yellow-500">
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Motif de l'ajustement</label>
                                        <select wire:model="formAjustement.motif" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-yellow-500 focus:border-yellow-500">
                                            <option value="">Sélectionner...</option>
                                            <option value="inventaire">Inventaire physique</option>
                                            <option value="perte">Perte/Avarie</option>
                                            <option value="erreur">Correction d'erreur</option>
                                            <option value="autre">Autre</option>
                                        </select>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Commentaire</label>
                                        <textarea wire:model="formAjustement.commentaire" rows="2"
                                            class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-yellow-500 focus:border-yellow-500"
                                            placeholder="Expliquez la raison de l'ajustement..."></textarea>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="saveAjustement" type="button" 
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-yellow-600 text-base font-medium text-white hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Ajuster
                        </button>
                        <button wire:click="$toggle('showAjustementModal')" type="button" 
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Annuler
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@script
<script>
    // Méthodes pour générer des données factices
    function getDummyStocks() {
        return [
            ['id' => 1, 'nom' => 'Riz local', 'categorie' => 'cereales', 'stock_actuel' => 2450, 'seuil_min' => 1000, 'prix_unitaire' => 3500],
            ['id' => 2, 'nom' => 'Maïs jaune', 'categorie' => 'cereales', 'stock_actuel' => 850, 'seuil_min' => 800, 'prix_unitaire' => 2800],
            ['id' => 3, 'nom' => 'Haricot rouge', 'categorie' => 'legumineuses', 'stock_actuel' => 320, 'seuil_min' => 500, 'prix_unitaire' => 4200],
            ['id' => 4, 'nom' => 'Patate douce', 'categorie' => 'tubercules', 'stock_actuel' => 1200, 'seuil_min' => 600, 'prix_unitaire' => 1800],
            ['id' => 5, 'nom' => 'Haricot blanc', 'categorie' => 'legumineuses', 'stock_actuel' => 180, 'seuil_min' => 400, 'prix_unitaire' => 3800],
        ];
    }

    function getDummyHistorique() {
        return [
            ['produit' => 'Riz local', 'type' => 'entree', 'quantite' => 500, 'date' => '2024-01-15 14:30:00'],
            ['produit' => 'Maïs jaune', 'type' => 'sortie', 'quantite' => 150, 'date' => '2024-01-15 11:20:00'],
            ['produit' => 'Haricot rouge', 'type' => 'sortie', 'quantite' => 80, 'date' => '2024-01-14 16:45:00'],
            ['produit' => 'Patate douce', 'type' => 'entree', 'quantite' => 300, 'date' => '2024-01-14 09:15:00'],
            ['produit' => 'Riz local', 'type' => 'sortie', 'quantite' => 200, 'date' => '2024-01-13 15:00:00'],
        ];
    }
</script>
@endscript