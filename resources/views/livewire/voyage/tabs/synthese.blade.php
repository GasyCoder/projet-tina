<div class="space-y-4 sm:space-y-6">
    <h3 class="text-lg font-medium text-gray-900">Synthèse du voyage</h3>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
        <!-- Résumé chargements par propriétaire -->
        <div class="bg-white shadow-sm rounded-lg p-4 sm:p-5 border border-gray-100">
            <h4 class="text-base font-medium text-gray-900 mb-3 flex items-center">
                <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                Chargements par propriétaire
            </h4>
            
            @php
                $chargementsParProprietaire = $voyage->chargements->groupBy('proprietaire.name');
            @endphp
            
            @if($chargementsParProprietaire->count() > 0)
                <div class="divide-y divide-gray-100">
                    @foreach($chargementsParProprietaire as $proprietaire => $chargements)
                        <div class="flex justify-between items-center py-3">
                            <span class="text-sm text-gray-600 truncate">{{ $proprietaire ?: 'Non spécifié' }}</span>
                            <span class="text-sm font-medium whitespace-nowrap ml-2">
                                {{ number_format($chargements->sum('poids_depart_kg'), 0) }} kg
                            </span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-500 py-2">Aucun chargement enregistré</p>
            @endif
        </div>

        <!-- Résumé financier -->
        <div class="bg-white shadow-sm rounded-lg p-4 sm:p-5 border border-gray-100">
            <h4 class="text-base font-medium text-gray-900 mb-3 flex items-center">
                <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Résumé financier
            </h4>
            
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">CA Total ventes</span>
                    <span class="text-sm font-medium bg-blue-50 px-2 py-1 rounded">
                        {{ number_format($totalVentes, 0) }} MGA
                    </span>
                </div>
                
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Total payé</span>
                    <span class="text-sm font-medium text-green-600 bg-green-50 px-2 py-1 rounded">
                        {{ number_format($totalPaiements, 0) }} MGA
                    </span>
                </div>
                
                <div class="flex justify-between items-center pt-3 border-t border-gray-100">
                    <span class="text-sm font-medium text-gray-700">Reste à encaisser</span>
                    <span class="text-sm font-medium {{ $totalReste > 0 ? 'text-red-600 bg-red-50' : 'text-gray-900 bg-gray-50' }} px-2 py-1 rounded">
                        {{ number_format($totalReste, 0) }} MGA
                    </span>
                </div>
            </div>
        </div>
    </div>

    @if($voyage->observation)
        <div class="bg-yellow-50 border border-yellow-100 rounded-lg p-4 sm:p-5">
            <div class="flex">
                <svg class="h-5 w-5 text-yellow-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <div>
                    <h4 class="text-sm font-medium text-gray-900 mb-1">Observations</h4>
                    <p class="text-sm text-gray-700">{{ $voyage->observation }}</p>
                </div>
            </div>
        </div>
    @endif
</div>