
{{-- Vue 1: Dashboard Stock Principal --}}
{{-- resources/views/livewire/stocks/dashboard-stock.blade.php --}}
<div x-data="{ darkMode: $persist(false) }" :class="{ 'dark': darkMode }" class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Header avec filtres -->
    <div class="bg-white dark:bg-gray-800 shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">📊 Dashboard Stock</h1>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">Vue d'ensemble et analyse des performances</p>
                </div>
                
                <!-- Filtres rapides -->
                <div class="flex items-center space-x-4">
                    <select wire:model.live="periode" class="rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200">
                        <option value="jour">Aujourd'hui</option>
                        <option value="semaine">Cette semaine</option>
                        <option value="mois">Ce mois</option>
                        <option value="trimestre">Ce trimestre</option>
                        <option value="annee">Cette année</option>
                    </select>
                    
                    <select wire:model.live="filterDepot" class="rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200">
                        <option value="">Tous les dépôts</option>
                        @foreach($depots as $depot)
                            <option value="{{ $depot->id }}">{{ $depot->nom }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Métriques principales -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Valeur Stock Total -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg border-l-4 border-blue-500">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                <span class="text-white font-bold">💰</span>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Valeur Stock Total</dt>
                                <dd class="flex items-baseline">
                                    <div class="text-2xl font-semibold text-gray-900 dark:text-white">
                                        {{ number_format($metriques['valeur_stock_total'], 0, ',', ' ') }} Ar
                                    </div>
                                    @if(isset($tendances['valeur_stock']))
                                        <div class="ml-2 flex items-baseline text-sm font-semibold {{ $tendances['valeur_stock'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                            <span>{{ $tendances['valeur_stock'] >= 0 ? '↗' : '↘' }}</span>
                                            {{ abs(round($tendances['valeur_stock'], 1)) }}%
                                        </div>
                                    @endif
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Nombre de Ventes -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg border-l-4 border-green-500">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                <span class="text-white font-bold">🛒</span>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Ventes</dt>
                                <dd class="flex items-baseline">
                                    <div class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $metriques['nb_ventes'] }}</div>
                                    @if(isset($tendances['nb_ventes']))
                                        <div class="ml-2 flex items-baseline text-sm font-semibold {{ $tendances['nb_ventes'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                            <span>{{ $tendances['nb_ventes'] >= 0 ? '↗' : '↘' }}</span>
                                            {{ abs(round($tendances['nb_ventes'], 1)) }}%
                                        </div>
                                    @endif
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chiffre d'Affaires -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg border-l-4 border-purple-500">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center">
                                <span class="text-white font-bold">📈</span>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Chiffre d'Affaires</dt>
                                <dd class="flex items-baseline">
                                    <div class="text-2xl font-semibold text-gray-900 dark:text-white">
                                        {{ number_format($metriques['ca_ventes'], 0, ',', ' ') }} Ar
                                    </div>
                                    @if(isset($tendances['ca_ventes']))
                                        <div class="ml-2 flex items-baseline text-sm font-semibold {{ $tendances['ca_ventes'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                            <span>{{ $tendances['ca_ventes'] >= 0 ? '↗' : '↘' }}</span>
                                            {{ abs(round($tendances['ca_ventes'], 1)) }}%
                                        </div>
                                    @endif
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Alertes Actives -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg border-l-4 border-red-500">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center">
                                <span class="text-white font-bold">🚨</span>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Alertes Actives</dt>
                                <dd class="flex items-baseline">
                                    <div class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $metriques['nb_alertes_actives'] }}</div>
                                    @if(isset($tendances['nb_alertes']))
                                        <div class="ml-2 flex items-baseline text-sm font-semibold {{ $tendances['nb_alertes'] <= 0 ? 'text-green-600' : 'text-red-600' }}">
                                            <span>{{ $tendances['nb_alertes'] <= 0 ? '↘' : '↗' }}</span>
                                            {{ abs(round($tendances['nb_alertes'], 1)) }}%
                                        </div>
                                    @endif
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Indicateurs de Performance -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg mb-8">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">🎯 Indicateurs de Performance</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-6">
                    @foreach($indicateursPerformance as $indicateur => $valeur)
                        <div class="text-center">
                            <div class="text-2xl font-bold text-gray-900 dark:text-white">
                                {{ is_numeric($valeur) ? number_format($valeur, 1) : $valeur }}
                                @if(in_array($indicateur, ['satisfaction_client', 'taux_disponibilite', 'efficacite_transport']))%@endif
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400 capitalize">
                                {{ str_replace('_', ' ', $indicateur) }}
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 mt-2">
                                @php
                                    $pourcentage = match($indicateur) {
                                        'rotation_stock' => min(100, ($valeur / 4) * 100), // Optimal = 4 rotations
                                        'couverture_stock' => min(100, (30 / max(1, $valeur)) * 100), // Optimal = 30 jours
                                        default => min(100, $valeur)
                                    };
                                    $couleur = $pourcentage >= 75 ? 'bg-green-600' : ($pourcentage >= 50 ? 'bg-yellow-500' : 'bg-red-500');
                                @endphp
                                <div class="{{ $couleur }} h-2 rounded-full" style="width: {{ $pourcentage }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Graphiques -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Évolution des Mouvements -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">📊 Évolution des Mouvements</h3>
                </div>
                <div class="p-6">
                    <canvas id="chartMouvements" width="400" height="200"></canvas>
                </div>
            </div>

            <!-- Rotation par Produit -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">🔄 Rotation des Stocks</h3>
                </div>
                <div class="p-6">
                    <canvas id="chartRotation" width="400" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Sections d'information -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Alertes Récentes -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">🚨 Alertes Récentes</h3>
                </div>
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($alertes as $alerte)
                        <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center">
                                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ $alerte->urgence_badge['class'] }}">
                                            {{ $alerte->urgence_badge['text'] }}
                                        </span>
                                        <span class="ml-2 px-2 py-1 text-xs font-medium rounded-full {{ $alerte->type_badge['class'] }}">
                                            {{ $alerte->type_badge['text'] }}
                                        </span>
                                    </div>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $alerte->titre_alerte }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $alerte->produit->nom ?? 'N/A' }} - {{ $alerte->depot->nom ?? 'N/A' }}
                                    </p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500">{{ $alerte->date_alerte->diffForHumans() }}</p>
                                </div>
                                @if(!$alerte->alerte_vue)
                                    <button wire:click="marquerAlerteVue({{ $alerte->id }})" 
                                            class="text-blue-600 hover:text-blue-800 dark:text-blue-400">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                        </svg>
                                    </button>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center">
                            <div class="text-gray-400 dark:text-gray-500 text-4xl mb-4">✅</div>
                            <p class="text-gray-500 dark:text-gray-400">Aucune alerte active</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Top Produits -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">🏆 Top Produits</h3>
                </div>
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($topProduits as $index => $produit)
                        <div class="p-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 w-6 h-6 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-full flex items-center justify-center text-white text-xs font-bold">
                                        {{ $index + 1 }}
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $produit->produit->nom }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $produit->nb_ventes }} ventes</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ number_format($produit->total_ca, 0, ',', ' ') }} Ar
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ number_format($produit->total_quantite, 0, ',', ' ') }} kg
                                    </p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center">
                            <div class="text-gray-400 dark:text-gray-500 text-4xl mb-4">📦</div>
                            <p class="text-gray-500 dark:text-gray-400">Aucune vente sur la période</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Stocks Critiques -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">⚠️ Stocks Critiques</h3>
                </div>
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($stocksCritiques as $stock)
                        <div class="p-4">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $stock->produit->nom }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $stock->depot->nom }}</p>
                                    <div class="mt-1 flex items-center space-x-2">
                                        @if($stock->alerte_stock_bas)
                                            <span class="px-2 py-1 text-xs bg-orange-100 dark:bg-orange-900/30 text-orange-800 dark:text-orange-200 rounded">Stock bas</span>
                                        @endif
                                        @if($stock->alerte_peremption)
                                            <span class="px-2 py-1 text-xs bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-200 rounded">Péremption proche</span>
                                        @endif
                                        @if($stock->statut_stock === 'quarantaine')
                                            <span class="px-2 py-1 text-xs bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-200 rounded">Quarantaine</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ number_format($stock->quantite_kg, 0, ',', ' ') }} kg
                                    </p>
                                    @if($stock->date_peremption)
                                        <p class="text-xs text-red-500">
                                            Expire {{ $stock->date_peremption->diffForHumans() }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center">
                            <div class="text-gray-400 dark:text-gray-500 text-4xl mb-4">✅</div>
                            <p class="text-gray-500 dark:text-gray-400">Tous les stocks sont normaux</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Mouvements Récents -->
        <div class="mt-8 bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">🕒 Mouvements Récents</h3>
            </div>
            <div class="overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Produit</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Dépôt</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Quantité</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Utilisateur</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($mouvementsRecents as $mouvement)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ $mouvement->operation_badge['class'] }}">
                                        {{ $mouvement->operation_badge['text'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    {{ $mouvement->produit->nom ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $mouvement->depot->nom ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span class="px-2 py-1 text-xs font-medium rounded {{ $mouvement->sens_badge['class'] }}">
                                            {{ $mouvement->sens_badge['text'] }}
                                        </span>
                                        <span class="ml-2 text-sm text-gray-900 dark:text-white">
                                            {{ number_format(abs($mouvement->quantite_mouvement_kg), 0, ',', ' ') }} kg
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $mouvement->user_nom }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $mouvement->created_at->diffForHumans() }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                    Aucun mouvement récent
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Configuration des graphiques
        document.addEventListener('DOMContentLoaded', function() {
            // Graphique des mouvements
            const ctxMouvements = document.getElementById('chartMouvements').getContext('2d');
            new Chart(ctxMouvements, {
                type: 'line',
                data: {
                    labels: @json($chartMouvements->pluck('date')),
                    datasets: [{
                        label: 'Entrées',
                        data: @json($chartMouvements->pluck('entrees')),
                        borderColor: 'rgb(34, 197, 94)',
                        backgroundColor: 'rgba(34, 197, 94, 0.1)',
                        tension: 0.1
                    }, {
                        label: 'Sorties',
                        data: @json($chartMouvements->pluck('sorties')),
                        borderColor: 'rgb(239, 68, 68)',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Graphique de rotation
            const ctxRotation = document.getElementById('chartRotation').getContext('2d');
            new Chart(ctxRotation, {
                type: 'bar',
                data: {
                    labels: @json($chartRotationStock->pluck('produit')),
                    datasets: [{
                        label: 'Taux de rotation',
                        data: @json($chartRotationStock->pluck('rotation')),
                        backgroundColor: 'rgba(59, 130, 246, 0.8)',
                        borderColor: 'rgb(59, 130, 246)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
    @endpush
</div>