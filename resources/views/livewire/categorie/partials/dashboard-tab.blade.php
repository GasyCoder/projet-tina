{{-- resources/views/livewire/categorie/partials/dashboard-tab.blade.php --}}
<div class="space-y-6">
    <!-- KPIs principaux -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Chiffre d'affaires / Dépenses -->
        <div class="bg-gradient-to-br {{ $categorie->type === 'recette' ? 'from-green-50 to-green-100 dark:from-green-900/50 dark:to-green-800/30' : 'from-red-50 to-red-100 dark:from-red-900/50 dark:to-red-800/30' }} p-6 rounded-xl border {{ $categorie->type === 'recette' ? 'border-green-200 dark:border-green-700' : 'border-red-200 dark:border-red-700' }}">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium {{ $categorie->type === 'recette' ? 'text-green-700 dark:text-green-300' : 'text-red-700 dark:text-red-300' }}">
                        {{ $categorie->type === 'recette' ? 'Chiffre d\'affaires' : 'Total dépenses' }}
                    </p>
                    <p class="text-2xl font-bold {{ $categorie->type === 'recette' ? 'text-green-900 dark:text-green-100' : 'text-red-900 dark:text-red-100' }}">
                        {{ number_format($dashboardData['financier']['chiffre_affaires'] ?? $dashboardData['financier']['total_depenses'] ?? 0, 0, ',', ' ') }} Ar
                    </p>
                </div>
                <div class="p-3 {{ $categorie->type === 'recette' ? 'bg-green-200 dark:bg-green-800' : 'bg-red-200 dark:bg-red-800' }} rounded-lg">
                    @if($categorie->type === 'recette')
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    @else
                        <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" />
                        </svg>
                    @endif
                </div>
            </div>
            <div class="mt-4">
                <p class="text-xs {{ $categorie->type === 'recette' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                    {{ number_format($dashboardData['temporel']['mois_courant'][$categorie->type === 'recette' ? 'recettes' : 'depenses'] ?? 0, 0, ',', ' ') }} Ar ce mois
                </p>
            </div>
        </div>

        <!-- Bénéfice/Perte -->
        <div class="bg-gradient-to-br {{ ($dashboardData['financier']['benefice_perte'] ?? 0) >= 0 ? 'from-blue-50 to-blue-100 dark:from-blue-900/50 dark:to-blue-800/30' : 'from-orange-50 to-orange-100 dark:from-orange-900/50 dark:to-orange-800/30' }} p-6 rounded-xl border {{ ($dashboardData['financier']['benefice_perte'] ?? 0) >= 0 ? 'border-blue-200 dark:border-blue-700' : 'border-orange-200 dark:border-orange-700' }}">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium {{ ($dashboardData['financier']['benefice_perte'] ?? 0) >= 0 ? 'text-blue-700 dark:text-blue-300' : 'text-orange-700 dark:text-orange-300' }}">
                        {{ ($dashboardData['financier']['benefice_perte'] ?? 0) >= 0 ? 'Bénéfice' : 'Perte' }}
                    </p>
                    <p class="text-2xl font-bold {{ ($dashboardData['financier']['benefice_perte'] ?? 0) >= 0 ? 'text-blue-900 dark:text-blue-100' : 'text-orange-900 dark:text-orange-100' }}">
                        {{ ($dashboardData['financier']['benefice_perte'] ?? 0) >= 0 ? '+' : '' }}{{ number_format($dashboardData['financier']['benefice_perte'] ?? 0, 0, ',', ' ') }} Ar
                    </p>
                </div>
                <div class="p-3 {{ ($dashboardData['financier']['benefice_perte'] ?? 0) >= 0 ? 'bg-blue-200 dark:bg-blue-800' : 'bg-orange-200 dark:bg-orange-800' }} rounded-lg">
                    @if(($dashboardData['financier']['benefice_perte'] ?? 0) >= 0)
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    @else
                        <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" />
                        </svg>
                    @endif
                </div>
            </div>
            @if($categorie->type === 'recette' && ($dashboardData['financier']['marge'] ?? 0) > 0)
                <div class="mt-4">
                    <p class="text-xs text-blue-600 dark:text-blue-400">
                        Marge: {{ number_format($dashboardData['financier']['marge'] ?? 0, 1) }}%
                    </p>
                </div>
            @endif
        </div>

        <!-- Budget utilisation -->
        <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/50 dark:to-purple-800/30 p-6 rounded-xl border border-purple-200 dark:border-purple-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-purple-700 dark:text-purple-300">Budget utilisé</p>
                    <p class="text-2xl font-bold text-purple-900 dark:text-purple-100">
                        {{ number_format($dashboardData['budget']['pourcentage_utilise'] ?? 0, 1) }}%
                    </p>
                </div>
                <div class="p-3 bg-purple-200 dark:bg-purple-800 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <!-- Barre de progression -->
                <div class="w-full bg-purple-200 dark:bg-purple-800 rounded-full h-2">
                    <div class="bg-purple-600 dark:bg-purple-400 h-2 rounded-full transition-all duration-300" 
                         style="width: {{ min(100, $dashboardData['budget']['pourcentage_utilise'] ?? 0) }}%"></div>
                </div>
                <p class="text-xs text-purple-600 dark:text-purple-400 mt-2">
                    {{ number_format($dashboardData['budget']['montant_restant'] ?? 0, 0, ',', ' ') }} Ar restants
                </p>
            </div>
        </div>

        <!-- Transactions moyennes -->
        <div class="bg-gradient-to-br from-amber-50 to-amber-100 dark:from-amber-900/50 dark:to-amber-800/30 p-6 rounded-xl border border-amber-200 dark:border-amber-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-amber-700 dark:text-amber-300">Transaction moyenne</p>
                    <p class="text-2xl font-bold text-amber-900 dark:text-amber-100">
                        {{ number_format($dashboardData['financier']['transaction_quotidienne_moyenne'] ?? 0, 0, ',', ' ') }} Ar
                    </p>
                </div>
                <div class="p-3 bg-amber-200 dark:bg-amber-800 rounded-lg">
                    <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <p class="text-xs text-amber-600 dark:text-amber-400">
                    {{ $dashboardData['temporel']['mois_courant']['nombre'] ?? 0 }} transactions ce mois
                </p>
            </div>
        </div>
    </div>

    <!-- Graphique d'évolution mensuelle -->
    @if(!empty($dashboardData['temporel']['evolution_mensuelle'] ?? []))
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Évolution sur 12 mois</h3>
                <div class="flex items-center space-x-4">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                        <span class="text-sm text-gray-600 dark:text-gray-400">Recettes</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-red-500 rounded-full mr-2"></div>
                        <span class="text-sm text-gray-600 dark:text-gray-400">Dépenses</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-blue-500 rounded-full mr-2"></div>
                        <span class="text-sm text-gray-600 dark:text-gray-400">Bénéfice</span>
                    </div>
                </div>
            </div>
            
            <!-- Graphique simplifié avec CSS -->
            <div class="relative h-64">
                <div class="absolute inset-0 flex items-end justify-between space-x-1">
                    @foreach($dashboardData['temporel']['evolution_mensuelle'] as $index => $mois)
                        @php
                            $maxValue = max(array_column($dashboardData['temporel']['evolution_mensuelle'], 'recettes'));
                            $maxValue = max($maxValue, max(array_column($dashboardData['temporel']['evolution_mensuelle'], 'depenses')));
                            $heightRecettes = $maxValue > 0 ? ($mois['recettes'] / $maxValue) * 100 : 0;
                            $heightDepenses = $maxValue > 0 ? ($mois['depenses'] / $maxValue) * 100 : 0;
                        @endphp
                        <div class="flex-1 flex items-end justify-center space-x-1">
                            <!-- Barre recettes -->
                            <div class="w-4 bg-green-500 rounded-t-sm transition-all duration-300 hover:bg-green-600" 
                                 style="height: {{ $heightRecettes }}%"
                                 title="Recettes: {{ number_format($mois['recettes'], 0, ',', ' ') }} Ar"></div>
                            <!-- Barre dépenses -->
                            <div class="w-4 bg-red-500 rounded-t-sm transition-all duration-300 hover:bg-red-600" 
                                 style="height: {{ $heightDepenses }}%"
                                 title="Dépenses: {{ number_format($mois['depenses'], 0, ',', ' ') }} Ar"></div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Étiquettes des mois -->
                <div class="absolute -bottom-6 left-0 right-0 flex justify-between text-xs text-gray-500 dark:text-gray-400">
                    @foreach($dashboardData['temporel']['evolution_mensuelle'] as $mois)
                        <span class="transform -rotate-45 origin-top-left">{{ $mois['mois'] }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Répartition et insights -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Top partenaires -->
        @if(!empty($dashboardData['analytics']['top_partenaires'] ?? []))
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Top Partenaires</h3>
                <div class="space-y-3">
                    @foreach($dashboardData['analytics']['top_partenaires'] as $partenaire)
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $partenaire['partenaire'] }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $partenaire['nombre_transactions'] }} transactions</p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-gray-900 dark:text-white">
                                    {{ number_format($partenaire['total'], 0, ',', ' ') }} Ar
                                </p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    Moy: {{ number_format($partenaire['moyenne'], 0, ',', ' ') }} Ar
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Modes de paiement -->
        @if(!empty($dashboardData['analytics']['modes_paiement'] ?? []))
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Répartition par mode de paiement</h3>
                <div class="space-y-3">
                    @foreach($dashboardData['analytics']['modes_paiement'] as $mode)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-3 h-3 rounded-full mr-3 
                                    {{ $mode['mode'] === 'especes' ? 'bg-orange-500' : 
                                       ($mode['mode'] === 'MobileMoney' ? 'bg-purple-500' : 'bg-blue-500') }}"></div>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ ucfirst($mode['mode']) }}
                                </span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="text-right">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ number_format($mode['total'], 0, ',', ' ') }} Ar
                                    </p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">
                                        {{ $mode['nombre'] }} trans.
                                    </p>
                                </div>
                                <span class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ $mode['pourcentage'] }}%
                                </span>
                            </div>
                        </div>
                        <!-- Barre de progression -->
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                            <div class="h-2 rounded-full transition-all duration-300
                                {{ $mode['mode'] === 'especes' ? 'bg-orange-500' : 
                                   ($mode['mode'] === 'MobileMoney' ? 'bg-purple-500' : 'bg-blue-500') }}" 
                                 style="width: {{ $mode['pourcentage'] }}%"></div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <!-- Transactions récentes -->
    @if(!empty($dashboardData['transactions_recentes'] ?? []))
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Transactions récentes</h3>
                <button wire:click="switchTab('transactions')" 
                        class="text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 text-sm font-medium">
                    Voir toutes →
                </button>
            </div>
            <div class="space-y-3">
                @foreach(array_slice($dashboardData['transactions_recentes'], 0, 5) as $transaction)
                    <div class="flex items-center justify-between p-3 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-colors cursor-pointer"
                         wire:click="showTransactionDetail({{ $transaction['id'] }})">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-lg flex items-center justify-center
                                {{ $transaction['type'] === 'recette' ? 'bg-green-100 dark:bg-green-900/50' : 'bg-red-100 dark:bg-red-900/50' }}">
                                @if($transaction['type'] === 'recette')
                                    <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                    </svg>
                                @endif
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $transaction['description'] }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ $transaction['date'] }} • {{ $transaction['mode_paiement'] }}
                                    @if($transaction['partenaire'])
                                        • {{ $transaction['partenaire'] }}
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-bold {{ $transaction['type'] === 'recette' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                {{ $transaction['montant_formate'] }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>