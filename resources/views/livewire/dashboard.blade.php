<div class="min-h-screen bg-gray-50 pt-20 pb-6 px-4 md:px-6">
    <div class="space-y-8 max-w-7xl mx-auto">
        <!-- En-tête avec filtres amélioré -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
            <div>
  <h1 class="text-2xl font-bold text-gray-900">
                    Dashboard Logistique
                </h1>
                <p class="text-gray-600 flex items-center gap-2">
                    <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                    </svg>
                    Vue d'ensemble de votre activité en temps réel
                </p>
            </div>
            <div class="flex items-center gap-3">
                <select wire:model.live="selectedPeriod" class="px-4 py-2 bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-all duration-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm font-medium">
                    <option value="today">📅 Aujourd'hui</option>
                    <option value="week">📊 Cette semaine</option>
                    <option value="month">📈 Ce mois</option>
                    <option value="year">🗓️ Cette année</option>
                </select>
                <button class="px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-all duration-200 shadow-sm hover:shadow-md text-sm font-medium">
                    📊 Exporter
                </button>
            </div>
        </div>

        <!-- Alertes améliorées -->
        @if(count($stats['alertes']) > 0)
        <div class="bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-200 rounded-2xl p-6 shadow-sm">
            <div class="flex items-center mb-4">
                <div class="p-2 bg-amber-100 rounded-xl mr-3">
                    <svg class="w-6 h-6 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-amber-800">⚠️ Alertes importantes</h3>
            </div>
            <div class="space-y-3">
                @foreach($stats['alertes'] as $alerte)
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between bg-white rounded-xl p-4 shadow-sm hover:shadow-md transition-all duration-200">
                    <span class="text-amber-700 font-medium">{{ $alerte['message'] }}</span>
                    <button class="mt-2 sm:mt-0 px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors duration-200 text-sm font-medium">
                        {{ $alerte['action'] }}
                    </button>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Stats principales améliorées -->
        {{-- <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Chiffre d'affaires -->
            <div class="group bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 p-6 border border-gray-100 hover:border-green-200">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-gradient-to-br from-green-100 to-emerald-100 rounded-2xl group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                        </svg>
                    </div>
                    <div class="text-green-600 text-sm font-medium bg-green-50 px-3 py-1 rounded-full">+12%</div>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-2">💰 Chiffre d'affaires</p>
                    <p class="text-2xl lg:text-3xl font-bold text-gray-900 mb-1">
                        {{ number_format($stats['chiffre_affaires'], 0, ',', ' ') }}
                    </p>
                    <p class="text-sm text-green-600 font-medium">MGA</p>
                </div>
            </div>

            <!-- Bénéfice -->
            <div class="group bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 p-6 border border-gray-100 hover:border-blue-200">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-2xl group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                    </div>
                    <div class="text-blue-600 text-sm font-medium bg-blue-50 px-3 py-1 rounded-full">+8%</div>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-2">📈 Bénéfice brut</p>
                    <p class="text-2xl lg:text-3xl font-bold {{ $stats['benefice_brut'] >= 0 ? 'text-blue-600' : 'text-red-600' }} mb-1">
                        {{ number_format($stats['benefice_brut'], 0, ',', ' ') }}
                    </p>
                    <p class="text-sm text-gray-500 font-medium">MGA</p>
                </div>
            </div>

            <!-- Voyages -->
            <div class="group bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 p-6 border border-gray-100 hover:border-purple-200">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-gradient-to-br from-purple-100 to-violet-100 rounded-2xl group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0M15 17a2 2 0 104 0"/>
                        </svg>
                    </div>
                    <div class="text-purple-600 text-sm font-medium bg-purple-50 px-3 py-1 rounded-full">Actif</div>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-2">🚚 Voyages en cours</p>
                    <p class="text-2xl lg:text-3xl font-bold text-purple-600 mb-1">{{ $stats['voyages_en_cours'] }}</p>
                    <p class="text-sm text-gray-500 font-medium">/ {{ $stats['voyages_total'] }} total</p>
                </div>
            </div>

            <!-- Factures impayées -->
            <div class="group bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 p-6 border border-gray-100 hover:border-red-200">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-gradient-to-br from-red-100 to-rose-100 rounded-2xl group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div class="text-red-600 text-sm font-medium bg-red-50 px-3 py-1 rounded-full">Urgent</div>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-2">📄 Factures impayées</p>
                    <p class="text-2xl lg:text-3xl font-bold text-red-600 mb-1">
                        {{ number_format($stats['factures_impayees'], 0, ',', ' ') }}
                    </p>
                    <p class="text-sm text-gray-500 font-medium">MGA</p>
                </div>
            </div>
        </div> --}}

        <!-- Contenu principal amélioré -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Derniers voyages -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-shadow duration-300">
                <div class="p-6 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-blue-100 rounded-xl">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0M15 17a2 2 0 104 0"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">🚛 Derniers voyages</h3>
                    </div>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($stats['derniers_voyages'] as $voyage)
                    <div class="p-4 hover:bg-gray-50 transition-colors duration-200 cursor-pointer">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-2">
                                    <p class="text-sm font-semibold text-gray-900">{{ $voyage->reference }}</p>
                                    <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full 
                                        {{ $voyage->statut === 'en_cours' ? 'bg-amber-100 text-amber-700' : 'bg-green-100 text-green-700' }}">
                                        {{ $voyage->statut === 'en_cours' ? '🔄 En cours' : '✅ Terminé' }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600 mb-1">📍 {{ $voyage->origine->nom ?? 'N/A' }}</p>
                                <p class="text-xs text-gray-500">🚗 {{ $voyage->vehicule->immatriculation ?? 'N/A' }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-gray-500">{{ $voyage->date->format('d/m') }}</p>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="p-8 text-center text-gray-500">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <p>Aucun voyage récent</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Dernières transactions -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-shadow duration-300">
                <div class="p-6 bg-gradient-to-r from-green-50 to-emerald-50 border-b border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-green-100 rounded-xl">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">💳 Transactions récentes</h3>
                    </div>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($stats['dernieres_transactions'] as $transaction)
                    <div class="p-4 hover:bg-gray-50 transition-colors duration-200 cursor-pointer">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-2">
                                    <p class="text-sm font-semibold text-gray-900">{{ $transaction->reference }}</p>
                                    <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full 
                                        {{ $transaction->type === 'vente' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }}">
                                        {{ $transaction->type === 'vente' ? '💰 Vente' : '📦 ' . ucfirst($transaction->type) }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600">{{ $transaction->from_nom_display }} → {{ $transaction->to_nom_display }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-semibold {{ $transaction->type === 'vente' ? 'text-green-600' : 'text-gray-900' }}">
                                    {{ number_format($transaction->montant_mga, 0, ',', ' ') }} MGA
                                </p>
                                <p class="text-xs text-gray-500">{{ $transaction->date->format('d/m') }}</p>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="p-8 text-center text-gray-500">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                        <p>Aucune transaction récente</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Top produits -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-shadow duration-300">
                <div class="p-6 bg-gradient-to-r from-purple-50 to-violet-50 border-b border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-purple-100 rounded-xl">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">🏆 Top produits</h3>
                    </div>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($stats['top_produits'] as $item)
                    <div class="p-4 hover:bg-gray-50 transition-colors duration-200 cursor-pointer">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-gray-900 mb-1">📦 {{ $item->produit->nom ?? 'N/A' }}</p>
                                <p class="text-sm text-gray-600">{{ number_format($item->total_quantite, 0) }} {{ $item->produit->unite ?? 'unités' }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-semibold text-green-600">{{ number_format($item->total_ventes, 0, ',', ' ') }}</p>
                                <p class="text-xs text-gray-500">MGA</p>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="p-8 text-center text-gray-500">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        <p>Aucune vente récente</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Stats supplémentaires et Actions rapides -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Stats Logistique -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-lg transition-shadow duration-300">
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-2 bg-blue-100 rounded-xl">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <h4 class="text-lg font-semibold text-gray-900">🚛 Logistique</h4>
                </div>
                <div class="space-y-4">
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-xl">
                        <span class="text-sm text-gray-600">🚗 Véhicules actifs</span>
                        <span class="text-sm font-semibold text-blue-600">{{ $stats['vehicules_actifs'] }}</span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-xl">
                        <span class="text-sm text-gray-600">📅 Voyages période</span>
                        <span class="text-sm font-semibold text-purple-600">{{ $stats['voyages_periode'] }}</span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-xl">
                        <span class="text-sm text-gray-600">📦 Stock total</span>
                        <span class="text-sm font-semibold text-green-600">{{ number_format($stats['stock_total_kg'], 0) }} kg</span>
                    </div>
                </div>
            </div>

            <!-- Stats Produits & Users -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-lg transition-shadow duration-300">
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-2 bg-green-100 rounded-xl">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <h4 class="text-lg font-semibold text-gray-900">👥 Activité</h4>
                </div>
                <div class="space-y-4">
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-xl">
                        <span class="text-sm text-gray-600">📦 Produits</span>
                        <span class="text-sm font-semibold text-purple-600">{{ $stats['produits_total'] }}</span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-xl">
                        <span class="text-sm text-gray-600">👨‍💼 Propriétaires actifs</span>
                        <span class="text-sm font-semibold text-blue-600">{{ $stats['proprietaires_actifs'] }}</span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-xl">
                        <span class="text-sm text-gray-600">💳 Transactions/jour</span>
                        <span class="text-sm font-semibold text-green-600">{{ $stats['transactions_jour'] }}</span>
                    </div>
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="lg:col-span-2 bg-gradient-to-br from-blue-600 to-purple-700 rounded-2xl shadow-lg p-6 text-white">
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-2 bg-white/20 rounded-xl">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <h4 class="text-lg font-semibold">⚡ Actions rapides</h4>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <a href="{{ route('voyages.index') }}" 
                       class="group bg-white/10 hover:bg-white/20 backdrop-blur-sm rounded-xl p-4 text-center transition-all duration-200 hover:scale-105">
                        <div class="text-2xl mb-2">🚛</div>
                        <p class="text-sm font-medium">Nouveau voyage</p>
                    </a>
                    <a href="{{ route('finance.index') }}" 
                       class="group bg-white/10 hover:bg-white/20 backdrop-blur-sm rounded-xl p-4 text-center transition-all duration-200 hover:scale-105">
                        <div class="text-2xl mb-2">💰</div>
                        <p class="text-sm font-medium">Transaction</p>
                    </a>
                    <a href="{{ route('admin.stocks') }}" 
                       class="group bg-white/10 hover:bg-white/20 backdrop-blur-sm rounded-xl p-4 text-center transition-all duration-200 hover:scale-105">
                        <div class="text-2xl mb-2">📊</div>
                        <p class="text-sm font-medium">Rapports</p>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>