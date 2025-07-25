<div class="space-y-4">
    <!-- En-tête -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <h3 class="text-lg font-medium text-gray-900">Chargements</h3>
        <button wire:click="createChargement" 
                class="inline-flex items-center px-3 py-1.5 sm:px-4 sm:py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
            <svg class="w-4 h-4 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            <span class="whitespace-nowrap">Ajouter chargement</span>
        </button>
    </div>

    @if($voyage->chargements->count() > 0)
        <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 rounded-lg">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-2 sm:px-6 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Référence</th>
                            <th class="px-3 py-2 sm:px-6 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Chargeur</th>
                            <th class="px-3 py-2 sm:px-6 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">Propriétaire</th>
                            <th class="px-3 py-2 sm:px-6 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Produit</th>
                            <th class="px-3 py-2 sm:px-6 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Quantité</th>
                            <th class="px-3 py-2 sm:px-6 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Poids</th>
                            <th class="px-3 py-2 sm:px-6 sm:py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($voyage->chargements as $chargement)
                            <tr class="hover:bg-gray-50">
                                <!-- Référence -->
                                <td class="px-3 py-3 sm:px-6 sm:py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $chargement->reference }}
                                </td>
                                
                                <!-- Chargeur -->
                                <td class="px-3 py-3 sm:px-6 sm:py-4 whitespace-nowrap text-sm text-gray-900">
                                    <div>
                                        <div class="font-medium">{{ $chargement->chargeur_nom ?: 'N/A' }}</div>
                                        @if($chargement->chargeur_contact)
                                            <div class="text-xs text-gray-500">{{ $chargement->chargeur_contact }}</div>
                                        @endif
                                    </div>
                                </td>
                                
                                <!-- Propriétaire (masqué sur mobile) -->
                                <td class="px-3 py-3 sm:px-6 sm:py-4 whitespace-nowrap text-sm text-gray-900 hidden sm:table-cell">
                                    <div>
                                        <div class="font-medium">{{ $chargement->proprietaire_nom ?: 'N/A' }}</div>
                                        @if($chargement->proprietaire_contact)
                                            <div class="text-xs text-gray-500">{{ $chargement->proprietaire_contact }}</div>
                                        @endif
                                    </div>
                                </td>
                                
                                <!-- Produit -->
                                <td class="px-3 py-3 sm:px-6 sm:py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $chargement->produit->nom ?? 'N/A' }}
                                </td>
                                
                                <!-- Quantité -->
                                <td class="px-3 py-3 sm:px-6 sm:py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $chargement->sacs_pleins_depart }}
                                    @if($chargement->sacs_demi_depart > 0)
                                        + {{ $chargement->sacs_demi_depart }}/2
                                    @endif
                                    sacs
                                </td>
                                
                                <!-- Poids -->
                                <td class="px-3 py-3 sm:px-6 sm:py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ number_format($chargement->poids_depart_kg, 0) }} kg
                                </td>
                                
                                <!-- Actions -->
                                <td class="px-3 py-3 sm:px-6 sm:py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-1 sm:space-x-2">
                                        <button wire:click="editChargement({{ $chargement->id }})" 
                                                class="text-blue-600 hover:text-blue-900 p-1 rounded hover:bg-blue-50"
                                                title="Modifier">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>
                                        <button 
                                            wire:click="deleteChargement({{ $chargement->id }})"
                                            wire:confirm="Êtes-vous sûr de vouloir supprimer ce chargement ?"
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-4.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 009.586 13H7"/>
            </svg>
            <h3 class="mt-2 text-sm sm:text-base font-medium text-gray-900">Aucun chargement</h3>
            <p class="mt-1 text-xs sm:text-sm text-gray-500">Commencez par ajouter un chargement à ce voyage.</p>
            <div class="mt-4">
                <button wire:click="createChargement" 
                        class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs sm:text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Ajouter un chargement
                </button>
            </div>
        </div>
    @endif
</div>