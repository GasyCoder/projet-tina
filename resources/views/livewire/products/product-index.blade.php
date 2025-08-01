<div class="min-h-screen bg-gray-50 pt-20 pb-6 px-4 md:px-6">
    <div class="space-y-6 max-w-7xl mx-auto">
        <!-- En-tête -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Produits</h1>
                <p class="text-sm text-gray-600">Gérez vos produits de transport</p>
            </div>
            <button wire:click="create" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Nouveau produit
            </button>
        </div>

        <!-- Alertes -->
        @if (session()->has('success'))
            <div class="bg-green-50 border border-green-200 rounded-md p-4">
                <div class="flex">
                    <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                    </svg>
                    <p class="ml-3 text-sm text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <!-- Recherche -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-4 md:p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
                    <div class="flex-1 max-w-lg">
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input 
                                wire:model.live.debounce.300ms="search"
                                type="text"
                                placeholder="Rechercher un produit..."
                                class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                            >
                        </div>
                    </div>
                    <div class="text-sm text-gray-500 whitespace-nowrap">
                        {{ $produits->total() }} produit(s) trouvé(s)
                    </div>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th wire:click="sortBy('nom')" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                                <div class="flex items-center gap-1">
                                    <span>Nom</span>
                                    @if($sortField === 'nom')
                                        <svg class="w-4 h-4 {{ $sortDirection === 'asc' ? 'rotate-0' : 'rotate-180' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h6a1 1 0 110 2H4a1 1 0 01-1-1zM3 16a1 1 0 011-1h4a1 1 0 110 2H4a1 1 0 01-1-1z"/>
                                        </svg>
                                    @endif
                                </div>
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">Variété</th>
                            <th wire:click="sortBy('qte_variable')" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                                <div class="flex items-center gap-1">
                                    <span>📦 Stock</span>
                                    @if($sortField === 'qte_variable')
                                        <svg class="w-4 h-4 {{ $sortDirection === 'asc' ? 'rotate-0' : 'rotate-180' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h6a1 1 0 110 2H4a1 1 0 01-1-1zM3 16a1 1 0 011-1h4a1 1 0 110 2H4a1 1 0 01-1-1z"/>
                                        </svg>
                                    @endif
                                </div>
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">Capacité</th>
                            <th wire:click="sortBy('prix_reference_mga')" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 hidden lg:table-cell">
                                <div class="flex items-center gap-1">
                                    <span>Prix réf.</span>
                                    @if($sortField === 'prix_reference_mga')
                                        <svg class="w-4 h-4 {{ $sortDirection === 'asc' ? 'rotate-0' : 'rotate-180' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h6a1 1 0 110 2H4a1 1 0 01-1-1zM3 16a1 1 0 011-1h4a1 1 0 110 2H4a1 1 0 01-1-1z"/>
                                        </svg>
                                    @endif
                                </div>
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($produits as $produit)
                            @php
                                $stockActuel = floatval($produit->qte_variable ?? 0);
                                $capaciteMax = floatval($produit->poids_moyen_sac_kg_max ?? 0);
                                $pourcentageStock = $capaciteMax > 0 ? ($stockActuel / $capaciteMax) * 100 : 0;
                                
                                // Déterminer les couleurs selon le niveau de stock
                                $stockStatus = '';
                                $stockColor = '';
                                $stockBgColor = '';
                                
                                if ($stockActuel <= 0) {
                                    $stockStatus = 'Vide';
                                    $stockColor = 'text-red-700';
                                    $stockBgColor = 'bg-red-50';
                                } elseif ($pourcentageStock <= 20) {
                                    $stockStatus = 'Critique';
                                    $stockColor = 'text-red-600';
                                    $stockBgColor = 'bg-red-50';
                                } elseif ($pourcentageStock <= 50) {
                                    $stockStatus = 'Faible';
                                    $stockColor = 'text-orange-600';
                                    $stockBgColor = 'bg-orange-50';
                                } elseif ($pourcentageStock <= 80) {
                                    $stockStatus = 'Correct';
                                    $stockColor = 'text-blue-600';
                                    $stockBgColor = 'bg-blue-50';
                                } else {
                                    $stockStatus = 'Plein';
                                    $stockColor = 'text-green-600';
                                    $stockBgColor = 'bg-green-50';
                                }
                            @endphp
                            
                            <tr class="hover:bg-gray-50 {{ $stockActuel <= 0 ? 'bg-red-25' : '' }}">
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900 flex items-center">
                                            {{ $produit->nom }}
                                            @if($stockActuel <= 0)
                                                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    ⚠️ Stock vide
                                                </span>
                                            @elseif($pourcentageStock <= 20)
                                                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                    🔻 Stock bas
                                                </span>
                                            @endif
                                        </div>
                                        @if($produit->description)
                                            <div class="text-xs text-gray-500 sm:hidden mt-1">{{ Str::limit($produit->description, 30) }}</div>
                                        @endif
                                        <div class="text-xs text-gray-500 sm:hidden mt-1">
                                            @if($produit->variete) 
                                                {{ $produit->variete }} • 
                                            @endif
                                            Stock: {{ number_format($stockActuel, 1) }}/{{ number_format($capaciteMax, 1) }} {{ $produit->unite }}
                                        </div>
                                    </div>
                                </td>
                                
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 hidden sm:table-cell">
                                    {{ $produit->variete ?: '-' }}
                                </td>
                                
                                <!-- Colonne Stock Actuel -->
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <div class="flex items-center justify-between mb-1">
                                            <span class="text-sm font-medium {{ $stockColor }}">
                                                {{ number_format($stockActuel, 1) }} {{ $produit->unite }}
                                            </span>
                                            <span class="text-xs text-gray-500">
                                                {{ number_format($pourcentageStock, 0) }}%
                                            </span>
                                        </div>
                                        
                                        <!-- Barre de progression du stock -->
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="h-2 rounded-full {{ $stockActuel <= 0 ? 'bg-gray-300' : ($pourcentageStock <= 20 ? 'bg-red-500' : ($pourcentageStock <= 50 ? 'bg-orange-500' : ($pourcentageStock <= 80 ? 'bg-blue-500' : 'bg-green-500'))) }}" 
                                                 style="width: {{ min(100, $pourcentageStock) }}%"></div>
                                        </div>
                                        
                                        <div class="flex justify-between text-xs text-gray-400 mt-1">
                                            <span>0</span>
                                            <span class="text-xs {{ $stockColor }} font-medium">{{ $stockStatus }}</span>
                                            <span>{{ number_format($capaciteMax, 0) }}</span>
                                        </div>
                                    </div>
                                </td>
                                
                                <!-- Colonne Capacité Maximum -->
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 hidden md:table-cell">
                                    <div class="flex flex-col">
                                        <span class="font-medium">{{ number_format($capaciteMax, 1) }} {{ $produit->unite }}</span>
                                        @if($capaciteMax > 0)
                                            <span class="text-xs text-gray-500">
                                                Libre: {{ number_format($capaciteMax - $stockActuel, 1) }} {{ $produit->unite }}
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 hidden lg:table-cell">
                                    @if($produit->prix_reference_mga)
                                        <div class="flex flex-col">
                                            <span class="font-medium">{{ number_format($produit->prix_reference_mga, 0, ',', ' ') }}</span>
                                            <span class="text-gray-500 text-xs">MGA/{{ $produit->unite }}</span>
                                        </div>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="flex flex-col gap-1">
                                        <button wire:click="toggleActif({{ $produit->id }})" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                            {{ $produit->actif ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $produit->actif ? 'Actif' : 'Inactif' }}
                                        </button>
                                        
                                        <!-- Indicateur de niveau de stock -->
                                        @if($produit->actif)
                                            <span class="inline-flex px-2 py-0.5 text-xs rounded-full {{ $stockColor }} {{ $stockBgColor }}">
                                                @if($stockActuel <= 0)
                                                    🔴 Vide
                                                @elseif($pourcentageStock <= 20)
                                                    🟠 Critique
                                                @elseif($pourcentageStock <= 50)
                                                    🟡 Faible
                                                @elseif($pourcentageStock <= 80)
                                                    🔵 Correct
                                                @else
                                                    🟢 Plein
                                                @endif
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                
                                <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        <!-- Bouton ajustement rapide du stock -->
                                        <button wire:click="ajusterStock({{ $produit->id }})" 
                                                class="text-purple-600 hover:text-purple-900 p-1 rounded-full hover:bg-purple-100" 
                                                title="Ajuster le stock">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2M7 4h10l.94 10.34A2 2 0 0116 16H8a2 2 0 01-1.94-1.66L7 4zM5 9h14M9 9v7m6-7v7"/>
                                            </svg>
                                        </button>
                                        
                                        <button wire:click="edit({{ $produit->id }})" 
                                                class="text-blue-600 hover:text-blue-900 p-1 rounded-full hover:bg-blue-100" 
                                                title="Modifier">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>
                                        
                                        <button 
                                            wire:click="delete({{ $produit->id }})"
                                            wire:confirm="Êtes-vous sûr de vouloir supprimer ce produit ?"
                                            class="text-red-600 hover:text-red-900 p-1 rounded-full hover:bg-red-100" 
                                            title="Supprimer">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="text-gray-500">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-4.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 009.586 13H7"/>
                                        </svg>
                                        <p class="mt-4 text-lg font-medium">Aucun produit trouvé</p>
                                        <p class="mt-2">Commencez par créer votre premier produit</p>
                                        <button wire:click="create" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                            </svg>
                                            Créer un produit
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($produits->hasPages())
                <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    {{ $produits->links() }}
                </div>
            @endif
        </div>

        <!-- Légende des niveaux de stock -->
        <div class="mt-4 bg-white rounded-lg shadow p-4">
            <h4 class="text-sm font-medium text-gray-900 mb-3">📊 Légende des niveaux de stock</h4>
            <div class="grid grid-cols-2 md:grid-cols-5 gap-3 text-xs">
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-gray-300 rounded-full mr-2"></div>
                    <span class="text-gray-600">🔴 Vide (0%)</span>
                </div>
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-red-500 rounded-full mr-2"></div>
                    <span class="text-red-600">🟠 Critique (≤20%)</span>
                </div>
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-orange-500 rounded-full mr-2"></div>
                    <span class="text-orange-600">🟡 Faible (≤50%)</span>
                </div>
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-blue-500 rounded-full mr-2"></div>
                    <span class="text-blue-600">🔵 Correct (≤80%)</span>
                </div>
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                    <span class="text-green-600">🟢 Plein (>80%)</span>
                </div>
            </div>
        </div>

        <!-- Modal -->
        @if($showModal)
            <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeModal"></div>

                    <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                        <form wire:submit="save">
                            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                <div class="flex items-center mb-4">
                                    <h3 class="text-lg font-medium text-gray-900">
                                        {{ $editingProduit ? 'Modifier' : 'Créer' }} un produit
                                    </h3>
                                </div>

                                <div class="space-y-4">
                                    <!-- Nom -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Nom *</label>
                                        <input 
                                            wire:model="nom"
                                            type="text"
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 text-sm"
                                            placeholder="TSOROKO, BEB, Haricot..."
                                        >
                                        @error('nom') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                    </div>

                                    <!-- Variété -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Variété</label>
                                        <input 
                                            wire:model="variete"
                                            type="text"
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 text-sm"
                                            placeholder="Premium, Rouge, Blanc..."
                                        >
                                        @error('variete') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <!-- Unité -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Unité *</label>
                                            <select wire:model="unite" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                                <option value="sacs">Sacs</option>
                                                <option value="kg">Kg</option>
                                                <option value="tonnes">Tonnes</option>
                                                <option value="boites">Boites</option>
                                                <option value="cartons">Cartons</option>
                                            </select>
                                            @error('unite') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                        </div>

                                        <!-- Poids moyen -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Poids/sac (kg) *</label>
                                            <input 
                                                wire:model="poids_moyen_sac_kg_max"
                                                type="number"
                                                step="0.1"
                                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 text-sm"
                                            >
                                            @error('poids_moyen_sac_kg_max') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                        </div>
                                    </div>

                                    <!-- Prix de référence -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Prix de référence (MGA)</label>
                                        <input 
                                            wire:model="prix_reference_mga"
                                            type="number"
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 text-sm"
                                            placeholder="3600"
                                        >
                                        @error('prix_reference_mga') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                    </div>

                                    <!-- Description -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Description</label>
                                        <textarea 
                                            wire:model="description"
                                            rows="3"
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 text-sm"
                                            placeholder="Description optionnelle..."
                                        ></textarea>
                                        @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                    </div>

                                    <!-- Actif -->
                                    <div class="flex items-center">
                                        <input 
                                            wire:model="actif"
                                            type="checkbox"
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                        >
                                        <label class="ml-2 block text-sm text-gray-900">Produit actif</label>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                                    {{ $editingProduit ? 'Modifier' : 'Créer' }}
                                </button>
                                <button type="button" wire:click="closeModal" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                    Annuler
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>