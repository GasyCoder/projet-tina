
<div>
    {{-- En-tête avec filtres et actions --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 space-y-4 sm:space-y-0">
        <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-4">
            <div class="relative">
                <input type="text" wire:model.live="search" placeholder="Rechercher..." 
                       class="pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>

            <select wire:model.live="filterMotif" class="border border-gray-300 rounded-md px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">Tous les motifs</option>
                <option value="non-conformite">Non-conformité</option>
                <option value="excedent">Excédent</option>
                <option value="defaut-qualite">Défaut de qualité</option>
                <option value="autre">Autre</option>
            </select>

            <select wire:model.live="filterStatut" class="border border-gray-300 rounded-md px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">Tous les statuts</option>
                <option value="en-traitement">En traitement</option>
                <option value="traite">Traité</option>
                <option value="refuse">Refusé</option>
            </select>
        </div>

        <div class="flex space-x-2">
            <button wire:click="$toggle('showCreateModal')" 
                    class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-md text-sm font-medium flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                </svg>
                Enregistrer Retour
            </button>
        </div>
    </div>

    {{-- Statistiques rapides --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-orange-50 p-4 rounded-lg">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-orange-600">Retours du mois</p>
                    <p class="text-2xl font-semibold text-orange-900">{{ $retoursMois }}</p>
                </div>
            </div>
        </div>
        <div class="bg-yellow-50 p-4 rounded-lg">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-yellow-600">En traitement</p>
                    <p class="text-2xl font-semibold text-yellow-900">{{ $retoursEnTraitement }}</p>
                </div>
            </div>
        </div>
        <div class="bg-green-50 p-4 rounded-lg">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-green-600">Traités</p>
                    <p class="text-2xl font-semibold text-green-900">{{ $retoursTraites }}</p>
                </div>
            </div>
        </div>
        <div class="bg-red-50 p-4 rounded-lg">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.618 5.984A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-red-600">Poids total retourné</p>
                    <p class="text-2xl font-semibold text-red-900">{{ number_format($poidsRetourne) }} kg</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Tableau des retours --}}
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th wire:click="sortBy('date')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                        Date
                        @if($sortField === 'date')
                            @if($sortDirection === 'asc') ↑ @else ↓ @endif
                        @endif
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">N° Vente</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produit</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Motif</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Poids (kg)</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($retours as $retour)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $retour->date->format('d/m/Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $retour->vente->numero ?? 'N/A' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $retour->produit->nom }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $retour->motif }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ number_format($retour->poids) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($retour->statut === 'en-traitement')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">En traitement</span>
                        @elseif($retour->statut === 'traite')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Traité</span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Refusé</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <button wire:click="viewRetour({{ $retour->id }})" class="text-indigo-600 hover:text-indigo-900">
                                Voir
                            </button>
                            @if($retour->statut === 'en-traitement')
                            <button wire:click="traiterRetour({{ $retour->id }})" class="text-green-600 hover:text-green-900">
                                Traiter
                            </button>
                            <button wire:click="refuserRetour({{ $retour->id }})" class="text-red-600 hover:text-red-900">
                                Refuser
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                        Aucun retour trouvé
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $retours->links() }}
    </div>

    {{-- Modal de création/édition --}}
    @if($showCreateModal)
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Enregistrer un Retour</h3>
            
            <form wire:submit="createRetour">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Rechercher vente</label>
                        <div class="flex">
                            <input type="text" wire:model.live="searchVente" placeholder="N° vente ou client" 
                                   class="flex-1 border border-gray-300 rounded-l-md px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <button type="button" wire:click="searchVentes" 
                                    class="bg-gray-100 border border-l-0 border-gray-300 rounded-r-md px-4 py-2 text-gray-600 hover:bg-gray-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </button>
                        </div>
                        @if($ventesFound && count($ventesFound) > 0)
                        <div class="mt-2 border border-gray-300 rounded-md max-h-40 overflow-y-auto">
                            @foreach($ventesFound as $vente)
                            <div wire:click="selectVente({{ $vente->id }})" 
                                 class="p-2 hover:bg-gray-100 cursor-pointer border-b">
                                <span class="font-medium">{{ $vente->numero }}</span> - 
                                {{ $vente->client }} - {{ $vente->produit->nom }}
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>

                    @if($selectedVente)
                    <div class="p-3 bg-blue-50 rounded-lg">
                        <h4 class="font-medium text-blue-900">Vente sélectionnée</h4>
                        <p class="text-sm text-blue-700">
                            {{ $selectedVente->numero }} - {{ $selectedVente->client }} - 
                            {{ $selectedVente->produit->nom }} ({{ number_format($selectedVente->poids) }} kg)
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Quantité retournée (kg)</label>
                            <input type="number" wire:model="form.poids" max="{{ $selectedVente->poids }}"
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <p class="text-xs text-gray-500">Max: {{ number_format($selectedVente->poids) }} kg</p>
                            @error('form.poids') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Motif du retour</label>
                            <select wire:model="form.motif" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Sélectionner un motif</option>
                                <option value="non-conformite">Non-conformité</option>
                                <option value="excedent">Excédent</option>
                                <option value="defaut-qualite">Défaut de qualité</option>
                                <option value="autre">Autre</option>
                            </select>
                            @error('form.motif') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description détaillée</label>
                            <textarea wire:model="form.description" rows="3" 
                                      class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500"
                                      placeholder="Décrivez le problème ou la raison du retour..."></textarea>
                        </div>

                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Photos (optionnel)</label>
                            <input type="file" wire:model="photos" multiple accept="image/*" 
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <p class="text-xs text-gray-500 mt-1">PNG, JPG, GIF jusqu'à 10MB par fichier</p>
                        </div>
                    </div>
                    @endif
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" wire:click="resetForm" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                        Annuler
                    </button>
                    <button type="submit" {{ !$selectedVente ? 'disabled' : '' }}
                            class="px-4 py-2 bg-orange-600 text-white rounded-md hover:bg-orange-700 disabled:opacity-50">
                        Enregistrer le retour
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>