<div class="space-y-4 sm:space-y-6">
    <h3 class="text-lg font-medium text-gray-900 dark:text-white">📊 Synthèse du voyage</h3>
    
    {{-- Métriques principales en haut --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 text-center">
            <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $voyage->chargements->count() }}</div>
            <div class="text-sm text-blue-700 dark:text-blue-300">Chargements</div>
        </div>
        
        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4 text-center">
            <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $voyage->dechargements->count() }}</div>
            <div class="text-sm text-green-700 dark:text-green-300">Déchargements</div>
        </div>
        
        <div class="bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-lg p-4 text-center">
            <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ number_format($totalPoidsCharge, 0) }}</div>
            <div class="text-sm text-purple-700 dark:text-purple-300">kg Chargés</div>
        </div>
        
        <div class="bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800 rounded-lg p-4 text-center">
            <div class="text-2xl font-bold text-orange-600 dark:text-orange-400">{{ number_format($totalPoidsDecharge, 0) }}</div>
            <div class="text-sm text-orange-700 dark:text-orange-300">kg Déchargés</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
        
        {{-- Chargements par propriétaire --}}
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-4 sm:p-5 border border-gray-100 dark:border-gray-700">
            <h4 class="text-base font-medium text-gray-900 dark:text-white mb-3 flex items-center">
                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Chargements par propriétaire
            </h4>
            
            @php
                $chargementsParProprietaire = $voyage->chargements->groupBy('proprietaire_nom');
            @endphp
            
            @if($chargementsParProprietaire->count() > 0)
                <div class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach($chargementsParProprietaire as $proprietaire => $chargements)
                        @php
                            $totalPoids = $chargements->sum('poids_depart_kg');
                            $nbChargements = $chargements->count();
                            $produits = $chargements->pluck('produit.nom')->filter()->unique()->implode(', ');
                        @endphp
                        <div class="py-3">
                            <div class="flex justify-between items-start">
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                        👤 {{ $proprietaire ?: 'Non spécifié' }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        {{ $nbChargements }} chargement{{ $nbChargements > 1 ? 's' : '' }}
                                        @if($produits) • {{ $produits }} @endif
                                    </div>
                                </div>
                                <div class="text-right ml-3">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ number_format($totalPoids, 0) }} kg
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ number_format($totalPoids / ($totalPoidsCharge ?: 1) * 100, 1) }}%
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-500 dark:text-gray-400 py-2">Aucun chargement enregistré</p>
            @endif
        </div>

        {{-- Écarts et Pertes/Gains --}}
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-4 sm:p-5 border border-gray-100 dark:border-gray-700">
            <h4 class="text-base font-medium text-gray-900 dark:text-white mb-3 flex items-center">
                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                Écarts et Performance
            </h4>
            
            @php
                $ecartPoids = $totalPoidsCharge - $totalPoidsDecharge;
                $pourcentagePerte = $totalPoidsCharge > 0 ? ($ecartPoids / $totalPoidsCharge) * 100 : 0;
            @endphp
            
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600 dark:text-gray-300">Poids chargé</span>
                    <span class="text-sm font-medium">{{ number_format($totalPoidsCharge, 0) }} kg</span>
                </div>
                
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600 dark:text-gray-300">Poids déchargé</span>
                    <span class="text-sm font-medium">{{ number_format($totalPoidsDecharge, 0) }} kg</span>
                </div>
                
                <div class="flex justify-between items-center pt-3 border-t border-gray-100 dark:border-gray-700">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Écart total</span>
                    <span class="text-sm font-medium {{ $ecartPoids > 0 ? 'text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/30' : ($ecartPoids < 0 ? 'text-green-600 dark:text-green-400 bg-green-50 dark:bg-green-900/30' : 'text-gray-600 dark:text-gray-400') }} px-2 py-1 rounded">
                        {{ $ecartPoids > 0 ? '-' : ($ecartPoids < 0 ? '+' : '') }}{{ number_format(abs($ecartPoids), 0) }} kg
                    </span>
                </div>
                
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600 dark:text-gray-300">% Perte/Gain</span>
                    <span class="text-sm font-medium {{ $pourcentagePerte > 5 ? 'text-red-600 dark:text-red-400' : ($pourcentagePerte > 0 ? 'text-yellow-600 dark:text-yellow-400' : 'text-green-600 dark:text-green-400') }}">
                        {{ $pourcentagePerte > 0 ? '-' : '+' }}{{ number_format(abs($pourcentagePerte), 1) }}%
                    </span>
                </div>

                {{-- Indicateur de performance --}}
                @if($pourcentagePerte > 10)
                    <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded p-2 mt-2">
                        <div class="text-xs text-red-700 dark:text-red-300">⚠️ Perte importante - Vérification recommandée</div>
                    </div>
                @elseif($pourcentagePerte > 5)
                    <div class="bg-yellow-50 dark:bg-yellow-900/30 border border-yellow-200 dark:border-yellow-800 rounded p-2 mt-2">
                        <div class="text-xs text-yellow-700 dark:text-yellow-300">⚠️ Perte modérée - Surveiller</div>
                    </div>
                @elseif($pourcentagePerte <= 2)
                    <div class="bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 rounded p-2 mt-2">
                        <div class="text-xs text-green-700 dark:text-green-300">✅ Performance excellente</div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Résumé financier --}}
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-4 sm:p-5 border border-gray-100 dark:border-gray-700">
            <h4 class="text-base font-medium text-gray-900 dark:text-white mb-3 flex items-center">
                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Résumé financier
            </h4>
            
            @php
                $ventesCount = $voyage->dechargements->where('type', 'vente')->count();
                $tauxPaiement = $totalVentes > 0 ? ($totalPaiements / $totalVentes) * 100 : 0;
            @endphp
            
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600 dark:text-gray-300">Nombre de ventes</span>
                    <span class="text-sm font-medium">{{ $ventesCount }}</span>
                </div>
                
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600 dark:text-gray-300">CA Total ventes</span>
                    <span class="text-sm font-medium bg-blue-50 dark:bg-blue-900/30 px-2 py-1 rounded">
                        {{ number_format($totalVentes ?: 0, 0) }} MGA
                    </span>
                </div>
                
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600 dark:text-gray-300">Total encaissé</span>
                    <span class="text-sm font-medium text-green-600 dark:text-green-400 bg-green-50 dark:bg-green-900/30 px-2 py-1 rounded">
                        {{ number_format($totalPaiements ?: 0, 0) }} MGA
                    </span>
                </div>
                
                <div class="flex justify-between items-center pt-3 border-t border-gray-100 dark:border-gray-700">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Reste à encaisser</span>
                    <span class="text-sm font-medium {{ ($totalReste ?: 0) > 0 ? 'text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/30' : 'text-gray-900 dark:text-gray-200 bg-gray-50 dark:bg-gray-700' }} px-2 py-1 rounded">
                        {{ number_format($totalReste ?: 0, 0) }} MGA
                    </span>
                </div>
                
                @if($totalVentes > 0)
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600 dark:text-gray-300">Taux d'encaissement</span>
                        <span class="text-sm font-medium {{ $tauxPaiement >= 100 ? 'text-green-600 dark:text-green-400' : ($tauxPaiement >= 80 ? 'text-yellow-600 dark:text-yellow-400' : 'text-red-600 dark:text-red-400') }}">
                            {{ number_format($tauxPaiement, 1) }}%
                        </span>
                    </div>
                @endif
            </div>
        </div>

        {{-- Statuts des opérations --}}
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-4 sm:p-5 border border-gray-100 dark:border-gray-700">
            <h4 class="text-base font-medium text-gray-900 dark:text-white mb-3 flex items-center">
                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Statuts des opérations
            </h4>
            
            @php
                $statutsCommerce = $voyage->dechargements->groupBy('statut_commercial');
                $typesDechargement = $voyage->dechargements->groupBy('type');
            @endphp
            
            <div class="space-y-3">
                {{-- Types de déchargement --}}
                <div class="text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wide">Types d'opérations</div>
                @foreach($typesDechargement as $type => $operations)
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600 dark:text-gray-300 capitalize">{{ str_replace('_', ' ', $type) }}</span>
                        <span class="text-sm font-medium px-2 py-1 bg-gray-100 dark:bg-gray-700 rounded">
                            {{ $operations->count() }}
                        </span>
                    </div>
                @endforeach
                
                {{-- Statuts commerciaux pour les ventes --}}
                @if($ventesCount > 0)
                    <div class="pt-3 border-t border-gray-100 dark:border-gray-700">
                        <div class="text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wide mb-2">Statuts commerciaux</div>
                        @foreach($statutsCommerce as $statut => $operations)
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-300 capitalize">{{ str_replace('_', ' ', $statut) }}</span>
                                <span class="text-sm font-medium 
                                    @if($statut === 'vendu') text-green-600 dark:text-green-400 bg-green-50 dark:bg-green-900/30
                                    @elseif($statut === 'en_attente') text-yellow-600 dark:text-yellow-400 bg-yellow-50 dark:bg-yellow-900/30
                                    @elseif($statut === 'retourne') text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/30
                                    @else text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30 @endif
                                    px-2 py-1 rounded">
                                    {{ $operations->count() }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Observation du voyage --}}
    @if($voyage->observation)
        <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-100 dark:border-yellow-800 rounded-lg p-4 sm:p-5">
            <div class="flex">
                <svg class="h-5 w-5 text-yellow-400 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                <div>
                    <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-1">📝 Observations du voyage</h4>
                    <p class="text-sm text-gray-700 dark:text-gray-300">{{ $voyage->observation }}</p>
                </div>
            </div>
        </div>
    @endif

    {{-- Actions rapides --}}
    <div class="bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4 sm:p-5">
        <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-3">⚡ Actions rapides</h4>
        <div class="flex flex-wrap gap-2">
            <button wire:click="setActiveTab('chargements')" class="inline-flex items-center px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 text-sm rounded-md hover:bg-blue-200 dark:hover:bg-blue-800/50">
                📦 Voir chargements ({{ $voyage->chargements->count() }})
            </button>
            <button wire:click="setActiveTab('dechargements')" class="inline-flex items-center px-3 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 text-sm rounded-md hover:bg-green-200 dark:hover:bg-green-800/50">
                🚚 Voir déchargements ({{ $voyage->dechargements->count() }})
            </button>
            @if($totalReste > 0)
                <span class="inline-flex items-center px-3 py-1 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 text-sm rounded-md">
                    💰 {{ number_format($totalReste, 0) }} MGA à encaisser
                </span>
            @endif
        </div>
    </div>
</div>