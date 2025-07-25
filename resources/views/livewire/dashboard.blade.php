<div class="min-h-screen bg-gray-50 pt-20 pb-6 px-4 md:px-6">
    <div class="space-y-6 max-w-7xl mx-auto">
        <!-- En-tête avec filtres -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
                <p class="text-sm text-gray-600">Vue d'ensemble de votre activité logistique</p>
            </div>
            <div>
                <select wire:model.live="selectedPeriod" class="w-full sm:w-auto rounded-lg border-gray-300 text-sm">
                    <option value="today">Aujourd'hui</option>
                    <option value="week">Cette semaine</option>
                    <option value="month">Ce mois</option>
                    <option value="year">Cette année</option>
                </select>
            </div>
        </div>

        <!-- Alertes -->
        @if(count($stats['alertes']) > 0)
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <div class="flex items-center mb-3">
                <svg class="w-5 h-5 text-yellow-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"/>
                </svg>
                <h3 class="text-sm font-medium text-yellow-800">Alertes</h3>
            </div>
            <div class="space-y-2">
                @foreach($stats['alertes'] as $alerte)
                <div class="flex flex-col xs:flex-row xs:items-center xs:justify-between text-sm gap-1 xs:gap-0">
                    <span class="text-yellow-700">{{ $alerte['message'] }}</span>
                    <button class="text-yellow-600 hover:text-yellow-800 font-medium">{{ $alerte['action'] }}</button>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Stats principales -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
            <!-- Chiffre d'affaires -->
            <div class="bg-white rounded-lg shadow p-4 md:p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Chiffre d'affaires</p>
                        <p class="text-xl md:text-2xl font-bold text-gray-900">{{ number_format($stats['chiffre_affaires'], 0, ',', ' ') }}</p>
                        <p class="text-xs text-green-600">MGA</p>
                    </div>
                </div>
            </div>

            <!-- Bénéfice -->
            <div class="bg-white rounded-lg shadow p-4 md:p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Bénéfice brut</p>
                        <p class="text-xl md:text-2xl font-bold {{ $stats['benefice_brut'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ number_format($stats['benefice_brut'], 0, ',', ' ') }}
                        </p>
                        <p class="text-xs text-gray-500">MGA</p>
                    </div>
                </div>
            </div>

            <!-- Voyages -->
            <div class="bg-white rounded-lg shadow p-4 md:p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-purple-100 rounded-lg">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0M15 17a2 2 0 104 0"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Voyages en cours</p>
                        <p class="text-xl md:text-2xl font-bold text-gray-900">{{ $stats['voyages_en_cours'] }}</p>
                        <p class="text-xs text-gray-500">/ {{ $stats['voyages_total'] }} total</p>
                    </div>
                </div>
            </div>

            <!-- Factures impayées -->
            <div class="bg-white rounded-lg shadow p-4 md:p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-red-100 rounded-lg">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Factures impayées</p>
                        <p class="text-xl md:text-2xl font-bold text-red-600">{{ number_format($stats['factures_impayees'], 0, ',', ' ') }}</p>
                        <p class="text-xs text-gray-500">MGA</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contenu principal -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6">
            <!-- Derniers voyages -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-4 md:p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Derniers voyages</h3>
                </div>
                <div class="divide-y divide-gray-200">
                    @forelse($stats['derniers_voyages'] as $voyage)
                    <div class="p-3 md:p-4 hover:bg-gray-50">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $voyage->reference }}</p>
                                <p class="text-sm text-gray-500">{{ $voyage->origine->nom ?? 'N/A' }}</p>
                                <p class="text-xs text-gray-400">{{ $voyage->vehicule->immatriculation ?? 'N/A' }}</p>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                    {{ $voyage->statut === 'en_cours' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                    {{ ucfirst($voyage->statut) }}
                                </span>
                                <p class="text-xs text-gray-500 mt-1">{{ $voyage->date->format('d/m') }}</p>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="p-4 text-center text-gray-500">Aucun voyage récent</div>
                    @endforelse
                </div>
            </div>

            <!-- Dernières transactions -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-4 md:p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Transactions récentes</h3>
                </div>
                <div class="divide-y divide-gray-200">
                    @forelse($stats['dernieres_transactions'] as $transaction)
                    <div class="p-3 md:p-4 hover:bg-gray-50">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $transaction->reference }}</p>
                                <p class="text-sm text-gray-500">{{ $transaction->from_nom_display }} → {{ $transaction->to_nom_display }}</p>
                                <p class="text-xs text-gray-400">{{ ucfirst($transaction->type) }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium {{ $transaction->type === 'vente' ? 'text-green-600' : 'text-gray-900' }}">
                                    {{ number_format($transaction->montant_mga, 0, ',', ' ') }}
                                </p>
                                <p class="text-xs text-gray-500">{{ $transaction->date->format('d/m') }}</p>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="p-4 text-center text-gray-500">Aucune transaction récente</div>
                    @endforelse
                </div>
            </div>

            <!-- Top produits -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-4 md:p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Top produits</h3>
                </div>
                <div class="divide-y divide-gray-200">
                    @forelse($stats['top_produits'] as $item)
                    <div class="p-3 md:p-4 hover:bg-gray-50">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $item->produit->nom ?? 'N/A' }}</p>
                                <p class="text-sm text-gray-500">{{ number_format($item->total_quantite, 0) }} {{ $item->produit->unite ?? 'unités' }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-green-600">{{ number_format($item->total_ventes, 0, ',', ' ') }}</p>
                                <p class="text-xs text-gray-500">MGA</p>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="p-4 text-center text-gray-500">Aucune vente récente</div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Stats supplémentaires -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
            <div class="bg-white rounded-lg shadow p-4 md:p-6">
                <h4 class="text-sm font-medium text-gray-900 mb-4">Logistique</h4>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Véhicules actifs</span>
                        <span class="text-sm font-medium">{{ $stats['vehicules_actifs'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Voyages période</span>
                        <span class="text-sm font-medium">{{ $stats['voyages_periode'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Stock total</span>
                        <span class="text-sm font-medium">{{ number_format($stats['stock_total_kg'], 0) }} kg</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-4 md:p-6">
                <h4 class="text-sm font-medium text-gray-900 mb-4">Produits & Users</h4>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Produits</span>
                        <span class="text-sm font-medium">{{ $stats['produits_total'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Propriétaires actifs</span>
                        <span class="text-sm font-medium">{{ $stats['proprietaires_actifs'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Transactions aujourd'hui</span>
                        <span class="text-sm font-medium">{{ $stats['transactions_jour'] }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-4 md:p-6">
                <h4 class="text-sm font-medium text-gray-900 mb-4">Actions rapides</h4>
                <div class="space-y-2">
                    <button class="w-full text-left px-3 py-2 text-sm text-blue-600 hover:bg-blue-50 rounded">
                        + Nouveau voyage
                    </button>
                    <button class="w-full text-left px-3 py-2 text-sm text-green-600 hover:bg-green-50 rounded">
                        + Transaction
                    </button>
                    <button class="w-full text-left px-3 py-2 text-sm text-purple-600 hover:bg-purple-50 rounded">
                        📊 Rapports
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>