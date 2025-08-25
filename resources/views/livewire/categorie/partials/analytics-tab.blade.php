{{-- resources/views/livewire/categorie/partials/analytics-tab.blade.php --}}
<div class="space-y-6">
    <!-- Métriques clés -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/50 dark:to-blue-800/30 p-6 rounded-xl border border-blue-200 dark:border-blue-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-blue-700 dark:text-blue-300">Volume Total</p>
                    <p class="text-2xl font-bold text-blue-900 dark:text-blue-100">
                        {{ number_format(($dashboardData['financier']['chiffre_affaires'] ?? 0) + ($dashboardData['financier']['total_depenses'] ?? 0), 0, ',', ' ') }} Ar
                    </p>
                </div>
                <div class="p-3 bg-blue-200 dark:bg-blue-800 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/50 dark:to-green-800/30 p-6 rounded-xl border border-green-200 dark:border-green-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-green-700 dark:text-green-300">Fréquence</p>
                    <p class="text-2xl font-bold text-green-900 dark:text-green-100">
                        {{ number_format($dashboardData['financier']['transaction_quotidienne_moyenne'] ?? 0, 0) }} / jour
                    </p>
                </div>
                <div class="p-3 bg-green-200 dark:bg-green-800 rounded-lg">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/50 dark:to-purple-800/30 p-6 rounded-xl border border-purple-200 dark:border-purple-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-purple-700 dark:text-purple-300">Partenaires Actifs</p>
                    <p class="text-2xl font-bold text-purple-900 dark:text-purple-100">
                        {{ count($dashboardData['analytics']['partenaires_associes'] ?? []) }}
                    </p>
                </div>
                <div class="p-3 bg-purple-200 dark:bg-purple-800 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-amber-50 to-amber-100 dark:from-amber-900/50 dark:to-amber-800/30 p-6 rounded-xl border border-amber-200 dark:border-amber-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-amber-700 dark:text-amber-300">Performance Budget</p>
                    <p class="text-2xl font-bold {{ ($dashboardData['budget']['budget_depasse'] ?? false) ? 'text-red-600 dark:text-red-400' : 'text-amber-900 dark:text-amber-100' }}">
                        {{ number_format($dashboardData['budget']['pourcentage_utilise'] ?? 0, 1) }}%
                    </p>
                </div>
                <div class="p-3 bg-amber-200 dark:bg-amber-800 rounded-lg">
                    <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Analyses par partenaires et modes de paiement -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Top Partenaires avec détails -->
        @if(!empty($dashboardData['analytics']['top_partenaires'] ?? []))
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Performance par Partenaire</h3>
                    <div class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>Top 5</span>
                    </div>
                </div>
                
                <div class="space-y-4">
                    @foreach($dashboardData['analytics']['top_partenaires'] as $index => $partenaire)
                        @php
                            $maxTotal = $dashboardData['analytics']['top_partenaires'][0]['total'] ?? 1;
                            $percentage = $maxTotal > 0 ? ($partenaire['total'] / $maxTotal) * 100 : 0;
                        @endphp
                        <div class="relative">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-white font-bold text-sm
                                        {{ $index === 0 ? 'bg-yellow-500' : ($index === 1 ? 'bg-gray-400' : ($index === 2 ? 'bg-orange-600' : 'bg-blue-500')) }}">
                                        {{ $index + 1 }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white">{{ $partenaire['partenaire'] }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $partenaire['nombre_transactions'] }} transactions</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-gray-900 dark:text-white">
                                        {{ number_format($partenaire['total'], 0, ',', ' ') }} Ar
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        Moy: {{ number_format($partenaire['moyenne'], 0, ',', ' ') }} Ar
                                    </p>
                                </div>
                            </div>
                            
                            <!-- Barre de progression -->
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                <div class="h-2 rounded-full transition-all duration-500 
                                    {{ $index === 0 ? 'bg-yellow-500' : ($index === 1 ? 'bg-gray-400' : ($index === 2 ? 'bg-orange-600' : 'bg-blue-500')) }}" 
                                     style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Répartition par mode de paiement -->
        @if(!empty($dashboardData['analytics']['modes_paiement'] ?? []))
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Répartition des Modes de Paiement</h3>
                    <div class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                        <span>Volume total</span>
                    </div>
                </div>
                
                <!-- Graphique en secteurs simplifié -->
                <div class="flex items-center justify-center mb-6">
                    <div class="relative w-48 h-48">
                        @php
                            $totalModes = array_sum(array_column($dashboardData['analytics']['modes_paiement'], 'total'));
                            $cumulativeAngle = 0;
                            $colors = [
                                'especes' => ['bg-orange-500', 'border-orange-600'],
                                'MobileMoney' => ['bg-purple-500', 'border-purple-600'],
                                'Banque' => ['bg-blue-500', 'border-blue-600']
                            ];
                        @endphp
                        
                        <svg class="w-full h-full transform -rotate-90" viewBox="0 0 200 200">
                            <circle cx="100" cy="100" r="90" fill="none" stroke="#e5e7eb" stroke-width="20" class="dark:stroke-gray-600" />
                            
                            @foreach($dashboardData['analytics']['modes_paiement'] as $mode)
                                @php
                                    $percentage = $totalModes > 0 ? ($mode['total'] / $totalModes) * 100 : 0;
                                    $angle = ($percentage / 100) * 360;
                                    $x1 = 100 + 90 * cos(deg2rad($cumulativeAngle));
                                    $y1 = 100 + 90 * sin(deg2rad($cumulativeAngle));
                                    $x2 = 100 + 90 * cos(deg2rad($cumulativeAngle + $angle));
                                    $y2 = 100 + 90 * sin(deg2rad($cumulativeAngle + $angle));
                                    $largeArcFlag = $angle > 180 ? 1 : 0;
                                    $cumulativeAngle += $angle;
                                    $color = $colors[$mode['mode']][0] ?? 'bg-gray-500';
                                @endphp
                                
                                @if($percentage > 0)
                                    <path d="M 100 100 L {{ $x1 }} {{ $y1 }} A 90 90 0 {{ $largeArcFlag }} 1 {{ $x2 }} {{ $y2 }} Z" 
                                          class="{{ str_replace('bg-', 'fill-', $color) }}" />
                                @endif
                            @endforeach
                        </svg>
                        
                        <!-- Pourcentage central -->
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="text-center">
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">100%</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Total</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Légende détaillée -->
                <div class="space-y-3">
                    @foreach($dashboardData['analytics']['modes_paiement'] as $mode)
                        @php
                            $colorClass = $colors[$mode['mode']][0] ?? 'bg-gray-500';
                        @endphp
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="w-4 h-4 rounded-full {{ $colorClass }}"></div>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">
                                        {{ $mode['mode'] === 'especes' ? 'Espèces' : ($mode['mode'] === 'MobileMoney' ? 'Mobile Money' : 'Banque') }}
                                    </p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $mode['nombre'] }} transactions</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-gray-900 dark:text-white">{{ $mode['pourcentage'] }}%</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ number_format($mode['total'], 0, ',', ' ') }} Ar
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <!-- Évolution temporelle détaillée -->
    @if(!empty($dashboardData['temporel']['evolution_mensuelle'] ?? []))
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Analyse Temporelle (12 mois)</h3>
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                        <span class="text-sm text-gray-600 dark:text-gray-400">Recettes</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                        <span class="text-sm text-gray-600 dark:text-gray-400">Dépenses</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                        <span class="text-sm text-gray-600 dark:text-gray-400">Bénéfice</span>
                    </div>
                </div>
            </div>

            <!-- Graphique -->
            <div class="relative h-80 mb-6">
                <div class="absolute inset-0 flex items-end justify-between">
                    @foreach($dashboardData['temporel']['evolution_mensuelle'] as $index => $mois)
                        @php
                            $maxValue = max(array_column($dashboardData['temporel']['evolution_mensuelle'], 'recettes'));
                            $maxValue = max($maxValue, max(array_column($dashboardData['temporel']['evolution_mensuelle'], 'depenses')));
                            $maxValue = max($maxValue, max(array_column($dashboardData['temporel']['evolution_mensuelle'], 'benefice')));
                            $maxValue = $maxValue > 0 ? $maxValue : 1;
                            
                            $heightRecettes = ($mois['recettes'] / $maxValue) * 100;
                            $heightDepenses = ($mois['depenses'] / $maxValue) * 100;
                            $heightBenefice = abs($mois['benefice'] / $maxValue) * 100;
                            $beneficePositif = $mois['benefice'] >= 0;
                        @endphp
                        <div class="flex-1 flex items-end justify-center space-x-1 px-1">
                            <!-- Barre recettes -->
                            <div class="w-3 bg-green-500 rounded-t-sm transition-all duration-300 hover:bg-green-600 relative group" 
                                 style="height: {{ $heightRecettes }}%">
                                <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-gray-900 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                                    {{ number_format($mois['recettes'], 0, ',', ' ') }} Ar
                                </div>
                            </div>
                            
                            <!-- Barre dépenses -->
                            <div class="w-3 bg-red-500 rounded-t-sm transition-all duration-300 hover:bg-red-600 relative group" 
                                 style="height: {{ $heightDepenses }}%">
                                <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-gray-900 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                                    {{ number_format($mois['depenses'], 0, ',', ' ') }} Ar
                                </div>
                            </div>
                            
                            <!-- Barre bénéfice -->
                            <div class="w-3 {{ $beneficePositif ? 'bg-blue-500 hover:bg-blue-600' : 'bg-orange-500 hover:bg-orange-600' }} rounded-t-sm transition-all duration-300 relative group" 
                                 style="height: {{ $heightBenefice }}%">
                                <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-gray-900 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                                    {{ $beneficePositif ? '+' : '-' }}{{ number_format(abs($mois['benefice']), 0, ',', ' ') }} Ar
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Statistiques du graphique -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                @php
                    $moyenneRecettes = array_sum(array_column($dashboardData['temporel']['evolution_mensuelle'], 'recettes')) / count($dashboardData['temporel']['evolution_mensuelle']);
                    $moyenneDepenses = array_sum(array_column($dashboardData['temporel']['evolution_mensuelle'], 'depenses')) / count($dashboardData['temporel']['evolution_mensuelle']);
                    $moyenneBenefice = array_sum(array_column($dashboardData['temporel']['evolution_mensuelle'], 'benefice')) / count($dashboardData['temporel']['evolution_mensuelle']);
                    $moisPositifs = count(array_filter($dashboardData['temporel']['evolution_mensuelle'], function($m) { return $m['benefice'] > 0; }));
                @endphp
                
                <div class="text-center">
                    <p class="text-sm text-green-600 dark:text-green-400 font-medium">Recettes moy.</p>
                    <p class="text-lg font-bold text-gray-900 dark:text-white">
                        {{ number_format($moyenneRecettes, 0, ',', ' ') }} Ar
                    </p>
                </div>
                
                <div class="text-center">
                    <p class="text-sm text-red-600 dark:text-red-400 font-medium">Dépenses moy.</p>
                    <p class="text-lg font-bold text-gray-900 dark:text-white">
                        {{ number_format($moyenneDepenses, 0, ',', ' ') }} Ar
                    </p>
                </div>
                
                <div class="text-center">
                    <p class="text-sm text-blue-600 dark:text-blue-400 font-medium">Bénéfice moy.</p>
                    <p class="text-lg font-bold {{ $moyenneBenefice >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                        {{ $moyenneBenefice >= 0 ? '+' : '' }}{{ number_format($moyenneBenefice, 0, ',', ' ') }} Ar
                    </p>
                </div>
                
                <div class="text-center">
                    <p class="text-sm text-purple-600 dark:text-purple-400 font-medium">Mois positifs</p>
                    <p class="text-lg font-bold text-gray-900 dark:text-white">
                        {{ $moisPositifs }}/12
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- Insights et recommandations -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-800 dark:to-gray-700 p-6 rounded-xl border border-blue-200 dark:border-gray-600">
        <div class="flex items-start space-x-4">
            <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-lg">
                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                </svg>
            </div>
            <div class="flex-1">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">💡 Insights & Recommandations</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-3">
                        <h4 class="font-medium text-gray-900 dark:text-white">Points forts:</h4>
                        <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                            @if(($dashboardData['financier']['benefice_perte'] ?? 0) > 0)
                                <li class="flex items-start">
                                    <svg class="w-4 h-4 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Catégorie rentable avec un bénéfice positif
                                </li>
                            @endif
                            
                            @if(($dashboardData['budget']['pourcentage_utilise'] ?? 0) < 80)
                                <li class="flex items-start">
                                    <svg class="w-4 h-4 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Bonne maîtrise du budget ({{ number_format($dashboardData['budget']['pourcentage_utilise'] ?? 0, 1) }}%)
                                </li>
                            @endif
                            
                            @if(count($dashboardData['analytics']['top_partenaires'] ?? []) > 2)
                                <li class="flex items-start">
                                    <svg class="w-4 h-4 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Portefeuille de partenaires diversifié
                                </li>
                            @endif
                        </ul>
                    </div>
                    
                    <div class="space-y-3">
                        <h4 class="font-medium text-gray-900 dark:text-white">À améliorer:</h4>
                        <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                            @if(($dashboardData['budget']['budget_depasse'] ?? false))
                                <li class="flex items-start">
                                    <svg class="w-4 h-4 text-red-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01" />
                                    </svg>
                                    Budget dépassé - réviser les objectifs
                                </li>
                            @endif
                            
                            @if(($dashboardData['financier']['benefice_perte'] ?? 0) < 0)
                                <li class="flex items-start">
                                    <svg class="w-4 h-4 text-red-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01" />
                                    </svg>
                                    Optimiser la rentabilité
                                </li>
                            @endif
                            
                            @if(count($dashboardData['analytics']['modes_paiement'] ?? []) == 1)
                                <li class="flex items-start">
                                    <svg class="w-4 h-4 text-yellow-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01" />
                                    </svg>
                                    Diversifier les modes de paiement
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>