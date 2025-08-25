{{-- resources/views/livewire/categorie/partials/budget-tab.blade.php --}}
<div class="space-y-6">
    <!-- Vue d'ensemble du budget -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Statut principal -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Suivi Budgétaire</h3>
                    <button wire:click="openBudgetModal" 
                            class="inline-flex items-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Modifier
                    </button>
                </div>

                <!-- Informations budget -->
                <div class="grid grid-cols-2 gap-6 mb-6">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Budget alloué</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ number_format($categorie->budget, 0, ',', ' ') }} Ar
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Objectif mensuel</p>
                        <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                            {{ number_format($categorie->objectif_mensuel ?? 0, 0, ',', ' ') }} Ar
                        </p>
                    </div>
                </div>

                <!-- Barre de progression principale -->
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Utilisation du budget</span>
                        <span class="text-sm font-bold {{ ($dashboardData['budget']['budget_depasse'] ?? false) ? 'text-red-600 dark:text-red-400' : 'text-gray-900 dark:text-white' }}">
                            {{ number_format($dashboardData['budget']['pourcentage_utilise'] ?? 0, 1) }}%
                        </span>
                    </div>
                    
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-4 relative">
                        <!-- Seuil d'alerte -->
                        <div class="absolute top-0 h-4 w-0.5 bg-yellow-500" 
                             style="left: {{ $categorie->seuil_alerte ?? 80 }}%"></div>
                        
                        <!-- Progression -->
                        <div class="h-4 rounded-full transition-all duration-500 {{ ($dashboardData['budget']['budget_depasse'] ?? false) ? 'bg-red-500' : (($dashboardData['budget']['seuil_alerte_atteint'] ?? false) ? 'bg-yellow-500' : 'bg-green-500') }}" 
                             style="width: {{ min(100, $dashboardData['budget']['pourcentage_utilise'] ?? 0) }}%"></div>
                    </div>
                    
                    <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400 mt-1">
                        <span>0%</span>
                        <span class="text-yellow-600">Seuil: {{ $categorie->seuil_alerte ?? 80 }}%</span>
                        <span>100%</span>
                    </div>
                </div>

                <!-- Détails financiers -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="p-4 bg-green-50 dark:bg-green-900/20 rounded-lg border border-green-200 dark:border-green-700">
                        <p class="text-sm text-green-700 dark:text-green-300 font-medium">Montant consommé</p>
                        <p class="text-xl font-bold text-green-900 dark:text-green-100">
                            {{ number_format($dashboardData['budget']['montant_consomme'] ?? 0, 0, ',', ' ') }} Ar
                        </p>
                    </div>
                    
                    <div class="p-4 {{ ($dashboardData['budget']['budget_depasse'] ?? false) ? 'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-700' : 'bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-700' }} rounded-lg border">
                        <p class="text-sm {{ ($dashboardData['budget']['budget_depasse'] ?? false) ? 'text-red-700 dark:text-red-300' : 'text-blue-700 dark:text-blue-300' }} font-medium">
                            {{ ($dashboardData['budget']['budget_depasse'] ?? false) ? 'Dépassement' : 'Montant restant' }}
                        </p>
                        <p class="text-xl font-bold {{ ($dashboardData['budget']['budget_depasse'] ?? false) ? 'text-red-900 dark:text-red-100' : 'text-blue-900 dark:text-blue-100' }}">
                            {{ ($dashboardData['budget']['budget_depasse'] ?? false) ? '+' : '' }}{{ number_format(abs($dashboardData['budget']['montant_restant'] ?? 0), 0, ',', ' ') }} Ar
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alertes et statut -->
        <div class="space-y-4">
            <!-- Statut global -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Statut</h4>
                
                @if($dashboardData['budget']['budget_depasse'] ?? false)
                    <div class="flex items-center space-x-3 p-4 bg-red-50 dark:bg-red-900/20 rounded-lg border border-red-200 dark:border-red-700">
                        <div class="w-8 h-8 bg-red-100 dark:bg-red-900 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.346 15.5c-.77.833.192 2.5 1.732 2.5z" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-red-800 dark:text-red-200">Budget dépassé</p>
                            <p class="text-sm text-red-600 dark:text-red-400">Action requise</p>
                        </div>
                    </div>
                @elseif($dashboardData['budget']['seuil_alerte_atteint'] ?? false)
                    <div class="flex items-center space-x-3 p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg border border-yellow-200 dark:border-yellow-700">
                        <div class="w-8 h-8 bg-yellow-100 dark:bg-yellow-900 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-yellow-800 dark:text-yellow-200">Seuil d'alerte</p>
                            <p class="text-sm text-yellow-600 dark:text-yellow-400">Surveillance requise</p>
                        </div>
                    </div>
                @else
                    <div class="flex items-center space-x-3 p-4 bg-green-50 dark:bg-green-900/20 rounded-lg border border-green-200 dark:border-green-700">
                        <div class="w-8 h-8 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-green-800 dark:text-green-200">Budget maîtrisé</p>
                            <p class="text-sm text-green-600 dark:text-green-400">Situation saine</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Seuil d'alerte -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Paramètres</h4>
                
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Seuil d'alerte</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $categorie->seuil_alerte ?? 80 }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                            <div class="bg-yellow-500 h-2 rounded-full" style="width: {{ $categorie->seuil_alerte ?? 80 }}%"></div>
                        </div>
                    </div>
                    
                    <div class="pt-2 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600 dark:text-gray-400">Type de catégorie</span>
                            <span class="px-2 py-1 rounded-full text-xs font-medium {{ $categorie->type === 'recette' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                {{ ucfirst($categorie->type) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Projection fin de mois -->
    @if(!empty($dashboardData['budget']['projection_fin_mois'] ?? []))
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    Projection Fin de Mois
                </h3>
                <span class="text-sm text-gray-500 dark:text-gray-400">{{ now()->format('F Y') }}</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Projection montant -->
                <div class="text-center p-4 {{ ($dashboardData['budget']['projection_fin_mois']['depassement'] ?? false) ? 'bg-red-50 dark:bg-red-900/20' : 'bg-blue-50 dark:bg-blue-900/20' }} rounded-lg">
                    <p class="text-sm {{ ($dashboardData['budget']['projection_fin_mois']['depassement'] ?? false) ? 'text-red-600 dark:text-red-400' : 'text-blue-600 dark:text-blue-400' }} font-medium mb-2">
                        Montant projeté
                    </p>
                    <p class="text-2xl font-bold {{ ($dashboardData['budget']['projection_fin_mois']['depassement'] ?? false) ? 'text-red-900 dark:text-red-100' : 'text-blue-900 dark:text-blue-100' }}">
                        {{ ($dashboardData['budget']['projection_fin_mois']['projection'] ?? 0) >= 0 ? '+' : '' }}{{ number_format($dashboardData['budget']['projection_fin_mois']['projection'] ?? 0, 0, ',', ' ') }} Ar
                    </p>
                </div>

                <!-- Pourcentage projection -->
                <div class="text-center p-4 {{ ($dashboardData['budget']['projection_fin_mois']['depassement'] ?? false) ? 'bg-red-50 dark:bg-red-900/20' : 'bg-green-50 dark:bg-green-900/20' }} rounded-lg">
                    <p class="text-sm {{ ($dashboardData['budget']['projection_fin_mois']['depassement'] ?? false) ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }} font-medium mb-2">
                        % Budget projeté
                    </p>
                    <p class="text-2xl font-bold {{ ($dashboardData['budget']['projection_fin_mois']['depassement'] ?? false) ? 'text-red-900 dark:text-red-100' : 'text-green-900 dark:text-green-100' }}">
                        {{ number_format($dashboardData['budget']['projection_fin_mois']['pourcentage_projection'] ?? 0, 1) }}%
                    </p>
                </div>

                <!-- Jours restants -->
                <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <p class="text-sm text-gray-600 dark:text-gray-400 font-medium mb-2">Jours restants</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ now()->daysInMonth - now()->day }}
                    </p>
                </div>
            </div>

            @if($dashboardData['budget']['projection_fin_mois']['depassement'] ?? false)
                <div class="mt-6 p-4 bg-red-100 dark:bg-red-900/30 rounded-lg border border-red-200 dark:border-red-700">
                    <div class="flex items-start space-x-3">
                        <svg class="w-5 h-5 text-red-600 dark:text-red-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.346 15.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                        <div>
                            <p class="font-medium text-red-800 dark:text-red-200">Attention : Dépassement de budget prévu</p>
                            <p class="text-sm text-red-600 dark:text-red-400 mt-1">
                                Basé sur votre rythme actuel, vous risquez de dépasser votre budget de 
                                {{ number_format(abs($dashboardData['budget']['projection_fin_mois']['projection'] ?? 0) - $categorie->budget, 0, ',', ' ') }} Ar 
                                d'ici la fin du mois.
                            </p>
                        </div>
                    </div>
                </div>
            @else
                <div class="mt-6 p-4 bg-green-100 dark:bg-green-900/30 rounded-lg border border-green-200 dark:border-green-700">
                    <div class="flex items-start space-x-3">
                        <svg class="w-5 h-5 text-green-600 dark:text-green-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <div>
                            <p class="font-medium text-green-800 dark:text-green-200">Projection positive</p>
                            <p class="text-sm text-green-600 dark:text-green-400 mt-1">
                                À ce rythme, vous devriez rester dans votre budget avec une marge de 
                                {{ number_format($categorie->budget - abs($dashboardData['budget']['projection_fin_mois']['projection'] ?? 0), 0, ',', ' ') }} Ar.
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @endif

    <!-- Historique budgétaire -->
    @if(!empty($dashboardData['temporel']['evolution_mensuelle'] ?? []))
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Performance Budgétaire (12 mois)</h3>
                <div class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
                    <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                    <span>% Budget utilisé</span>
                </div>
            </div>

            <!-- Graphique performance budget -->
            <div class="relative h-64 mb-4">
                <div class="absolute inset-0 flex items-end justify-between space-x-1">
                    @foreach($dashboardData['temporel']['evolution_mensuelle'] as $mois)
                        @php
                            $volumeMois = ($mois['recettes'] + $mois['depenses']);
                            $budgetMensuel = $categorie->budget / 12; // Budget mensuel estimé
                            $pourcentageBudget = $budgetMensuel > 0 ? min(100, ($volumeMois / $budgetMensuel) * 100) : 0;
                        @endphp
                        <div class="flex-1 flex items-end justify-center">
                            <div class="w-8 rounded-t-sm transition-all duration-300 hover:opacity-80 relative group
                                {{ $pourcentageBudget > 100 ? 'bg-red-500' : ($pourcentageBudget > 80 ? 'bg-yellow-500' : 'bg-green-500') }}" 
                                 style="height: {{ min(100, $pourcentageBudget) }}%">
                                <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-gray-900 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                                    {{ number_format($pourcentageBudget, 1) }}%<br>
                                    {{ number_format($volumeMois, 0, ',', ' ') }} Ar
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Ligne de référence 100% -->
                <div class="absolute w-full border-t-2 border-dashed border-gray-400 dark:border-gray-500" style="bottom: 0%;">
                    <span class="absolute -top-6 right-0 text-xs text-gray-500 dark:text-gray-400">100%</span>
                </div>
            </div>

            <!-- Légendes des mois -->
            <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400">
                @foreach($dashboardData['temporel']['evolution_mensuelle'] as $mois)
                    <span class="transform -rotate-45 origin-top-left">{{ $mois['mois'] }}</span>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Conseils d'optimisation -->
    <div class="bg-gradient-to-r from-indigo-50 to-blue-50 dark:from-gray-800 dark:to-gray-700 p-6 rounded-xl border border-indigo-200 dark:border-gray-600">
        <div class="flex items-start space-x-4">
            <div class="p-3 bg-indigo-100 dark:bg-indigo-900 rounded-lg">
                <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">💡 Conseils d'Optimisation Budgétaire</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="font-medium text-gray-900 dark:text-white mb-3">Actions recommandées :</h4>
                        <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                            @if(($dashboardData['budget']['budget_depasse'] ?? false))
                                <li class="flex items-start">
                                    <svg class="w-4 h-4 text-red-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01" />
                                    </svg>
                                    Réviser le budget à la hausse ou réduire les dépenses
                                </li>
                            @else
                                <li class="flex items-start">
                                    <svg class="w-4 h-4 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Maintenir le rythme actuel
                                </li>
                            @endif
                            
                            <li class="flex items-start">
                                <svg class="w-4 h-4 text-blue-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Surveiller la projection de fin de mois
                            </li>
                            
                            <li class="flex items-start">
                                <svg class="w-4 h-4 text-purple-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                                Définir des objectifs mensuels réalistes
                            </li>
                        </ul>
                    </div>
                    
                    <div>
                        <h4 class="font-medium text-gray-900 dark:text-white mb-3">Bonnes pratiques :</h4>
                        <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                            <li class="flex items-start">
                                <span class="text-blue-500 mr-2 mt-0.5">•</span>
                                Réviser le budget trimestriellement
                            </li>
                            <li class="flex items-start">
                                <span class="text-blue-500 mr-2 mt-0.5">•</span>
                                Ajuster le seuil d'alerte selon vos besoins
                            </li>
                            <li class="flex items-start">
                                <span class="text-blue-500 mr-2 mt-0.5">•</span>
                                Documenter les dépassements pour analyse
                            </li>
                            <li class="flex items-start">
                                <span class="text-blue-500 mr-2 mt-0.5">•</span>
                                Comparer avec les catégories similaires
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>