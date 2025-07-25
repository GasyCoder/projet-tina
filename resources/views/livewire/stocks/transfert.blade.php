{{-- resources/views/livewire/stocks/transfert.blade.php --}}
<div>
    <!-- Statistiques des transferts -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-6">
        <!-- Transferts du mois -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Transferts du mois</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $transfertsMois ?? '15' }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- En préparation -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">En préparation</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $transfertsPreparation ?? '4' }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- En transit -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-orange-500 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">En transit</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $transfertsTransit ?? '6' }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reçus -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Reçus</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $transfertsRecus ?? '5' }}</dd>
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
                                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Rechercher un transfert...">
                            </div>
                        </div>
                        
                        <!-- Filtre par statut -->
                        <div class="mt-3 sm:mt-0">
                            <select wire:model.live="filterStatus" 
                                class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                <option value="">Tous les statuts</option>
                                <option value="en_preparation">En préparation</option>
                                <option value="en_transit">En transit</option>
                                <option value="recu">Reçu</option>
                                <option value="annule">Annulé</option>
                            </select>
                        </div>

                        <!-- Filtre par dépôt -->
                        <div class="mt-3 sm:mt-0">
                            <select wire:model.live="filterDepot" 
                                class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                <option value="">Tous les dépôts</option>
                                <option value="depot_central">Dépôt Central</option>
                                <option value="depot_nord">Dépôt Nord</option>
                                <option value="depot_sud">Dépôt Sud</option>
                                <option value="depot_est">Dépôt Est</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="mt-5 sm:mt-0 sm:ml-6 sm:flex-shrink-0 sm:flex sm:items-center">
                    <button wire:click="$toggle('showModal')" type="button" 
                        class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Nouveau Transfert
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau des transferts -->
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Liste des transferts</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Tous les transferts entre dépôts</p>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th wire:click="sortBy('numero')" class="cursor-pointer px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hover:bg-gray-100">
                            N° Transfert
                            @if($sortField == 'numero')
                                <span class="ml-1">{{ $sortDirection == 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th wire:click="sortBy('date')" class="cursor-pointer px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hover:bg-gray-100">
                            Date
                            @if($sortField == 'date')
                                <span class="ml-1">{{ $sortDirection == 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Origine</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Destination</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produits</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Véhicule</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($transferts ?? $this->getDummyTransferts() as $transfert)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-600">
                                #{{ $transfert['numero'] ?? 'T' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ \Carbon\Carbon::parse($transfert['date'] ?? now())->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div class="flex items-center">
                                    <div class="h-2 w-2 bg-blue-400 rounded-full mr-2"></div>
                                    {{ $transfert['depot_origine'] ?? 'Dépôt Central' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div class="flex items-center">
                                    <div class="h-2 w-2 bg-green-400 rounded-full mr-2"></div>
                                    {{ $transfert['depot_destination'] ?? 'Dépôt Nord' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                <div class="max-w-xs">
                                    @php
                                        $produits = $transfert['produits'] ?? ['Riz: 500kg', 'Maïs: 200kg'];
                                    @endphp
                                    @foreach(array_slice($produits, 0, 2) as $produit)
                                        <div class="text-xs text-gray-600">{{ $produit }}</div>
                                    @endforeach
                                    @if(count($produits) > 2)
                                        <div class="text-xs text-blue-600">+{{ count($produits) - 2 }} autres</div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $transfert['vehicule'] ?? 'Camion ABC-123' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statuts = ['en_preparation', 'en_transit', 'recu', 'annule'];
                                    $statut = $transfert['statut'] ?? $statuts[array_rand($statuts)];
                                    $colors = [
                                        'en_preparation' => 'bg-yellow-100 text-yellow-800',
                                        'en_transit' => 'bg-orange-100 text-orange-800',
                                        'recu' => 'bg-green-100 text-green-800',
                                        'annule' => 'bg-red-100 text-red-800'
                                    ];
                                    $labels = [
                                        'en_preparation' => 'En préparation',
                                        'en_transit' => 'En transit',
                                        'recu' => 'Reçu',
                                        'annule' => 'Annulé'
                                    ];
                                @endphp
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $colors[$statut] }}">
                                    {{ $labels[$statut] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    @if($statut === 'en_preparation')
                                        <button wire:click="demarrerTransfert({{ $transfert['id'] ?? rand(1, 100) }})" 
                                            class="text-blue-600 hover:text-blue-900">Démarrer</button>
                                    @endif
                                    @if($statut === 'en_transit')
                                        <button wire:click="confirmerReception({{ $transfert['id'] ?? rand(1, 100) }})" 
                                            class="text-green-600 hover:text-green-900">Confirmer</button>
                                    @endif
                                    <button wire:click="voirDetails({{ $transfert['id'] ?? rand(1, 100) }})" 
                                        class="text-indigo-600 hover:text-indigo-900">Détails</button>
                                    @if($statut === 'en_preparation')
                                        <button wire:click="annulerTransfert({{ $transfert['id'] ?? rand(1, 100) }})" 
                                            class="text-red-600 hover:text-red-900">Annuler</button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">
                                Aucun transfert trouvé
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
            <div class="flex-1 flex justify-between sm:hidden">
                <button class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Précédent
                </button>
                <button class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Suivant
                </button>
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700">
                        Affichage de <span class="font-medium">1</span> à <span class="font-medium">10</span> sur <span class="font-medium">{{ $totalTransferts ?? 18 }}</span> résultats
                    </p>
                </div>
                <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        <button class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                            <span class="sr-only">Précédent</span>
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <button class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">1</button>
                        <button class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-blue-50 text-sm font-medium text-blue-600">2</button>
                        <button class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">3</button>
                        <button class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                            <span class="sr-only">Suivant</span>
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de création de transfert -->
    @if($showModal ?? false)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="$toggle('showModal')"></div>
                
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                                    Nouveau transfert
                                </h3>
                                
                                <form wire:submit.prevent="save" class="space-y-4">
                                    <!-- Informations du transfert -->
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Dépôt d'origine</label>
                                            <select wire:model="form.depot_origine" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                                <option value="">Sélectionner...</option>
                                                <option value="depot_central">Dépôt Central</option>
                                                <option value="depot_nord">Dépôt Nord</option>
                                                <option value="depot_sud">Dépôt Sud</option>
                                                <option value="depot_est">Dépôt Est</option>
                                            </select>
                                        </div>
                                        
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Dépôt de destination</label>
                                            <select wire:model="form.depot_destination" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                                <option value="">Sélectionner...</option>
                                                <option value="depot_central">Dépôt Central</option>
                                                <option value="depot_nord">Dépôt Nord</option>
                                                <option value="depot_sud">Dépôt Sud</option>
                                                <option value="depot_est">Dépôt Est</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Date prévue</label>
                                            <input wire:model="form.date_prevue" type="datetime-local"
                                                class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                        </div>
                                        
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Véhicule</label>
                                            <select wire:model="form.vehicule" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                                <option value="">Sélectionner un véhicule...</option>
                                                <option value="camion_1">Camion 1 - ABC 123</option>
                                                <option value="camion_2">Camion 2 - DEF 456</option>
                                                <option value="fourgon_1">Fourgon 1 - GHI 789</option>
                                                <option value="fourgon_2">Fourgon 2 - JKL 012</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Produits à transférer -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Produits à transférer</label>
                                        <div class="border border-gray-300 rounded-md p-4 space-y-3 max-h-60 overflow-y-auto">
                                            @for($i = 0; $i < 3; $i++)
                                                <div class="grid grid-cols-3 gap-3 items-end" wire:key="produit-{{ $i }}">
                                                    <div>
                                                        <label class="block text-xs font-medium text-gray-600">Produit</label>
                                                        <select wire:model="form.produits.{{ $i }}.id" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                                            <option value="">Sélectionner...</option>
                                                            <option value="1">Riz local</option>
                                                            <option value="2">Maïs jaune</option>
                                                            <option value="3">Haricot rouge</option>
                                                            <option value="4">Patate douce</option>
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label class="block text-xs font-medium text-gray-600">Quantité (kg)</label>
                                                        <input wire:model="form.produits.{{ $i }}.quantite" type="number" step="0.01"
                                                            class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                                    </div>
                                                    <div>
                                                        <button type="button" wire:click="removeProduit({{ $i }})" 
                                                            class="px-3 py-2 text-sm text-red-600 hover:text-red-900">
                                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                            @endfor
                                        </div>
                                        <button type="button" wire:click="addProduit" 
                                            class="mt-2 inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            <svg class="-ml-0.5 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                            </svg>
                                            Ajouter un produit
                                        </button>
                                    </div>

                                    <!-- Chauffeur et notes -->
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Chauffeur</label>
                                            <select wire:model="form.chauffeur" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                                <option value="">Sélectionner un chauffeur...</option>
                                                <option value="rakoto">Rakoto Jean</option>
                                                <option value="rabe">Rabe Paul</option>
                                                <option value="randria">Randria Pierre</option>
                                            </select>
                                        </div>
                                        
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Priorité</label>
                                            <select wire:model="form.priorite" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                                <option value="normale">Normale</option>
                                                <option value="urgente">Urgente</option>
                                                <option value="tres_urgente">Très urgente</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Notes</label>
                                        <textarea wire:model="form.notes" rows="3"
                                            class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                            placeholder="Instructions particulières pour le transfert..."></textarea>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="save" type="button" 
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Créer le transfert
                        </button>
                        <button wire:click="$toggle('showModal')" type="button" 
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Annuler
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal de détails du transfert -->
    @if($showDetailsModal ?? false)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="$toggle('showDetailsModal')"></div>
                
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                                    Détails du transfert #T{{ str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT) }}
                                </h3>
                                
                                <!-- Timeline du transfert -->
                                <div class="flow-root">
                                    <ul class="-mb-8">
                                        <li>
                                            <div class="relative pb-8">
                                                <div class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"></div>
                                                <div class="relative flex space-x-3">
                                                    <div>
                                                        <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white">
                                                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                            </svg>
                                                        </span>
                                                    </div>
                                                    <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                        <div>
                                                            <p class="text-sm text-gray-500">Transfert créé par <span class="font-medium text-gray-900">Admin</span></p>
                                                        </div>
                                                        <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                            <time>15/01/2024 08:30</time>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="relative pb-8">
                                                <div class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"></div>
                                                <div class="relative flex space-x-3">
                                                    <div>
                                                        <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white">
                                                            <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                            </svg>
                                                        </span>
                                                    </div>
                                                    <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                        <div>
                                                            <p class="text-sm text-gray-500">Préparation terminée</p>
                                                        </div>
                                                        <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                            <time>15/01/2024 10:15</time>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="relative">
                                                <div class="relative flex space-x-3">
                                                    <div>
                                                        <span class="h-8 w-8 rounded-full bg-yellow-500 flex items-center justify-center ring-8 ring-white">
                                                            <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                        </span>
                                                    </div>
                                                    <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                        <div>
                                                            <p class="text-sm text-gray-500">En transit - Départ effectué</p>
                                                        </div>
                                                        <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                            <time>15/01/2024 11:00</time>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>

                                <!-- Informations détaillées -->
                                <div class="mt-6 grid grid-cols-2 gap-4">
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900 mb-2">Informations générales</h4>
                                        <dl class="space-y-1">
                                            <div class="flex justify-between text-sm">
                                                <dt class="text-gray-500">Origine:</dt>
                                                <dd class="text-gray-900">Dépôt Central</dd>
                                            </div>
                                            <div class="flex justify-between text-sm">
                                                <dt class="text-gray-500">Destination:</dt>
                                                <dd class="text-gray-900">Dépôt Nord</dd>
                                            </div>
                                            <div class="flex justify-between text-sm">
                                                <dt class="text-gray-500">Véhicule:</dt>
                                                <dd class="text-gray-900">Camion ABC-123</dd>
                                            </div>
                                            <div class="flex justify-between text-sm">
                                                <dt class="text-gray-500">Chauffeur:</dt>
                                                <dd class="text-gray-900">Rakoto Jean</dd>
                                            </div>
                                        </dl>
                                    </div>
                                    
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900 mb-2">Produits transférés</h4>
                                        <ul class="space-y-1">
                                            <li class="flex justify-between text-sm">
                                                <span class="text-gray-500">Riz local:</span>
                                                <span class="text-gray-900">500 kg</span>
                                            </li>
                                            <li class="flex justify-between text-sm">
                                                <span class="text-gray-500">Maïs jaune:</span>
                                                <span class="text-gray-900">200 kg</span>
                                            </li>
                                            <li class="flex justify-between text-sm font-medium border-t pt-1">
                                                <span class="text-gray-900">Total:</span>
                                                <span class="text-gray-900">700 kg</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="$toggle('showDetailsModal')" type="button" 
                            class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Fermer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@script
<script>
    // Méthode pour générer des données factices pour les transferts
    function getDummyTransferts() {
        return [
            [
                'id' => 1, 
                'numero' => 'T001', 
                'date' => '2024-01-15 08:30:00',
                'depot_origine' => 'Dépôt Central',
                'depot_destination' => 'Dépôt Nord',
                'produits' => ['Riz: 500kg', 'Maïs: 200kg'],
                'vehicule' => 'Camion ABC-123',
                'statut' => 'en_transit'
            ],
            [
                'id' => 2,
                'numero' => 'T002',
                'date' => '2024-01-14 14:15:00',
                'depot_origine' => 'Dépôt Nord',
                'depot_destination' => 'Dépôt Sud',
                'produits' => ['Haricot: 150kg', 'Patate: 300kg'],
                'vehicule' => 'Fourgon DEF-456',
                'statut' => 'recu'
            ],
            [
                'id' => 3,
                'numero' => 'T003',
                'date' => '2024-01-14 09:00:00',
                'depot_origine' => 'Dépôt Est',
                'depot_destination' => 'Dépôt Central',
                'produits' => ['Riz: 800kg'],
                'vehicule' => 'Camion GHI-789',
                'statut' => 'en_preparation'
            ],
            [
                'id' => 4,
                'numero' => 'T004',
                'date' => '2024-01-13 16:30:00',
                'depot_origine' => 'Dépôt Sud',
                'depot_destination' => 'Dépôt Est',
                'produits' => ['Maïs: 400kg', 'Haricot: 250kg', 'Riz: 600kg'],
                'vehicule' => 'Camion JKL-012',
                'statut' => 'en_transit'
            ],
            [
                'id' => 5,
                'numero' => 'T005',
                'date' => '2024-01-12 11:45:00',
                'depot_origine' => 'Dépôt Central',
                'depot_destination' => 'Dépôt Sud',
                'produits' => ['Patate: 450kg'],
                'vehicule' => 'Fourgon MNO-345',
                'statut' => 'recu'
            ]
        ];
    }
</script>
@endscript