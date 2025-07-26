{{-- /home/kananavy/Documents/DevGGasy/projet-tina/resources/views/livewire/stocks/vente.blade.php - VERSION AMÉLIORÉE --}}
<div>
    <!-- Statistiques en temps réel avec alertes -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-6">
        <!-- Ventes du jour -->
        <div class="bg-white overflow-hidden shadow-lg rounded-lg border-l-4 border-blue-500">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Ventes du jour</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900">{{ $ventesJour ?? '12' }}</div>
                                <div class="ml-2 flex items-baseline text-sm font-semibold text-green-600">
                                    <svg class="self-center flex-shrink-0 h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="sr-only">Augmenté de</span>
                                    +8%
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chiffre d'affaires journalier -->
        <div class="bg-white overflow-hidden shadow-lg rounded-lg border-l-4 border-green-500">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">CA Journalier</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900">{{ number_format($caJournalier ?? 2453280, 0, ',', ' ') }}</div>
                                <div class="ml-1 text-sm font-medium text-gray-500">Ar</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Commandes en attente -->
        <div class="bg-white overflow-hidden shadow-lg rounded-lg border-l-4 border-yellow-500">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-yellow-500 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">En attente</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900">{{ $commandesAttente ?? '3' }}</div>
                                @if(($commandesAttente ?? 3) > 5)
                                    <div class="ml-2 flex items-baseline text-sm font-semibold text-red-600">
                                        <svg class="self-center flex-shrink-0 h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                        <span class="sr-only">Attention</span>
                                        Élevé
                                    </div>
                                @endif
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- CA Mensuel -->
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
                            <dt class="text-sm font-medium text-gray-500 truncate">CA Mensuel</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900">{{ number_format($caMensuel ?? 56245680, 0, ',', ' ') }}</div>
                                <div class="ml-1 text-sm font-medium text-gray-500">Ar</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alertes et notifications -->
    @if(session()->has('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-md p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Barre d'outils améliorée -->
    <div class="bg-white shadow-lg rounded-lg mb-6">
        <div class="px-6 py-5">
            <div class="sm:flex sm:items-center sm:justify-between">
                <div class="flex-1 min-w-0">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-4">
                        <!-- Recherche avancée -->
                        <div class="flex-1">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <input wire:model.live.debounce.300ms="search" type="text" 
                                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Rechercher par client, produit, référence...">
                            </div>
                        </div>
                        
                        <!-- Filtres multiples -->
                        <div class="mt-3 sm:mt-0 flex space-x-3">
                            <select wire:model.live="filterStatus" 
                                class="block w-full pl-3 pr-10 py-3 text-base border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-lg">
                                <option value="">Tous les statuts</option>
                                <option value="en_attente">🟡 En attente</option>
                                <option value="valide">🟢 Validé</option>
                                <option value="livre">🚚 Livré</option>
                                <option value="annule">🔴 Annulé</option>
                            </select>

                            <select wire:model.live="filterPeriod" 
                                class="block w-full pl-3 pr-10 py-3 text-base border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-lg">
                                <option value="">Toutes les périodes</option>
                                <option value="today">Aujourd'hui</option>
                                <option value="week">Cette semaine</option>
                                <option value="month">Ce mois</option>
                                <option value="custom">Période personnalisée</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <!-- Actions principales -->
                <div class="mt-5 sm:mt-0 sm:ml-6 sm:flex-shrink-0 sm:flex sm:items-center sm:space-x-3">
                    <button wire:click="exportVentes" type="button" 
                        class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Exporter
                    </button>
                    
                    <button wire:click="openVenteModal" type="button" 
                        class="inline-flex items-center px-6 py-3 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Nouvelle Vente
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau des ventes amélioré -->
    <div class="bg-white shadow-lg overflow-hidden rounded-lg">
        <div class="px-6 py-5 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Liste des ventes</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">
                        {{ $totalVentes ?? 25 }} ventes trouvées
                        @if($search)
                            pour "{{ $search }}"
                        @endif
                    </p>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-500">Afficher:</span>
                    <select wire:model.live="perPage" class="border border-gray-300 rounded-md text-sm py-1 px-2">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th wire:click="sortBy('reference')" class="cursor-pointer px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hover:bg-gray-100 transition-colors duration-150">
                            <div class="flex items-center space-x-1">
                                <span>Référence</span>
                                @if(($sortField ?? '') == 'reference')
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ ($sortDirection ?? 'asc') == 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}" />
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th wire:click="sortBy('date')" class="cursor-pointer px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hover:bg-gray-100 transition-colors duration-150">
                            <div class="flex items-center space-x-1">
                                <span>Date</span>
                                @if(($sortField ?? '') == 'date')
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ ($sortDirection ?? 'asc') == 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}" />
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                        <th wire:click="sortBy('poids')" class="cursor-pointer px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hover:bg-gray-100 transition-colors duration-150">
                            <div class="flex items-center space-x-1">
                                <span>Poids (kg)</span>
                                @if(($sortField ?? '') == 'poids')
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ ($sortDirection ?? 'asc') == 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}" />
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th wire:click="sortBy('montant')" class="cursor-pointer px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hover:bg-gray-100 transition-colors duration-150">
                            <div class="flex items-center space-x-1">
                                <span>Montant (Ar)</span>
                                @if(($sortField ?? '') == 'montant')
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ ($sortDirection ?? 'asc') == 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}" />
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Paiement</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @php
                        // Données factices étendues pour la démonstration
                        $ventes_demo = [
                            ['id' => 1, 'reference' => 'VENTE-001', 'date' => '2025-01-26 14:30', 'produit' => 'Riz local', 'client' => 'Rakoto Jean', 'poids' => 250, 'prix_unitaire' => 3500, 'montant' => 875000, 'paiement' => 875000, 'reste' => 0, 'statut' => 'livre'],
                            ['id' => 2, 'reference' => 'VENTE-002', 'date' => '2025-01-26 11:20', 'produit' => 'Maïs jaune', 'client' => 'Rabe Hery', 'poids' => 150, 'prix_unitaire' => 2800, 'montant' => 420000, 'paiement' => 200000, 'reste' => 220000, 'statut' => 'en_attente'],
                            ['id' => 3, 'reference' => 'VENTE-003', 'date' => '2025-01-25 16:45', 'produit' => 'Haricot rouge', 'client' => 'Andry Nivo', 'poids' => 80, 'prix_unitaire' => 4200, 'montant' => 336000, 'paiement' => 336000, 'reste' => 0, 'statut' => 'valide'],
                            ['id' => 4, 'reference' => 'VENTE-004', 'date' => '2025-01-25 09:15', 'produit' => 'Riz local', 'client' => 'Hery Tiana', 'poids' => 300, 'prix_unitaire' => 3500, 'montant' => 1050000, 'paiement' => 0, 'reste' => 1050000, 'statut' => 'annule'],
                            ['id' => 5, 'reference' => 'VENTE-005', 'date' => '2025-01-24 15:00', 'produit' => 'Patate douce', 'client' => 'Nivo Rina', 'poids' => 200, 'prix_unitaire' => 1800, 'montant' => 360000, 'paiement' => 360000, 'reste' => 0, 'statut' => 'livre'],
                        ];
                    @endphp
                    
                    @forelse($ventes ?? $ventes_demo as $vente)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-600">
                                {{ $vente['reference'] }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ \Carbon\Carbon::parse($vente['date'])->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $vente['produit'] }}</div>
                                <div class="text-sm text-gray-500">{{ number_format($vente['prix_unitaire'] ?? 0, 0) }} Ar/kg</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $vente['client'] }}</div>
                                <div class="text-sm text-gray-500">Client particulier</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                {{ number_format($vente['poids'], 0, ',', ' ') }} kg
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ number_format($vente['montant'] ?? 0, 0, ',', ' ') }} Ar</div>
                                @if(isset($vente['reste']) && $vente['reste'] > 0)
                                    <div class="text-sm text-red-600">Reste: {{ number_format($vente['reste'], 0, ',', ' ') }} Ar</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $paiement = $vente['paiement'] ?? 0;
                                    $montant = $vente['montant'] ?? 0;
                                    $pourcentage = $montant > 0 ? ($paiement / $montant) * 100 : 0;
                                @endphp
                                <div class="flex flex-col">
                                    <div class="text-sm font-medium text-gray-900">{{ number_format($paiement, 0, ',', ' ') }} Ar</div>
                                    <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                        <div class="h-2 rounded-full {{ $pourcentage >= 100 ? 'bg-green-600' : ($pourcentage >= 50 ? 'bg-yellow-500' : 'bg-red-500') }}" 
                                             style="width: {{ min($pourcentage, 100) }}%"></div>
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">{{ number_format($pourcentage, 0) }}%</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $colors = [
                                        'en_attente' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                        'valide' => 'bg-blue-100 text-blue-800 border-blue-200',
                                        'livre' => 'bg-green-100 text-green-800 border-green-200',
                                        'annule' => 'bg-red-100 text-red-800 border-red-200'
                                    ];
                                    $icons = [
                                        'en_attente' => '🟡',
                                        'valide' => '🔵',
                                        'livre' => '✅',
                                        'annule' => '❌'
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $colors[$vente['statut']] ?? 'bg-gray-100 text-gray-800 border-gray-200' }}">
                                    <span class="mr-1">{{ $icons[$vente['statut']] ?? '📋' }}</span>
                                    {{ ucfirst(str_replace('_', ' ', $vente['statut'])) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <button wire:click="viewVente({{ $vente['id'] }})" 
                                        class="text-blue-600 hover:text-blue-900 transition-colors duration-150" 
                                        title="Voir les détails">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                    <button wire:click="editVente({{ $vente['id'] }})" 
                                        class="text-yellow-600 hover:text-yellow-900 transition-colors duration-150" 
                                        title="Modifier">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    @if($vente['statut'] !== 'livre')
                                        <button wire:click="deleteVente({{ $vente['id'] }})" 
                                            wire:confirm="Êtes-vous sûr de vouloir supprimer cette vente ?"
                                            class="text-red-600 hover:text-red-900 transition-colors duration-150" 
                                            title="Supprimer">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                    </svg>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune vente trouvée</h3>
                                    <p class="text-gray-500 mb-4">Commencez par créer votre première vente</p>
                                    <button wire:click="openVenteModal" 
                                        class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                        Nouvelle Vente
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination améliorée -->
        <div class="bg-white px-6 py-4 flex items-center justify-between border-t border-gray-200">
           <div class="flex-1 flex justify-between sm:hidden">
    <button wire:click="previousPage" {{ $ventes->currentPage() <= 1 ? 'disabled' : '' }}
        class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:bg-gray-100 disabled:text-gray-400">
        Précédent
    </button>
    <button wire:click="nextPage" {{ $ventes->currentPage() >= $ventes->lastPage() ? 'disabled' : '' }}
        class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:bg-gray-100 disabled:text-gray-400">
        Suivant
    </button>
</div>

            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700">
                        Affichage de <span class="font-medium">{{ $from ?? 1 }}</span> à 
                        <span class="font-medium">{{ $to ?? 5 }}</span> sur 
                        <span class="font-medium">{{ $totalVentes ?? 25 }}</span> résultats
                    </p>
                </div>
                <div>
                    {{ $ventes->links() ?? '' }}
                </div>
            </div>
        </div>
    </div>

    {{-- Modal dynamique pour les ventes (utilise le système de modals dynamiques) --}}
    @include('livewire.voyage.modals.dechargement-modal', ['type_dechargement' => 'vente'])
</div>