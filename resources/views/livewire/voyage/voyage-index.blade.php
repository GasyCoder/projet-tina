<div class="min-h-screen bg-gray-50 p-4 md:p-6">
    <div class="space-y-6 max-w-7xl mx-auto">
        <!-- En-tête -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Voyages</h1>
                <p class="text-sm text-gray-600">Gérez vos voyages de transport</p>
            </div>
            <button wire:click="create" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Nouveau voyage
            </button>
        </div>

        <!-- Alertes -->
        @if (session()->has('success'))
            <div class="bg-green-50 border border-green-200 rounded-md p-4">
                <div class="flex">
                    <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                    </svg>
                    <p class="ml-3 text-sm text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <!-- Stats rapides -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 md:gap-4">
            <div class="bg-white rounded-lg shadow p-3 md:p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0M15 17a2 2 0 104 0"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs font-medium text-gray-600">Total</p>
                        <p class="text-lg font-bold text-gray-900">{{ $stats['total'] }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-3 md:p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-yellow-100 rounded-lg">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs font-medium text-gray-600">En cours</p>
                        <p class="text-lg font-bold text-gray-900">{{ $stats['en_cours'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-3 md:p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 rounded-lg">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs font-medium text-gray-600">Terminés</p>
                        <p class="text-lg font-bold text-gray-900">{{ $stats['termine'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-3 md:p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-red-100 rounded-lg">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs font-medium text-gray-600">Annulés</p>
                        <p class="text-lg font-bold text-gray-900">{{ $stats['annule'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-3 md:p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-purple-100 rounded-lg">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs font-medium text-gray-600">Aujourd'hui</p>
                        <p class="text-lg font-bold text-gray-900">{{ $stats['aujourd_hui'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-3 md:p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-indigo-100 rounded-lg">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs font-medium text-gray-600">Cette semaine</p>
                        <p class="text-lg font-bold text-gray-900">{{ $stats['cette_semaine'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recherche et filtres -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-4 md:p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
                    <div class="flex-1 max-w-lg">
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input 
                                wire:model.live.debounce.300ms="search"
                                type="text"
                                placeholder="Rechercher par référence, origine, véhicule..."
                                class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                            >
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3">
                        <select wire:model.live="filterStatut" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                            <option value="">Tous statuts</option>
                            <option value="en_cours">En cours</option>
                            <option value="termine">Terminé</option>
                            <option value="annule">Annulé</option>
                        </select>
                        
                        <div class="text-sm text-gray-500 whitespace-nowrap">
                            {{ $voyages->total() }} voyage(s)
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th wire:click="sortBy('reference')" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                                <div class="flex items-center gap-1">
                                    <span>Référence</span>
                                    @if($sortField === 'reference')
                                        <svg class="w-4 h-4 {{ $sortDirection === 'asc' ? 'rotate-0' : 'rotate-180' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h6a1 1 0 110 2H4a1 1 0 01-1-1zM3 16a1 1 0 011-1h4a1 1 0 110 2H4a1 1 0 01-1-1z"/>
                                        </svg>
                                    @endif
                                </div>
                            </th>
                            <th wire:click="sortBy('date')" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                                <div class="flex items-center gap-1">
                                    <span>Date</span>
                                    @if($sortField === 'date')
                                        <svg class="w-4 h-4 {{ $sortDirection === 'asc' ? 'rotate-0' : 'rotate-180' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h6a1 1 0 110 2H4a1 1 0 01-1-1zM3 16a1 1 0 011-1h4a1 1 0 110 2H4a1 1 0 01-1-1z"/>
                                        </svg>
                                    @endif
                                </div>
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">Origine</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">Véhicule</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">Chauffeur</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">Chargements</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($voyages as $voyage)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="p-1 bg-blue-100 rounded mr-3">
                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0M15 17a2 2 0 104 0"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $voyage->reference }}</div>
                                            @if($voyage->observation)
                                                <div class="text-xs text-gray-500 sm:hidden">{{ Str::limit($voyage->observation, 20) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $voyage->date ? $voyage->date->format('d/m/Y') : 'N/A' }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 hidden sm:table-cell">
                                    <div class="flex items-center">
                                        <div class="p-1 bg-green-100 rounded mr-2">
                                            <svg class="w-3 h-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                        </div>
                                        <span class="truncate max-w-[120px]">{{ $voyage->origine->nom ?? 'N/A' }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 hidden md:table-cell">
                                    @if($voyage->vehicule)
                                        <div>
                                            <div class="font-medium">{{ $voyage->vehicule->immatriculation }}</div>
                                            <div class="text-gray-500 text-xs">{{ $voyage->vehicule->marque }} {{ $voyage->vehicule->modele }}</div>
                                        </div>
                                    @else
                                        <span class="text-gray-400">N/A</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 hidden lg:table-cell">
                                    @if($voyage->chauffeur)
                                        <div class="flex items-center">
                                            <div class="h-6 w-6 rounded-full bg-green-100 flex items-center justify-center mr-2">
                                                <span class="text-xs font-medium text-green-600">{{ substr($voyage->chauffeur->name, 0, 1) }}</span>
                                            </div>
                                            <div class="truncate max-w-[120px]">
                                                <div class="font-medium">{{ $voyage->chauffeur->name }}</div>
                                                @if($voyage->chauffeur->code)
                                                    <div class="text-gray-500 text-xs">Code: {{ $voyage->chauffeur->code }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-red-400 text-xs">❌ Non renseigné</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 hidden lg:table-cell">
                                    <div class="flex items-center gap-2">
                                        @if($voyage->chargements->count() > 0)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                📦 {{ $voyage->chargements->count() }}
                                            </span>
                                        @endif
                                        @if($voyage->dechargements->count() > 0)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                🏪 {{ $voyage->dechargements->count() }}
                                            </span>
                                        @endif
                                        @if($voyage->chargements->count() === 0 && $voyage->dechargements->count() === 0)
                                            <span class="text-gray-400 text-xs">Vide</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="relative inline-block text-left">
                                        <button 
                                            class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                            {{ $voyage->statut === 'en_cours' ? 'bg-yellow-100 text-yellow-800' : 
                                               ($voyage->statut === 'termine' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}"
                                            onclick="document.getElementById('statut-menu-{{ $voyage->id }}').classList.toggle('hidden')"
                                        >
                                            {{ ucfirst($voyage->statut) }}
                                            <svg class="ml-1 w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                                            </svg>
                                        </button>
                                        
                                        <div id="statut-menu-{{ $voyage->id }}" class="hidden absolute right-0 mt-2 w-32 bg-white rounded-md shadow-lg z-10">
                                            <div class="py-1">
                                                @if($voyage->statut !== 'en_cours')
                                                    <button wire:click="changeStatut({{ $voyage->id }}, 'en_cours')" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">En cours</button>
                                                @endif
                                                @if($voyage->statut !== 'termine')
                                                    <button wire:click="changeStatut({{ $voyage->id }}, 'termine')" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Terminé</button>
                                                @endif
                                                @if($voyage->statut !== 'annule')
                                                    <button wire:click="changeStatut({{ $voyage->id }}, 'annule')" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Annulé</button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('voyages.show', $voyage) }}" class="text-green-600 hover:text-green-900" title="Voir détails">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>
                                        <button wire:click="edit({{ $voyage->id }})" class="text-blue-600 hover:text-blue-900" title="Modifier">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>
                                        <button 
                                            wire:click="delete({{ $voyage->id }})"
                                            wire:confirm="Êtes-vous sûr de vouloir supprimer ce voyage ?"
                                            class="text-red-600 hover:text-red-900" title="Supprimer">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center">
                                    <div class="text-gray-500">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0M15 17a2 2 0 104 0"/>
                                        </svg>
                                        <p class="mt-4 text-lg font-medium">Aucun voyage trouvé</p>
                                        <p class="mt-2">Commencez par créer votre premier voyage</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($voyages->hasPages())
                <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    {{ $voyages->links() }}
                </div>
            @endif
        </div>

        <!-- Modal -->
        @if($showModal)
            <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeModal"></div>

                    <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                        <form wire:submit="save">
                            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                <div class="flex items-center mb-4">
                                    <h3 class="text-lg font-medium text-gray-900">
                                        {{ $editingVoyage ? 'Modifier' : 'Créer' }} un voyage
                                    </h3>
                                </div>

                                <div class="space-y-4">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <!-- Référence -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Référence *</label>
                                            <input 
                                                wire:model="reference"
                                                type="text"
                                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 text-sm"
                                                placeholder="V001/25"
                                            >
                                            @error('reference') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                        </div>

                                        <!-- Date -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Date *</label>
                                            <input 
                                                wire:model="date"
                                                type="date"
                                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 text-sm"
                                            >
                                            @error('date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                        </div>
                                    </div>

                                    <!-- Origine -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Origine *</label>
                                        <select wire:model="origine_id" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                            <option value="">Sélectionner une origine</option>
                                            @foreach($origines as $origine)
                                                <option value="{{ $origine->id }}">{{ $origine->nom }} ({{ $origine->region }})</option>
                                            @endforeach
                                        </select>
                                        @error('origine_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                    </div>

                                    <!-- Véhicule -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Véhicule *</label>
                                        <select wire:model="vehicule_id" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                            <option value="">Sélectionner un véhicule</option>
                                            @foreach($vehicules as $vehicule)
                                                <option value="{{ $vehicule->id }}">
                                                    {{ $vehicule->immatriculation }} 
                                                    ({{ $vehicule->marque }} {{ $vehicule->modele }})
                                                    @if($vehicule->capacite_max_kg)
                                                        - {{ number_format($vehicule->capacite_max_kg/1000, 1) }}T
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('vehicule_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <!-- Chauffeur -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Chauffeur <span class="text-gray-400">(optionnel)</span></label>
                                            <select wire:model="chauffeur_id" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                                <option value="">Sélectionner un chauffeur</option>
                                                @foreach($chauffeurs as $chauffeur)
                                                    <option value="{{ $chauffeur->id }}">
                                                        {{ $chauffeur->name }}
                                                        @if($chauffeur->code)
                                                            ({{ $chauffeur->code }})
                                                        @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('chauffeur_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                        </div>

                                        <!-- Statut -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Statut *</label>
                                            <select wire:model="statut" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                                <option value="en_cours">En cours</option>
                                                <option value="termine">Terminé</option>
                                                <option value="annule">Annulé</option>
                                            </select>
                                            @error('statut') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                        </div>
                                    </div>

                                    <!-- Observation -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Observation</label>
                                        <textarea 
                                            wire:model="observation"
                                            rows="3"
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 text-sm"
                                            placeholder="Notes sur le voyage..."
                                        ></textarea>
                                        @error('observation') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                                    {{ $editingVoyage ? 'Modifier' : 'Créer' }}
                                </button>
                                <button type="button" wire:click="closeModal" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                    Annuler
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>