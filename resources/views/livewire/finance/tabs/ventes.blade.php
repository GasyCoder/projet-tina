{{-- resources/views/livewire/finance/tabs/ventes.blade.php --}}
{{-- PURE LIVEWIRE + TAILWIND - SANS ALPINE.JS --}}

<div class="space-y-6">

    <!-- Filtres -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-5 gap-4">
            
            <!-- Recherche -->
            <div class="xl:col-span-2">
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input wire:model.live="searchTerm" 
                           type="text" 
                           placeholder="Rechercher par référence, objet, vendeur..."
                           class="pl-10 w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>
            </div>

            <!-- Filtre date -->
            <div>
                <select wire:model.live="filterDate" 
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <option value="">Toutes les dates</option>
                    <option value="today">Aujourd'hui</option>
                    <option value="week">Cette semaine</option>
                    <option value="month">Ce mois</option>
                    <option value="year">Cette année</option>
                </select>
            </div>

            <!-- Filtre statut -->
            <div>
                <select wire:model.live="filterStatutPaiement" 
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <option value="">Tous les statuts</option>
                    <option value="paye">✅ Payé</option>
                    <option value="partiel">⏳ Partiel</option>
                </select>
            </div>

            <!-- Filtre mode paiement -->
            <div>
                <select wire:model.live="filterModePaiement" 
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <option value="">Tous les modes</option>
                    <option value="especes">💵 Espèces</option>
                    <option value="AirtelMoney">📱 AirtelMoney</option>
                    <option value="Mvola">📱 MVola</option>
                    <option value="OrangeMoney">📱 OrangeMoney</option>
                    <option value="banque">🏦 Banque</option>
                </select>
            </div>
        </div>

        <!-- Filtre dépôt (ligne séparée) -->
        <div class="mt-4 grid grid-cols-1 lg:grid-cols-3 gap-4">
            <div>
                <select wire:model.live="filterDepot" 
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <option value="">Tous les dépôts</option>
                    @foreach($depots as $depot)
                        <option value="{{ $depot->id }}">{{ $depot->nom }} ({{ $depot->type }})</option>
                    @endforeach
                </select>
            </div>
            
            <div class="lg:col-span-2 flex justify-end">
                <button wire:click="clearFilters" 
                        class="px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 transition-colors inline-flex items-center" 
                        title="Effacer les filtres">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Effacer filtres
                </button>
            </div>
        </div>
    </div>

    @if ($ventes->count() > 0)
        <!-- Liste des ventes -->
        <div class="space-y-4">
            @foreach ($ventes as $vente)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden transition-all duration-200 hover:shadow-md">
                    
                    <!-- Ligne principale -->
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            
                            <!-- Informations principales -->
                            <div class="flex items-center space-x-4 flex-1">
                                
                                <!-- Badge type -->
                                <div class="flex-shrink-0">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                        💰 Vente
                                    </span>
                                </div>

                                <!-- Détails -->
                                <div>
                                    <div class="font-semibold text-gray-900 dark:text-gray-100">
                                        {{ $vente->reference }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $vente->objet ?: 'Vente' }} • {{ $vente->date?->format('d/m/Y') ?? $vente->created_at->format('d/m/Y') }}
                                        @if($vente->vendeur_nom)
                                            • {{ $vente->vendeur_nom }}
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Montant et statut -->
                            <div class="flex items-center space-x-4">
                                <!-- Montants -->
                                <div class="text-right">
                                    <div class="text-xl font-bold text-green-600 dark:text-green-400">
                                        +{{ number_format($vente->montant_paye_mga, 0, ',', ' ') }} MGA
                                    </div>
                                    @if($vente->montant_restant_mga > 0)
                                        <div class="text-sm text-yellow-600 dark:text-yellow-400">
                                            Reste: {{ number_format($vente->montant_restant_mga, 0, ',', ' ') }} MGA
                                        </div>
                                    @endif
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ ucfirst($vente->mode_paiement) }}
                                    </div>
                                </div>

                                <!-- Dépôt -->
                                @if($vente->depot)
                                    <div class="text-center">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $vente->depot->nom }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ ucfirst($vente->depot->type) }}
                                        </div>
                                    </div>
                                @endif

                                <!-- Statut -->
                                <div class="flex-shrink-0">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $vente->statut_paiement === 'paye' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' }}">
                                        {{ $vente->statut_paiement === 'paye' ? '✅ Payé' : '⏳ Partiel' }}
                                    </span>
                                </div>

                                <!-- Actions - PURE LIVEWIRE -->
                                <div class="flex items-center space-x-2">
                                    
                                    {{-- Bouton marquer payé (seulement si partiel) --}}
                                    @if ($vente->statut_paiement === 'partiel')
                                        <button wire:click="marquerPaye({{ $vente->id }})" 
                                                class="p-2 text-emerald-600 hover:text-emerald-800 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 rounded-lg transition-colors" 
                                                title="Marquer comme payé">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </button>
                                    @endif

                                    {{-- Bouton modifier --}}
                                    <button wire:click="editVente({{ $vente->id }})" 
                                            class="p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors" 
                                            title="Modifier la vente">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>

                                    {{-- Bouton supprimer --}}
                                    <button wire:click="deleteVente({{ $vente->id }})" 
                                            wire:confirm="Êtes-vous sûr de vouloir supprimer cette vente ?" 
                                            class="p-2 text-red-600 hover:text-red-800 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors" 
                                            title="Supprimer la vente">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>

                                    {{-- Bouton toggle détails --}}
                                    <button wire:click="toggleDetails({{ $vente->id }})" 
                                            class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded-lg transition-all" 
                                            title="Voir les détails">
                                        <svg class="w-5 h-5 {{ in_array($vente->id, $expandedVentes ?? []) ? 'rotate-180' : '' }} transition-transform" 
                                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Détails expandables - LIVEWIRE TOGGLE -->
                    @if(in_array($vente->id, $expandedVentes ?? []))
                        <div class="border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                            <div class="p-6 space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

                                    <!-- Informations générales -->
                                    <div class="space-y-3">
                                        <h4 class="flex items-center font-semibold text-gray-900 dark:text-gray-100">
                                            <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center mr-3">
                                                📋
                                            </div>
                                            Informations
                                        </h4>
                                        <div class="space-y-2 text-sm">
                                            <div class="flex justify-between">
                                                <span class="text-gray-600 dark:text-gray-400">Référence :</span>
                                                <span class="font-medium text-gray-900 dark:text-gray-100">{{ $vente->reference }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600 dark:text-gray-400">Date :</span>
                                                <span class="font-medium text-gray-900 dark:text-gray-100">{{ $vente->date?->format('d/m/Y à H:i') ?? $vente->created_at->format('d/m/Y à H:i') }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600 dark:text-gray-400">Objet :</span>
                                                <span class="font-medium text-gray-900 dark:text-gray-100">{{ $vente->objet ?: 'Non spécifié' }}</span>
                                            </div>
                                            @if($vente->vendeur_nom)
                                            <div class="flex justify-between">
                                                <span class="text-gray-600 dark:text-gray-400">Vendeur :</span>
                                                <span class="font-medium text-gray-900 dark:text-gray-100">{{ $vente->vendeur_nom }}</span>
                                            </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Lieu de vente -->
                                    @if($vente->depot)
                                    <div class="space-y-3">
                                        <h4 class="flex items-center font-semibold text-gray-900 dark:text-gray-100">
                                            <div class="w-8 h-8 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center mr-3">
                                                🏪
                                            </div>
                                            Lieu de vente
                                        </h4>
                                        <div class="space-y-2 text-sm">
                                            <div class="flex justify-between">
                                                <span class="text-gray-600 dark:text-gray-400">Nom :</span>
                                                <span class="font-medium text-gray-900 dark:text-gray-100">{{ $vente->depot->nom }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600 dark:text-gray-400">Type :</span>
                                                <span class="font-medium text-gray-900 dark:text-gray-100">{{ ucfirst($vente->depot->type) }}</span>
                                            </div>
                                            @if($vente->depot->region)
                                            <div class="flex justify-between">
                                                <span class="text-gray-600 dark:text-gray-400">Région :</span>
                                                <span class="font-medium text-gray-900 dark:text-gray-100">{{ $vente->depot->region }}</span>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    @endif

                                    <!-- Finances -->
                                    <div class="space-y-3">
                                        <h4 class="flex items-center font-semibold text-gray-900 dark:text-gray-100">
                                            <div class="w-8 h-8 bg-emerald-100 dark:bg-emerald-900 rounded-lg flex items-center justify-center mr-3">
                                                💰
                                            </div>
                                            Finances
                                        </h4>
                                        <div class="space-y-2 text-sm">
                                            <div class="flex justify-between">
                                                <span class="text-gray-600 dark:text-gray-400">Montant payé :</span>
                                                <span class="font-medium text-green-600 dark:text-green-400">+{{ number_format($vente->montant_paye_mga, 0, ',', ' ') }} MGA</span>
                                            </div>
                                            @if($vente->montant_restant_mga > 0)
                                            <div class="flex justify-between">
                                                <span class="text-gray-600 dark:text-gray-400">Montant restant :</span>
                                                <span class="font-medium text-yellow-600 dark:text-yellow-400">{{ number_format($vente->montant_restant_mga, 0, ',', ' ') }} MGA</span>
                                            </div>
                                            @endif
                                            <div class="flex justify-between">
                                                <span class="text-gray-600 dark:text-gray-400">Mode paiement :</span>
                                                <span class="font-medium text-gray-900 dark:text-gray-100">{{ ucfirst($vente->mode_paiement) }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600 dark:text-gray-400">Statut :</span>
                                                <span class="font-medium {{ $vente->statut_paiement === 'paye' ? 'text-green-600 dark:text-green-400' : 'text-yellow-600 dark:text-yellow-400' }}">
                                                    {{ $vente->statut_paiement === 'paye' ? 'Payé' : 'Partiel' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Observations -->
                                @if($vente->observation)
                                <div class="pt-4 border-t border-gray-200 dark:border-gray-600">
                                    <h4 class="flex items-center font-semibold text-gray-900 dark:text-gray-100 mb-3">
                                        <div class="w-8 h-8 bg-yellow-100 dark:bg-yellow-900 rounded-lg flex items-center justify-center mr-3">
                                            📝
                                        </div>
                                        Observations
                                    </h4>
                                    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border-l-4 border-blue-500 text-gray-700 dark:text-gray-300">
                                        {{ $vente->observation }}
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $ventes->links() }}
        </div>

    @else
        <!-- État vide -->
        <div class="text-center py-12 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="mx-auto h-12 w-12 text-gray-400 mb-4">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-12 h-12">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-1">Aucune vente trouvée</h3>
            <p class="text-gray-500 dark:text-gray-400 mb-4">Commencez par ajouter une nouvelle vente.</p>
            <button wire:click="createVente" 
                    class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Créer une vente
            </button>
        </div>
    @endif
</div>