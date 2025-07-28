<div class="space-y-4">
    <!-- En-tête -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <h3 class="text-lg font-medium text-gray-900">Déchargements</h3>
        <button wire:click="createDechargement" 
                class="inline-flex items-center px-3 py-1.5 sm:px-4 sm:py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition">
            <svg class="w-4 h-4 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            <span class="whitespace-nowrap">Ajouter déchargement</span>
        </button>
    </div>

    @if($voyage->dechargements->count() > 0)
        <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 rounded-lg">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-2 sm:px-6 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Référence</th>
                            <th class="px-3 py-2 sm:px-6 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Type</th>
                            <th class="px-3 py-2 sm:px-6 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">Propriétaire</th>
                            <th class="px-3 py-2 sm:px-6 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Produit</th>
                            <th class="px-3 py-2 sm:px-6 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Poids</th>
                            <th class="px-3 py-2 sm:px-6 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Montant</th>
                            <th class="px-3 py-2 sm:px-6 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Statut</th>
                            <th class="px-3 py-2 sm:px-6 sm:py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($voyage->dechargements as $dechargement)
                            <tr class="hover:bg-gray-50">
                                <!-- Référence -->
                                <td class="px-3 py-3 sm:px-6 sm:py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $dechargement->reference }}
                                </td>
                                
                                <!-- Type -->
                                <td class="px-3 py-3 sm:px-6 sm:py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                        {{ $dechargement->type === 'vente' ? 'bg-green-100 text-green-800' : 
                                           ($dechargement->type === 'retour' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800') }}">
                                        {{ ucfirst($dechargement->type) }}
                                    </span>
                                </td>
                                
                                <!-- Propriétaire (masqué sur mobile) -->
                                <td class="px-3 py-3 sm:px-6 sm:py-4 whitespace-nowrap text-sm text-gray-900 hidden sm:table-cell">
                                    <div>
                                        <div class="font-medium">{{ $dechargement->chargement->proprietaire_nom ?? 'N/A' }}</div>
                                        @if($dechargement->chargement->proprietaire_contact)
                                            <div class="text-xs text-gray-500">{{ $dechargement->chargement->proprietaire_contact }}</div>
                                        @endif
                                    </div>
                                </td>
                                
                                <!-- Produit -->
                                <td class="px-3 py-3 sm:px-6 sm:py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $dechargement->produit->nom ?? 'N/A' }}
                                </td>
                                
                                <!-- Poids -->
                                <td class="px-3 py-3 sm:px-6 sm:py-4 whitespace-nowrap text-sm text-gray-900">
                                    <div>
                                        {{ number_format($dechargement->poids_depart_kg, 0) }} kg
                                        @if($dechargement->poids_arrivee_kg && $dechargement->poids_arrivee_kg != $dechargement->poids_depart_kg)
                                            <div class="text-xs text-gray-500">Arrivée: {{ number_format($dechargement->poids_arrivee_kg, 0) }} kg</div>
                                        @endif
                                    </div>
                                </td>
                                
                                <!-- Montant -->
                                <td class="px-3 py-3 sm:px-6 sm:py-4 whitespace-nowrap text-sm text-gray-900">
                                    @if($dechargement->montant_total_mga)
                                        <div class="font-medium">{{ number_format($dechargement->montant_total_mga, 0) }} MGA</div>
                                        @if($dechargement->reste_mga > 0)
                                            <div class="text-xs text-red-500">Reste: {{ number_format($dechargement->reste_mga, 0) }} MGA</div>
                                        @endif
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                
                                <!-- Statut -->
                                <td class="px-3 py-3 sm:px-6 sm:py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                        {{ $dechargement->statut_commercial === 'vendu' ? 'bg-green-100 text-green-800' : 
                                           ($dechargement->statut_commercial === 'retourne' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                        {{ ucfirst($dechargement->statut_commercial) }}
                                    </span>
                                </td>
                                
                                <!-- Actions -->
                                <td class="px-3 py-3 sm:px-6 sm:py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-1 sm:space-x-2">
                                        <!-- Bouton Détail -->
                                        <button wire:click="viewDechargementDetail({{ $dechargement->id }})" 
                                                class="text-indigo-600 hover:text-indigo-900 p-1 rounded hover:bg-indigo-50"
                                                title="Voir détails">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </button>
                                        <button wire:click="editDechargement({{ $dechargement->id }})" 
                                                class="text-blue-600 hover:text-blue-900 p-1 rounded hover:bg-blue-50"
                                                title="Modifier">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>
                                        <button 
                                            wire:click="deleteDechargement({{ $dechargement->id }})"
                                            wire:confirm="Êtes-vous sûr de vouloir supprimer ce déchargement ?"
                                            class="text-red-600 hover:text-red-900 p-1 rounded hover:bg-red-50"
                                            title="Supprimer">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <!-- État vide -->
        <div class="text-center py-8 sm:py-12 bg-white rounded-lg shadow">
            <svg class="mx-auto h-10 w-10 sm:h-12 sm:w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
            <h3 class="mt-2 text-sm sm:text-base font-medium text-gray-900">Aucun déchargement</h3>
            <p class="mt-1 text-xs sm:text-sm text-gray-500">Ajoutez les opérations de déchargement du voyage.</p>
            <div class="mt-4">
                <button wire:click="createDechargement" 
                        class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs sm:text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    Ajouter un déchargement
                </button>
            </div>
        </div>
    @endif
</div>