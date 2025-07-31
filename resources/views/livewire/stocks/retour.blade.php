{{-- resources/views/livewire/stocks/retour.blade.php --}}
<div>
    <!-- Statistiques du stock de retour -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-6">
        <!-- Carte 1: Total en stock -->
        <div class="bg-white overflow-hidden shadow-lg rounded-lg border-l-4 border-orange-500">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-orange-500 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Stock Retours</dt>
                            <dd class="text-2xl font-semibold text-gray-900">{{ $stats['total_stock'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Carte 2: Valeur du stock -->
        <div class="bg-white overflow-hidden shadow-lg rounded-lg border-l-4 border-blue-500">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Valeur Stock</dt>
                            <dd class="text-2xl font-semibold text-gray-900">
                                {{ number_format($stats['valeur_stock'], 0, ',', ' ') }} Ar
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Carte 3: Vendus ce mois -->
        <div class="bg-white overflow-hidden shadow-lg rounded-lg border-l-4 border-green-500">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Vendus ce mois</dt>
                            <dd class="text-2xl font-semibold text-gray-900">{{ $stats['vendus_mois'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Carte 4: CA Retours -->
        <div class="bg-white overflow-hidden shadow-lg rounded-lg border-l-4 border-purple-500">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">CA Retours</dt>
                            <dd class="text-2xl font-semibold text-gray-900">
                                {{ number_format($stats['ca_retours'], 0, ',', ' ') }} Ar
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Barre de recherche et filtres -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-4 py-5 sm:p-6">
            <div class="sm:flex sm:items-center sm:justify-between">
                <div class="flex-1 min-w-0">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-4">
                        <div class="flex-1">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <input wire:model.live="search" type="text"
                                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                                    placeholder="Rechercher par référence ou produit...">
                            </div>
                        </div>
                        <div class="mt-3 sm:mt-0">
                            <select wire:model.live="filterStatut"
                                class="block w-full pl-3 pr-10 py-3 text-base border border-gray-300 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 sm:text-sm rounded-lg">
                                <option value="">Tous les statuts</option>
                                <option value="en_stock">En stock</option>
                                <option value="vendu">Vendu</option>
                                <option value="perdu">Perdu</option>
                            </select>
                        </div>
                        <div class="mt-3 sm:mt-0">
                            <button wire:click="openModal" 
                                class="inline-flex items-center px-4 py-3 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                                Ajouter un retour
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau des retours -->
    <div class="bg-white shadow-lg overflow-hidden rounded-lg">
        <div class="px-6 py-5 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Gestion des retours</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">
                        Liste des produits retournés et leur statut actuel
                    </p>
                </div>
                <div class="flex items-center">
                    <span class="text-sm text-gray-500 mr-2">{{ $retours->total() }} résultats</span>
                    <select wire:model.live="perPage" 
                        class="block pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 sm:text-sm rounded-lg">
                        <option value="10">10/page</option>
                        <option value="25">25/page</option>
                        <option value="50">50/page</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th wire:click="sortBy('reference')" class="cursor-pointer px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hover:bg-gray-100">
                            Référence
                            @if($sortField === 'reference')
                                @if($sortDirection === 'asc') ↑ @else ↓ @endif
                            @endif
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Produit
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Lieu de stockage
                        </th>
                        <th wire:click="sortBy('quantite_kg')" class="cursor-pointer px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hover:bg-gray-100">
                            Quantité
                            @if($sortField === 'quantite_kg')
                                @if($sortDirection === 'asc') ↑ @else ↓ @endif
                            @endif
                        </th>
                        <th wire:click="sortBy('valeur_totale_mga')" class="cursor-pointer px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hover:bg-gray-100">
                            Valeur
                            @if($sortField === 'valeur_totale_mga')
                                @if($sortDirection === 'asc') ↑ @else ↓ @endif
                            @endif
                        </th>
                        <th wire:click="sortBy('statut')" class="cursor-pointer px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hover:bg-gray-100">
                            Statut
                            @if($sortField === 'statut')
                                @if($sortDirection === 'asc') ↑ @else ↓ @endif
                            @endif
                        </th>
                     
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($retours as $retour)
                                                                <tr class="hover:bg-orange-50 transition-colors duration-150">
                                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-orange-600">
                                                                        {{ $retour->reference }}
                                                                    </td>
                                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                                        <div class="text-sm font-medium text-gray-900">
                                                                        {{ $retour->produit?->nom ?? 'Produit inconnu' }}
                                                                        </div>
                                                                        <div class="text-sm text-gray-500">
                                                                            {{ number_format($retour->prix_unitaire_mga, 0, ',', ' ') }} Ar/kg
                                                                        </div>
                                                                    </td>
                                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                                        {{ $retour->lieuLivraison->nom ?? 'N/A' }}
                                                                    </td>
                                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                                        <div class="text-sm font-medium text-gray-900">
                                                                            {{ number_format($retour->poids_arrivee_kg, 0, ',', ' ') }} kg
                                                                        </div>
                                                                        <div class="text-xs text-gray-500">
                                                                            {{ $retour->sacs_pleins_arrivee }} Sac + {{ $retour->sacs_demi_arrivee }}½ Sac
                                                                        </div>
                                                                    </td>
                                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                                        {{ number_format($retour->montant_total_mga, 0, ',', ' ') }} Ar
                                                                    </td>
                                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                                        @php
                        $statusClasses = [
                            'en_stock' => 'bg-blue-100 text-blue-800',
                            'vendu' => 'bg-green-100 text-green-800',
                            'perdu' => 'bg-red-100 text-red-800'
                        ];
                                                                        @endphp
                                                                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $statusClasses[$retour->statut] }}">
                                                                            {{ ucfirst(str_replace('_', ' ', $retour->statut)) }}
                                                                        </span>
                                                                    </td>

                                                                </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun retour enregistré</h3>
                                    <p class="text-gray-500">Commencez par ajouter un retour en cliquant sur le bouton ci-dessus</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="bg-white px-6 py-4 flex items-center justify-between border-t border-gray-200">
            {{ $retours->links() }}
        </div>
    </div>

    <!-- Modal de gestion des retours -->
    @if($showModal)
        <div class="fixed inset-0 overflow-y-auto z-50">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>
                
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                            {{ $editingRetour ? 'Modifier un retour' : 'Ajouter un retour' }}
                        </h3>
                        
                        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                            <!-- Référence -->
                            <div class="sm:col-span-3">
                                <label for="reference" class="block text-sm font-medium text-gray-700">Référence</label>
                                <input type="text" wire:model="reference" id="reference"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm">
                            </div>
                            
                            <!-- Produit -->
                            <div class="sm:col-span-3">
                                <label for="produit_id" class="block text-sm font-medium text-gray-700">Produit</label>
                                <select wire:model="produit_id" id="produit_id"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm">
                                    <option value="">Sélectionnez un produit</option>
                                    @foreach($produits as $produit)
                                        <option value="{{ $produit->id }}">{{ $produit->nom }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <!-- Lieu de stockage -->
                            <div class="sm:col-span-3">
                                <label for="lieu_stockage_id" class="block text-sm font-medium text-gray-700">Lieu de stockage</label>
                                <select wire:model="lieu_stockage_id" id="lieu_stockage_id"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm">
                                    <option value="">Sélectionnez un lieu</option>
                                    @foreach($lieux as $lieu)
                                        <option value="{{ $lieu->id }}">{{ $lieu->nom }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <!-- Statut -->
                            <div class="sm:col-span-3">
                                <label for="statut" class="block text-sm font-medium text-gray-700">Statut</label>
                                <select wire:model="statut" id="statut"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm">
                                    <option value="en_stock">En stock</option>
                                    <option value="vendu">Vendu</option>
                                    <option value="perdu">Perdu</option>
                                </select>
                            </div>
                            
                            <!-- Quantités -->
                            <div class="sm:col-span-2">
                                <label for="quantite_kg" class="block text-sm font-medium text-gray-700">Quantité (kg)</label>
                                <input type="number" wire:model="quantite_kg" id="quantite_kg"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm">
                            </div>
                            
                            <div class="sm:col-span-2">
                                <label for="quantite_sacs_pleins" class="block text-sm font-medium text-gray-700">Sacs pleins</label>
                                <input type="number" wire:model="quantite_sacs_pleins" id="quantite_sacs_pleins"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm">
                            </div>
                            
                            <div class="sm:col-span-2">
                                <label for="quantite_sacs_demi" class="block text-sm font-medium text-gray-700">Sacs demi</label>
                                <input type="number" wire:model="quantite_sacs_demi" id="quantite_sacs_demi"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm">
                            </div>
                            
                            <!-- Prix et valeur -->
                            <div class="sm:col-span-3">
                                <label for="prix_unitaire_mga" class="block text-sm font-medium text-gray-700">Prix unitaire (Ar)</label>
                                <input type="number" wire:model="prix_unitaire_mga" id="prix_unitaire_mga"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm">
                            </div>
                            
                            <div class="sm:col-span-3">
                                <label class="block text-sm font-medium text-gray-700">Valeur totale (Ar)</label>
                                <div class="mt-1 p-2 border border-gray-300 rounded-md bg-gray-50">
                                    {{ number_format($valeur_totale_mga, 0, ',', ' ') }} Ar
                                </div>
                            </div>
                            
                            <!-- Motif et observation -->
                            <div class="sm:col-span-6">
                                <label for="motif_retour" class="block text-sm font-medium text-gray-700">Motif du retour</label>
                                <textarea wire:model="motif_retour" id="motif_retour" rows="2"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm"></textarea>
                            </div>
                            
                            <div class="sm:col-span-6">
                                <label for="observation" class="block text-sm font-medium text-gray-700">Observation</label>
                                <textarea wire:model="observation" id="observation" rows="2"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm"></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="saveRetour" type="button"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-orange-600 text-base font-medium text-white hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Enregistrer
                        </button>
                        <button wire:click="closeModal" type="button"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Annuler
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>