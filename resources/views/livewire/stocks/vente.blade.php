{{-- Vue 2: Vente Enhanced --}}
{{-- resources/views/livewire/stocks/vente-enhanced.blade.php --}}
<div x-data="{ darkMode: $persist(false) }" :class="{ 'dark': darkMode }">
    <!-- Alertes -->
    @if($alertes->count() > 0)
        <div class="mb-6 bg-orange-50 dark:bg-orange-900/30 border border-orange-200 dark:border-orange-800 rounded-lg p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-orange-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-orange-800 dark:text-orange-200">
                        <strong>{{ $alertes->count() }} alerte(s) de stock :</strong>
                        @foreach($alertes->take(3) as $alerte)
                            {{ $alerte->produit->nom }} ({{ $alerte->depot->nom }}){{ !$loop->last ? ', ' : '' }}
                        @endforeach
                        @if($alertes->count() > 3)... et {{ $alertes->count() - 3 }} autres@endif
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- Statistiques des ventes -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-6">
        <!-- Ventes du jour -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg border-l-4 border-blue-500">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Ventes du jour</dt>
                            <dd class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['ventes_jour'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- CA Journalier -->
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
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">CA Journalier</dt>
                            <dd class="text-2xl font-semibold text-gray-900 dark:text-white">
                                {{ number_format($stats['ca_journalier'], 0, ',', ' ') }} Ar
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Montant Impayé -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg border-l-4 border-red-500">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-red-500 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Montant Impayé</dt>
                            <dd class="text-2xl font-semibold text-gray-900 dark:text-white">
                                {{ number_format($stats['montant_impaye'], 0, ',', ' ') }} Ar
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ventes en Retard -->
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
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">En Retard</dt>
                            <dd class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['ventes_en_retard'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
                                <option value="brouillon">Brouillon</option>
                                <option value="confirmee">Confirmée</option>
                                <option value="en_preparation">En préparation</option>
                                <option value="expediee">Expédiée</option>
                                <option value="livree">Livrée</option>
                                <option value="annulee">Annulée</option>
                            </select>

                            <select wire:model.live="filterPaiement" class="rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200">
                                <option value="">Tous les paiements</option>
                                <option value="impaye">Impayé</option>
                                <option value="partiel">Partiel</option>
                                <option value="paye">Payé</option>
                            </select>

                            <select wire:model.live="filterPeriod" class="rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200">
                                <option value="">Toutes les périodes</option>
                                <option value="today">Aujourd'hui</option>
                                <option value="week">Cette semaine</option>
                                <option value="month">Ce mois</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="mt-3 sm:mt-0 sm:ml-4 flex space-x-3">
                    <button wire:click="openVenteModal()" 
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Nouvelle Vente
                    </button>

                    <button wire:click="openExportModal()" 
                            class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Exporter
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau des ventes -->
    <div class="bg-white dark:bg-gray-800 shadow-lg overflow-hidden rounded-lg">
        <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Liste des ventes</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        {{ $ventes->total() }} ventes trouvées
                    </p>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Afficher:</span>
                    <select wire:model.live="perPage" class="border border-gray-300 dark:border-gray-700 rounded-md text-sm py-1 px-2 bg-white dark:bg-gray-900">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th wire:click="sortBy('numero_vente')" class="cursor-pointer px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider hover:bg-gray-100 dark:hover:bg-gray-800">
                            <div class="flex items-center space-x-1">
                                <span>N° Vente</span>
                                @if($sortField === 'numero_vente')
                                    <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </div>
                        </th>
                        <th wire:click="sortBy('date_vente')" class="cursor-pointer px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider hover:bg-gray-100 dark:hover:bg-gray-800">
                            <div class="flex items-center space-x-1">
                                <span>Date</span>
                                @if($sortField === 'date_vente')
                                    <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Client</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Produit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Quantité</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Montant</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Paiement</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($ventes as $vente)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-600 dark:text-blue-400">
                                {{ $vente->numero_vente }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                {{ $vente->date_vente->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $vente->client_nom }}</div>
                                @if($vente->client_contact)
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $vente->client_contact }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $vente->produit->nom }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ number_format($vente->prix_unitaire_mga, 0) }} Ar/kg</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                {{ number_format($vente->quantite_kg, 0, ',', ' ') }} kg
                                @if($vente->total_sacs > 0)
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $vente->total_sacs }} sacs</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ number_format($vente->prix_total_mga, 0, ',', ' ') }} Ar
                                </div>
                                @if($vente->montant_restant_mga > 0)
                                    <div class="text-sm text-red-600 dark:text-red-400">
                                        Reste: {{ number_format($vente->montant_restant_mga, 0, ',', ' ') }} Ar
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $vente->statut_paiement_badge['class'] }}">
                                    {{ $vente->statut_paiement_badge['text'] }}
                                </span>
                                @php
                                    $pourcentagePaye = $vente->prix_total_mga > 0 ? ($vente->montant_paye_mga / $vente->prix_total_mga) * 100 : 0;
                                @endphp
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5 mt-1">
                                    <div class="h-1.5 rounded-full {{ $pourcentagePaye >= 100 ? 'bg-green-600' : ($pourcentagePaye >= 50 ? 'bg-yellow-500' : 'bg-red-500') }}"
                                         style="width: {{ min($pourcentagePaye, 100) }}%"></div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $vente->statut_badge['class'] }}">
                                    {{ $vente->statut_badge['text'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <button wire:click="showDetails({{ $vente->id }})" 
                                            class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900">Détails</button>
                                    
                                    @if($vente->statut_vente === 'brouillon')
                                        <button wire:click="confirmerVente({{ $vente->id }})" 
                                                class="text-green-600 dark:text-green-400 hover:text-green-900">Confirmer</button>
                                    @endif
                                    
                                    @if(in_array($vente->statut_vente, ['brouillon', 'confirmee']))
                                        <button wire:click="openVenteModal({{ $vente->id }})" 
                                                class="text-blue-600 dark:text-blue-400 hover:text-blue-900">Modifier</button>
                                    @endif
                                    
                                    <button wire:click="imprimerVente({{ $vente->id }})" 
                                            class="text-purple-600 dark:text-purple-400 hover:text-purple-900">Imprimer</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                    </svg>
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Aucune vente trouvée</h3>
                                    <p class="text-gray-500 dark:text-gray-400">Commencez par créer votre première vente.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($ventes->hasPages())
            <div class="bg-white dark:bg-gray-800 px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $ventes->links() }}
            </div>
        @endif
    </div>

    <!-- Modal de vente -->
    @if($showVenteModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ activeTab: 'general' }">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-gray-500 dark:bg-gray-900 bg-opacity-75" wire:click="closeVenteModal"></div>

                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-6">
                            {{ $editingVente ? 'Modifier la vente' : 'Nouvelle vente' }}
                        </h3>

                        <!-- Onglets -->
                        <div class="border-b border-gray-200 dark:border-gray-700 mb-6">
                            <nav class="-mb-px flex space-x-8">
                                <button @click="activeTab = 'general'" 
                                        :class="activeTab === 'general' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500'"
                                        class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                                    Informations générales
                                </button>
                                <button @click="activeTab = 'produit'" 
                                        :class="activeTab === 'produit' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500'"
                                        class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                                    Produit et quantités
                                </button>
                                <button @click="activeTab = 'paiement'" 
                                        :class="activeTab === 'paiement' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500'"
                                        class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                                    Paiement et livraison
                                </button>
                            </nav>
                        </div>

                        <form wire:submit.prevent="saveVente">
                            <!-- Onglet Général -->
                            <div x-show="activeTab === 'general'" class="space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">N° Vente</label>
                                        <input type="text" wire:model="numero_vente" readonly
                                               class="mt-1 block w-full border border-gray-300 dark:border-gray-700 rounded-md px-3 py-2 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-200">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date de vente *</label>
                                        <input type="date" wire:model="date_vente" required
                                               class="mt-1 block w-full border border-gray-300 dark:border-gray-700 rounded-md px-3 py-2 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-200">
                                        @error('date_vente') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Type de client</label>
                                        <select wire:model="client_type"
                                                class="mt-1 block w-full border border-gray-300 dark:border-gray-700 rounded-md px-3 py-2 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-200">
                                            <option value="particulier">Particulier</option>
                                            <option value="entreprise">Entreprise</option>
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

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Adresse du client</label>
                                    <textarea wire:model="client_adresse" rows="2"
                                              class="mt-1 block w-full border border-gray-300 dark:border-gray-700 rounded-md px-3 py-2 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-200"></textarea>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Lieu de livraison *</label>
                                    <select wire:model="lieu_livraison_id" required
                                            class="mt-1 block w-full border border-gray-300 dark:border-gray-700 rounded-md px-3 py-2 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-200">
                                        <option value="">Sélectionnez un lieu</option>
                                        @foreach($destinations as $destination)
                                            <option value="{{ $destination->id }}">{{ $destination->nom }}</option>
                                        @endforeach
                                    </select>
                                    @error('lieu_livraison_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Onglet Produit -->
                            <div x-show="activeTab === 'produit'" class="space-y-4">
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
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Qualité du produit</label>
                                        <select wire:model="qualite_produit"
                                                class="mt-1 block w-full border border-gray-300 dark:border-gray-700 rounded-md px-3 py-2 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-200">
                                            <option value="excellent">Excellent</option>
                                            <option value="bon">Bon</option>
                                            <option value="moyen">Moyen</option>
                                            <option value="mauvais">Mauvais</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Quantité (kg) *</label>
                                        <input type="number" step="0.01" wire:model="quantite_kg" required
                                               class="mt-1 block w-full border border-gray-300 dark:border-gray-700 rounded-md px-3 py-2 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-200">
                                        @error('quantite_kg') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sacs pleins</label>
                                        <input type="number" wire:model="sacs_pleins"
                                               class="mt-1 block w-full border border-gray-300 dark:border-gray-700 rounded-md px-3 py-2 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-200">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sacs demi</label>
                                        <input type="number" wire:model="sacs_demi"
                                               class="mt-1 block w-full border border-gray-300 dark:border-gray-700 rounded-md px-3 py-2 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-200">
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Prix unitaire (Ar/kg) *</label>
                                        <input type="number" step="0.01" wire:model="prix_unitaire_mga" required
                                               class="mt-1 block w-full border border-gray-300 dark:border-gray-700 rounded-md px-3 py-2 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-200">
                                        @error('prix_unitaire_mga') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Taux TVA (%)</label>
                                        <input type="number" step="0.01" wire:model="tva_taux"
                                               class="mt-1 block w-full border border-gray-300 dark:border-gray-700 rounded-md px-3 py-2 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-200">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Prix total (Ar)</label>
                                        <input type="text" value="{{ number_format($prix_total_mga, 0, ',', ' ') }} Ar" readonly
                                               class="mt-1 block w-full border border-gray-300 dark:border-gray-700 rounded-md px-3 py-2 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-200">
                                    </div>
                                </div>
                            </div>

                            <!-- Onglet Paiement -->
                            <div x-show="activeTab === 'paiement'" class="space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Montant payé (Ar)</label>
                                        <input type="number" step="0.01" wire:model="montant_paye_mga"
                                               class="mt-1 block w-full border border-gray-300 dark:border-gray-700 rounded-md px-3 py-2 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-200">
                                        <button type="button" wire:click="$set('montant_paye_mga', {{ $prix_total_mga }})"
                                                class="mt-1 text-xs text-blue-600 hover:text-blue-800">Paiement complet</button>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mode de paiement</label>
                                        <select wire:model="mode_paiement"
                                                class="mt-1 block w-full border border-gray-300 dark:border-gray-700 rounded-md px-3 py-2 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-200">
                                            <option value="">Sélectionnez</option>
                                            <option value="especes">Espèces</option>
                                            <option value="cheque">Chèque</option>
                                            <option value="virement">Virement</option>
                                            <option value="mobile_money">Mobile Money</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Montant restant (Ar)</label>
                                        <input type="text" value="{{ number_format($montant_restant_mga, 0, ',', ' ') }} Ar" readonly
                                               class="mt-1 block w-full border border-gray-300 dark:border-gray-700 rounded-md px-3 py-2 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-200">
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Transporteur</label>
                                        <input type="text" wire:model="transporteur_nom"
                                               class="mt-1 block w-full border border-gray-300 dark:border-gray-700 rounded-md px-3 py-2 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-200">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Véhicule</label>
                                        <input type="text" wire:model="vehicule_immatriculation"
                                               class="mt-1 block w-full border border-gray-300 dark:border-gray-700 rounded-md px-3 py-2 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-200">
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Frais de transport (Ar)</label>
                                        <input type="number" step="0.01" wire:model="frais_transport_mga"
                                               class="mt-1 block w-full border border-gray-300 dark:border-gray-700 rounded-md px-3 py-2 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-200">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Livraison prévue</label>
                                        <input type="date" wire:model="date_livraison_prevue"
                                               class="mt-1 block w-full border border-gray-300 dark:border-gray-700 rounded-md px-3 py-2 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-200">
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Statut de la vente</label>
                                        <select wire:model="statut_vente"
                                                class="mt-1 block w-full border border-gray-300 dark:border-gray-700 rounded-md px-3 py-2 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-200">
                                            <option value="brouillon">Brouillon</option>
                                            <option value="confirmee">Confirmée</option>
                                            <option value="en_preparation">En préparation</option>
                                            <option value="expediee">Expédiée</option>
                                            <option value="livree">Livrée</option>
                                        </select>
                                    </div>

                                    <div class="flex items-center justify-center">
                                        <div class="text-center">
                                            <div class="text-sm font-medium text-gray-700 dark:text-gray-300">Statut de paiement</div>
                                            <span class="px-3 py-1 text-sm font-medium rounded-full bg-{{ $statut_paiement === 'paye' ? 'green' : ($statut_paiement === 'partiel' ? 'yellow' : 'red') }}-100 text-{{ $statut_paiement === 'paye' ? 'green' : ($statut_paiement === 'partiel' ? 'yellow' : 'red') }}-800">
                                                {{ ucfirst($statut_paiement) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Observations</label>
                                    <textarea wire:model="observations" rows="3"
                                              class="mt-1 block w-full border border-gray-300 dark:border-gray-700 rounded-md px-3 py-2 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-200"></textarea>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Remarques du client</label>
                                    <textarea wire:model="remarques_client" rows="2"
                                              class="mt-1 block w-full border border-gray-300 dark:border-gray-700 rounded-md px-3 py-2 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-200"></textarea>
                                </div>
                            </div>

                            <!-- Actions du modal -->
                            <div class="mt-6 flex justify-end space-x-3">
                                <button type="button" wire:click="closeVenteModal"
                                        class="inline-flex justify-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-600">
                                    Annuler
                                </button>
                                <button type="submit"
                                        class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700">
                                    {{ $editingVente ? 'Modifier' : 'Créer' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal de détails -->
    @if($showDetailsModal && $venteDetails)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-gray-500 dark:bg-gray-900 bg-opacity-75" wire:click="closeDetailsModal"></div>

                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="flex justify-between items-start mb-6">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                                Détails de la vente {{ $venteDetails->numero_vente }}
                            </h3>
                            <button wire:click="closeDetailsModal" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Informations client -->
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <h4 class="font-medium text-gray-900 dark:text-white mb-3">👤 Informations Client</h4>
                                <div class="space-y-2 text-sm">
                                    <div><span class="font-medium">Nom:</span> {{ $venteDetails->client_nom }}</div>
                                    @if($venteDetails->client_contact)
                                        <div><span class="font-medium">Contact:</span> {{ $venteDetails->client_contact }}</div>
                                    @endif
                                    @if($venteDetails->client_adresse)
                                        <div><span class="font-medium">Adresse:</span> {{ $venteDetails->client_adresse }}</div>
                                    @endif
                                    <div><span class="font-medium">Type:</span> {{ ucfirst($venteDetails->client_type) }}</div>
                                </div>
                            </div>

                            <!-- Informations produit -->
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <h4 class="font-medium text-gray-900 dark:text-white mb-3">📦 Produit</h4>
                                <div class="space-y-2 text-sm">
                                    <div><span class="font-medium">Produit:</span> {{ $venteDetails->produit->nom }}</div>
                                    <div><span class="font-medium">Quantité:</span> {{ number_format($venteDetails->quantite_kg, 0, ',', ' ') }} kg</div>
                                    <div><span class="font-medium">Prix unitaire:</span> {{ number_format($venteDetails->prix_unitaire_mga, 0, ',', ' ') }} Ar/kg</div>
                                    <div><span class="font-medium">Qualité:</span> {{ ucfirst($venteDetails->qualite_produit) }}</div>
                                    @if($venteDetails->total_sacs > 0)
                                        <div><span class="font-medium">Sacs:</span> {{ $venteDetails->sacs_pleins }} pleins + {{ $venteDetails->sacs_demi }} demi</div>
                                    @endif
                                </div>
                            </div>

                            <!-- Informations financières -->
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <h4 class="font-medium text-gray-900 dark:text-white mb-3">💰 Financier</h4>
                                <div class="space-y-2 text-sm">
                                    <div><span class="font-medium">Montant total:</span> {{ number_format($venteDetails->prix_total_mga, 0, ',', ' ') }} Ar</div>
                                    <div><span class="font-medium">Montant payé:</span> {{ number_format($venteDetails->montant_paye_mga, 0, ',', ' ') }} Ar</div>
                                    <div><span class="font-medium">Montant restant:</span> {{ number_format($venteDetails->montant_restant_mga, 0, ',', ' ') }} Ar</div>
                                    <div><span class="font-medium">Statut paiement:</span> 
                                        <span class="px-2 py-1 text-xs rounded-full {{ $venteDetails->statut_paiement_badge['class'] }}">
                                            {{ $venteDetails->statut_paiement_badge['text'] }}
                                        </span>
                                    </div>
                                    @if($venteDetails->mode_paiement)
                                        <div><span class="font-medium">Mode:</span> {{ ucfirst($venteDetails->mode_paiement) }}</div>
                                    @endif
                                    @if($venteDetails->tva_taux > 0)
                                        <div><span class="font-medium">TVA:</span> {{ $venteDetails->tva_taux }}% ({{ number_format($venteDetails->tva_montant_mga, 0, ',', ' ') }} Ar)</div>
                                    @endif
                                </div>
                            </div>

                            <!-- Informations livraison -->
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <h4 class="font-medium text-gray-900 dark:text-white mb-3">🚚 Livraison</h4>
                                <div class="space-y-2 text-sm">
                                    <div><span class="font-medium">Lieu:</span> {{ $venteDetails->lieuLivraison->nom }}</div>
                                    <div><span class="font-medium">Statut:</span> 
                                        <span class="px-2 py-1 text-xs rounded-full {{ $venteDetails->statut_badge['class'] }}">
                                            {{ $venteDetails->statut_badge['text'] }}
                                        </span>
                                    </div>
                                    @if($venteDetails->date_livraison_prevue)
                                        <div><span class="font-medium">Date prévue:</span> {{ $venteDetails->date_livraison_prevue->format('d/m/Y') }}</div>
                                    @endif
                                    @if($venteDetails->date_livraison_reelle)
                                        <div><span class="font-medium">Date réelle:</span> {{ $venteDetails->date_livraison_reelle->format('d/m/Y') }}</div>
                                    @endif
                                    @if($venteDetails->transporteur_nom)
                                        <div><span class="font-medium">Transporteur:</span> {{ $venteDetails->transporteur_nom }}</div>
                                    @endif
                                    @if($venteDetails->vehicule_immatriculation)
                                        <div><span class="font-medium">Véhicule:</span> {{ $venteDetails->vehicule_immatriculation }}</div>
                                    @endif
                                    @if($venteDetails->frais_transport_mga > 0)
                                        <div><span class="font-medium">Frais transport:</span> {{ number_format($venteDetails->frais_transport_mga, 0, ',', ' ') }} Ar</div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Observations et remarques -->
                        @if($venteDetails->observations || $venteDetails->remarques_client)
                            <div class="mt-6">
                                <h4 class="font-medium text-gray-900 dark:text-white mb-3">📝 Notes</h4>
                                @if($venteDetails->observations)
                                    <div class="mb-3">
                                        <span class="font-medium text-sm">Observations:</span>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $venteDetails->observations }}</p>
                                    </div>
                                @endif
                                @if($venteDetails->remarques_client)
                                    <div>
                                        <span class="font-medium text-sm">Remarques client:</span>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $venteDetails->remarques_client }}</p>
                                    </div>
                                @endif
                            </div>
                        @endif

                        <!-- Historique des paiements -->
                        @if($venteDetails->paiements && $venteDetails->paiements->count() > 0)
                            <div class="mt-6">
                                <h4 class="font-medium text-gray-900 dark:text-white mb-3">💳 Historique des paiements</h4>
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                    <div class="space-y-2">
                                        @foreach($venteDetails->paiements as $paiement)
                                            <div class="flex justify-between items-center text-sm">
                                                <span>{{ $paiement->date_paiement->format('d/m/Y') }} - {{ ucfirst($paiement->mode_paiement) }}</span>
                                                <span class="font-medium">{{ number_format($paiement->montant_mga, 0, ',', ' ') }} Ar</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Retours associés -->
                        @if($venteDetails->retours && $venteDetails->retours->count() > 0)
                            <div class="mt-6">
                                <h4 class="font-medium text-gray-900 dark:text-white mb-3">↩️ Retours</h4>
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                    <div class="space-y-2">
                                        @foreach($venteDetails->retours as $retour)
                                            <div class="flex justify-between items-center text-sm">
                                                <span>{{ $retour->date_retour->format('d/m/Y') }} - {{ $retour->motif_retour }}</span>
                                                <span class="font-medium">{{ number_format($retour->quantite_retour_kg, 0, ',', ' ') }} kg</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-800 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="imprimerVente({{ $venteDetails->id }})"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                            🖨️ Imprimer
                        </button>
                        <button wire:click="closeDetailsModal"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Fermer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal d'export -->
    @if($showExportModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-gray-500 dark:bg-gray-900 bg-opacity-75" wire:click="closeExportModal"></div>

                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-6">📊 Exporter les ventes</h3>

                        <div class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date début</label>
                                    <input type="date" wire:model="exportDateDebut"
                                           class="mt-1 block w-full border border-gray-300 dark:border-gray-700 rounded-md px-3 py-2 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-200">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date fin</label>
                                    <input type="date" wire:model="exportDateFin"
                                           class="mt-1 block w-full border border-gray-300 dark:border-gray-700 rounded-md px-3 py-2 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-200">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Format d'export</label>
                                <select wire:model="exportFormat"
                                        class="mt-1 block w-full border border-gray-300 dark:border-gray-700 rounded-md px-3 py-2 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-200">
                                    <option value="excel">Excel (.xlsx)</option>
                                    <option value="csv">CSV (.csv)</option>
                                    <option value="pdf">PDF (.pdf)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-800 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="exportVentes"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                            📥 Exporter
                        </button>
                        <button wire:click="closeExportModal"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Annuler
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
