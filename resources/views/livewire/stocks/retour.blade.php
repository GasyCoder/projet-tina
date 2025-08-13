
{{-- Vue 3: Retour Enhanced --}}
{{-- resources/views/livewire/stocks/retour-enhanced.blade.php --}}
<div x-data="{ darkMode: $persist(false) }" :class="{ 'dark': darkMode }">
    <!-- Statistiques des retours -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-6">
        <!-- Retours du mois -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg border-l-4 border-orange-500">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-orange-500 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 15L3 9m0 0l6-6m-6 6h18"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Retours du mois</dt>
                            <dd class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['retours_mois'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- En attente -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg border-l-4 border-yellow-500">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-yellow-500 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">En attente</dt>
                            <dd class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['retours_en_attente'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Valeur récupérable -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg border-l-4 border-green-500">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Valeur récupérable</dt>
                            <dd class="text-2xl font-semibold text-gray-900 dark:text-white">
                                {{ number_format($stats['valeur_recuperable'], 0, ',', ' ') }} Ar
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Perte totale -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg border-l-4 border-red-500">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-red-500 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Perte totale</dt>
                            <dd class="text-2xl font-semibold text-gray-900 dark:text-white">
                                {{ number_format($stats['perte_totale'], 0, ',', ' ') }} Ar
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Répartition par motif -->
    @if(count($stats['retours_par_motif']) > 0)
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg mb-6">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">📊 Répartition par motif</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                    @foreach($stats['retours_par_motif'] as $motif => $count)
                        <div class="text-center">
                            <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $count }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400 capitalize">
                                {{ str_replace('_', ' ', $motif) }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Barre d'outils -->
    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg mb-6">
        <div class="px-6 py-5">
            <div class="sm:flex sm:items-center sm:justify-between">
                <div class="flex-1 min-w-0">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-4">
                        <!-- Recherche -->
                        <div class="flex-1">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                                <input wire:model.live.debounce.300ms="search" type="text"
                                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-200"
                                    placeholder="Rechercher par client, produit, référence...">
                            </div>
                        </div>

                        <!-- Filtres -->
                        <div class="mt-3 sm:mt-0 flex space-x-3">
                            <select wire:model.live="filterStatut" class="rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200">
                                <option value="">Tous les statuts</option>
                                <option value="en_attente">En attente</option>
                                <option value="accepte">Accepté</option>
                                <option value="refuse">Refusé</option>
                                <option value="en_cours_traitement">En traitement</option>
                                <option value="traite">Traité</option>
                            </select>

                            <select wire:model.live="filterMotif" class="rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200">
                                <option value="">Tous les motifs</option>
                                <option value="defaut_qualite">Défaut qualité</option>
                                <option value="erreur_livraison">Erreur livraison</option>
                                <option value="annulation_client">Annulation client</option>
                                <option value="surplus">Surplus</option>
                                <option value="autre">Autre</option>
                            </select>

                            <select wire:model.live="filterEtat" class="rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200">
                                <option value="">Tous les états</option>
                                <option value="excellent">Excellent</option>
                                <option value="bon">Bon</option>
                                <option value="moyen">Moyen</option>
                                <option value="mauvais">Mauvais</option>
                                <option value="inutilisable">Inutilisable</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="mt-3 sm:mt-0 sm:ml-4">
                    <button wire:click="openRetourModal()" 
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-orange-600 hover:bg-orange-700">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Nouveau Retour
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau des retours -->
    <div class="bg-white dark:bg-gray-800 shadow-lg overflow-hidden rounded-lg">
        <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Liste des retours</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $retours->total() }} retours trouvés</p>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Afficher:</span>
                    <select wire:model.live="perPage" class="border border-gray-300 dark:border-gray-700 rounded-md text-sm py-1 px-2 bg-white dark:bg-gray-900">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th wire:click="sortBy('numero_retour')" class="cursor-pointer px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider hover:bg-gray-100 dark:hover:bg-gray-800">
                            <div class="flex items-center space-x-1">
                                <span>N° Retour</span>
                                @if($sortField === 'numero_retour')
                                    <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </div>
                        </th>
                        <th wire:click="sortBy('date_retour')" class="cursor-pointer px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider hover:bg-gray-100 dark:hover:bg-gray-800">
                            <div class="flex items-center space-x-1">
                                <span>Date</span>
                                @if($sortField === 'date_retour')
                                    <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Client</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Produit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Motif</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Quantité</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">État</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Valeur</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($retours as $retour)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-orange-600 dark:text-orange-400">
                                {{ $retour->numero_retour }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                {{ $retour->date_retour->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $retour->client_nom }}</div>
                                @if($retour->client_contact)
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $retour->client_contact }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $retour->produit->nom }}</div>
                                @if($retour->vente)
                                    <div class="text-sm text-gray-500 dark:text-gray-400">Vente: {{ $retour->vente->numero_vente }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $retour->motif_badge['class'] }}">
                                    {{ $retour->motif_badge['text'] }}
                                </span>
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ ucfirst($retour->responsabilite) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                {{ number_format($retour->quantite_retour_kg, 0, ',', ' ') }} kg
                                @if($retour->total_sacs_retour > 0)
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $retour->total_sacs_retour }} sacs</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @php
                                        $etatCouleur = match($retour->etat_produit) {
                                            'excellent' => 'text-green-600',
                                            'bon' => 'text-blue-600', 
                                            'moyen' => 'text-yellow-600',
                                            'mauvais' => 'text-orange-600',
                                            'inutilisable' => 'text-red-600',
                                            default => 'text-gray-600'
                                        };
                                        $etatIcon = match($retour->etat_produit) {
                                            'excellent' => '⭐⭐⭐',
                                            'bon' => '⭐⭐',
                                            'moyen' => '⭐',
                                            'mauvais' => '⚠️',
                                            'inutilisable' => '❌',
                                            default => '?'
                                        };
                                    @endphp
                                    <span class="{{ $etatCouleur }} text-sm font-medium">{{ $etatIcon }}</span>
                                    <span class="ml-2 text-sm text-gray-900 dark:text-white">{{ ucfirst($retour->etat_produit) }}</span>
                                </div>
                                @if($retour->produit_revendable)
                                    <div class="text-xs text-green-600 dark:text-green-400">✓ Revendable</div>
                                @else
                                    <div class="text-xs text-red-600 dark:text-red-400">✗ Non revendable</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ number_format($retour->valeur_recuperable_mga, 0, ',', ' ') }} Ar
                                </div>
                                @if($retour->perte_estimee_mga > 0)
                                    <div class="text-sm text-red-600 dark:text-red-400">
                                        Perte: {{ number_format($retour->perte_estimee_mga, 0, ',', ' ') }} Ar
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $retour->statut_badge['class'] }}">
                                    {{ $retour->statut_badge['text'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <button wire:click="showDetails({{ $retour->id }})" 
                                            class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900">Détails</button>
                                    
                                    @if($retour->statut_retour === 'en_attente')
                                        <button wire:click="accepterRetour({{ $retour->id }})" 
                                                class="text-green-600 dark:text-green-400 hover:text-green-900">Accepter</button>
                                        <button wire:click="refuserRetour({{ $retour->id }}, 'Refus manuel')" 
                                                class="text-red-600 dark:text-red-400 hover:text-red-900">Refuser</button>
                                    @endif

                                    @if(in_array($retour->statut_retour, ['accepte', 'en_cours_traitement']))
                                        <button wire:click="openTraitementModal({{ $retour->id }})" 
                                                class="text-blue-600 dark:text-blue-400 hover:text-blue-900">Traiter</button>
                                    @endif

                                    @if($retour->statut_retour !== 'traite')
                                        <button wire:click="openRetourModal({{ $retour->id }})" 
                                                class="text-yellow-600 dark:text-yellow-400 hover:text-yellow-900">Modifier</button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 15L3 9m0 0l6-6m-6 6h18"/>
                                    </svg>
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Aucun retour trouvé</h3>
                                    <p class="text-gray-500 dark:text-gray-400">Les retours apparaîtront ici une fois enregistrés.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($retours->hasPages())
            <div class="bg-white dark:bg-gray-800 px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $retours->links() }}
            </div>
        @endif
    </div>

    <!-- Modal de retour -->
    @if($showRetourModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ activeTab: 'general' }">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-gray-500 dark:bg-gray-900 bg-opacity-75" wire:click="closeRetourModal"></div>

                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-6">
                            {{ $editingRetour ? 'Modifier le retour' : 'Nouveau retour' }}
                        </h3>

                        <!-- Onglets -->
                        <div class="border-b border-gray-200 dark:border-gray-700 mb-6">
                            <nav class="-mb-px flex space-x-8">
                                <button @click="activeTab = 'general'" 
                                        :class="activeTab === 'general' ? 'border-orange-500 text-orange-600' : 'border-transparent text-gray-500'"
                                        class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                                    Informations générales
                                </button>
                                <button @click="activeTab = 'evaluation'" 
                                        :class="activeTab === 'evaluation' ? 'border-orange-500 text-orange-600' : 'border-transparent text-gray-500'"
                                        class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                                    Évaluation produit
                                </button>
                                <button @click="activeTab = 'logistique'" 
                                        :class="activeTab === 'logistique' ? 'border-orange-500 text-orange-600' : 'border-transparent text-gray-500'"
                                        class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                                    Logistique retour
                                </button>
                            </nav>
                        </div>

                        <form wire:submit.prevent="saveRetour">
                            <!-- Onglet Général -->
                            <div x-show="activeTab === 'general'" class="space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">N° Retour</label>
                                        <input type="text" wire:model="numero_retour" readonly
                                               class="mt-1 block w-full border border-gray-300 dark:border-gray-700 rounded-md px-3 py-2 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-200">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date de retour *</label>
                                        <input type="date" wire:model="date_retour" required
                                               class="mt-1 block w-full border border-gray-300 dark:border-gray-700 rounded-md px-3 py-2 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-200">
                                        @error('date_retour') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Vente associée</label>
                                        <select wire:model="vente_id"
                                                class="mt-1 block w-full border border-gray-300 dark:border-gray-700 rounded-md px-3 py-2 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-200">
                                            <option value="">Sélectionnez une vente</option>
                                            @foreach($ventes as $vente)
                                                <option value="{{ $vente->id }}">{{ $vente->numero_vente }} - {{ $vente->client_nom }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nom du client *</label>
                                        <input type="text" wire:model="client_nom" required
                                               class="mt-1 block w-full border border-gray-300 dark:border-gray-700 rounded-md px-3 py-2 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-200">
                                        @error('client_nom') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Contact client</label>
                                        <input type="text" wire:model="client_contact"
                                               class="mt-1 block w-full border border-gray-300 dark:border-gray-700 rounded-md px-3 py-2 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-200">
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Produit *</label>
                                        <select wire:model="produit_id" required
                                                class="mt-1 block w-full border border-gray-300 dark:border-gray-700 rounded-md px-3 py-2 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-200">
                                            <option value="">Sélectionnez un produit</option>
                                            @foreach($produits as $produit)
                                                <option value="{{ $produit->id }}">{{ $produit->nom }}</option>
                                            @endforeach
                                        </select>
                                        @error('produit_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Lieu de stockage *</label>
                                        <select wire:model="lieu_stockage_id" required
                                                class="mt-1 block w-full border border-gray-300 dark:border-gray-700 rounded-md px-3 py-2 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-200">
                                            <option value="">Sélectionnez un lieu</option>
                                            @foreach($lieux as $lieu)
                                                <option value="{{ $lieu->id }}">{{ $lieu->nom }}</option>
                                            @endforeach
                                        </select>
                                        @error('lieu_stockage_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Quantité retour (kg) *</label>
                                        <input type="number" step="0.01" wire:model="quantite_retour_kg" required
                                               class="mt-1 block w-full border border-gray-300 dark:border-gray-700 rounded-md px-3 py-2 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-200">
                                        @error('quantite_retour_kg') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sacs pleins</label>
                                        <input type="number" wire:model="sacs_pleins_retour"
                                               class="mt-1 block w-full border border-gray-300 dark:border-gray-700 rounded-md px-3 py-2 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-200">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sacs demi</label>
                                        <input type="number" wire:model="sacs_demi_retour"
                                               class="mt-1 block w-full border border-gray-300 dark:border-gray-700 rounded-md px-3 py-2 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-200">
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Motif du retour *</label>
                                        <select wire:model="motif_retour" required
                                                class="mt-1 block w-full border border-gray-300 dark:border-gray-700 rounded-md px-3 py-2 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-200">
                                            <option value="">Sélectionnez un motif</option>
                                            <option value="defaut_qualite">Défaut de qualité</option>
                                            <option value="erreur_livraison">Erreur de livraison</option>
                                            <option value="annulation_client">Annulation client</option>
                                            <option value="surplus">Surplus</option>
                                            <option value="autre">Autre</option>
                                        </select>
                                        @error('motif_retour') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Responsabilité</label>
                                        <select wire:model="responsabilite"
                                                class="mt-1 block w-full border border-gray-300 dark:border-gray-700 rounded-md px-3 py-2 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-200">
                                            <option value="client">Client</option>
                                            <option value="transporteur">Transporteur</option>
                                            <option value="vendeur">Vendeur</option>
                                            <option value="produit">Produit défectueux</option>
                                        </select>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description détaillée *</label>
                                    <textarea wire:model="description_motif" rows="3" required
                                              class="mt-1 block w-full border border-gray-300 dark:border-gray-700 rounded-md px-3 py-2 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-200"
                                              placeholder="Décrivez précisément le motif du retour..."></textarea>
                                    @error('description_motif') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Onglet Évaluation -->
                            <div x-show="activeTab === 'evaluation'" class="space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">État du produit *</label>
                                        <div class="mt-2 space-y-2">
                                            @foreach(['excellent' => '⭐⭐⭐ Excellent', 'bon' => '⭐⭐ Bon', 'moyen' => '⭐ Moyen', 'mauvais' => '⚠️ Mauvais', 'inutilisable' => '❌ Inutilisable'] as $value => $label)
                                                <label class="flex items-center">
                                                    <input type="radio" wire:model="etat_produit" value="{{ $value }}" 
                                                           class="focus:ring-orange-500 h-4 w-4 text-orange-600 border-gray-300">
                                                    <span class="ml-2 text-sm text-gray-900 dark:text-gray-200">{{ $label }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                        @error('etat_produit') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <div class="flex items-center justify-between mb-2">
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Produit revendable</label>
                                            <label class="inline-flex items-center">
                                                <input type="checkbox" wire:model="produit_revendable" 
                                                       class="rounded border-gray-300 text-orange-600 shadow-sm focus:border-orange-300 focus:ring focus:ring-orange-200">
                                                <span class="ml-2 text-sm text-gray-900 dark:text-gray-200">Oui</span>
                                            </label>
                                        </div>

                                        <div class="space-y-3">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Valeur récupérable (Ar)</label>
                                                <input type="number" step="0.01" wire:model="valeur_recuperable_mga"
                                                       class="mt-1 block w-full border border-gray-300 dark:border-gray-700 rounded-md px-3 py-2 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-200">
                                            </div>

                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Perte estimée (Ar)</label>
                                                <input type="text" value="{{ number_format($perte_estimee_mga, 0, ',', ' ') }} Ar" readonly
                                                       class="mt-1 block w-full border border-gray-300 dark:border-gray-700 rounded-md px-3 py-2 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-200">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Observations sur la qualité</label>
                                    <textarea wire:model="observations" rows="3"
                                              class="mt-1 block w-full border border-gray-300 dark:border-gray-700 rounded-md px-3 py-2 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-200"
                                              placeholder="Notes détaillées sur l'état du produit, défauts constatés, etc."></textarea>
                                </div>
                            </div>

                            <!-- Onglet Logistique -->
                            <div x-show="activeTab === 'logistique'" class="space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Transporteur retour</label>
                                        <input type="text" wire:model="transporteur_retour"
                                               class="mt-1 block w-full border border-gray-300 dark:border-gray-700 rounded-md px-3 py-2 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-200">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Frais de retour (Ar)</label>
                                        <input type="number" step="0.01" wire:model="frais_retour_mga"
                                               class="mt-1 block w-full border border-gray-300 dark:border-gray-700 rounded-md px-3 py-2 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-200">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Prise en charge des frais</label>
                                    <select wire:model="prise_charge_frais"
                                            class="mt-1 block w-full border border-gray-300 dark:border-gray-700 rounded-md px-3 py-2 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-200">
                                        <option value="client">Client</option>
                                        <option value="vendeur">Vendeur</option>
                                        <option value="transporteur">Transporteur</option>
                                        <option value="partage">Partage</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Statut du retour</label>
                                    <select wire:model="statut_retour"
                                            class="mt-1 block w-full border border-gray-300 dark:border-gray-700 rounded-md px-3 py-2 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-200">
                                        <option value="en_attente">En attente</option>
                                        <option value="accepte">Accepté</option>
                                        <option value="refuse">Refusé</option>
                                        <option value="en_cours_traitement">En cours de traitement</option>
                                        <option value="traite">Traité</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Actions du modal -->
                            <div class="mt-6 flex justify-end space-x-3">
                                <button type="button" wire:click="closeRetourModal"
                                        class="inline-flex justify-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-600">
                                    Annuler
                                </button>
                                <button type="submit"
                                        class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-orange-600 border border-transparent rounded-md hover:bg-orange-700">
                                    {{ $editingRetour ? 'Modifier' : 'Créer' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal de traitement -->
    @if($showTraitementModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-gray-500 dark:bg-gray-900 bg-opacity-75" wire:click="closeTraitementModal"></div>

                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-6">
                            Traiter le retour {{ $editingRetour?->numero_retour }}
                        </h3>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Action à prendre *</label>
                                <select wire:model="action_prise" required
                                        class="mt-1 block w-full border border-gray-300 dark:border-gray-700 rounded-md px-3 py-2 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-200">
                                    <option value="">Sélectionnez une action</option>
                                    <option value="remboursement">💰 Remboursement</option>
                                    <option value="echange">🔄 Échange</option>
                                    <option value="avoir">📋 Avoir client</option>
                                    <option value="destruction">🗑️ Destruction</option>
                                    <option value="revente_reduite">🏷️ Revente à prix réduit</option>
                                </select>
                            </div>

                            @if($action_prise === 'remboursement')
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Montant à rembourser (Ar)</label>
                                    <input type="number" step="0.01" wire:model="montant_rembourse_mga"
                                           class="mt-1 block w-full border border-gray-300 dark:border-gray-700 rounded-md px-3 py-2 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-200">
                                    @if($editingRetour)
                                        <button type="button" wire:click="$set('montant_rembourse_mga', {{ $editingRetour->valeur_recuperable_mga }})"
                                                class="mt-1 text-xs text-blue-600 hover:text-blue-800">Valeur récupérable complète</button>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-800 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="traiterRetour"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                            ✅ Valider le traitement
                        </button>
                        <button wire:click="closeTraitementModal"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Annuler
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>