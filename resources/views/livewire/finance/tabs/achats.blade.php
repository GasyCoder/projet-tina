{{-- resources/views/livewire/finance/tabs/achats.blade.php --}}
<div class="space-y-6">
    <!-- Filtres -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
        <div class="flex flex-col lg:flex-row lg:items-center gap-4">
            <div class="flex-1">
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input wire:model.live="searchTerm" 
                           type="text" 
                           placeholder="Rechercher par référence, objet..."
                           class="pl-10 w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>

            <div class="flex gap-3">
                <select wire:model.live="filterDate" 
                        class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Toutes les dates</option>
                    <option value="today">Aujourd'hui</option>
                    <option value="week">Cette semaine</option>
                    <option value="month">Ce mois</option>
                    <option value="year">Cette année</option>
                </select>

                <select wire:model.live="filterModePaiement" 
                        class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Tous les modes</option>
                    <option value="especes">Espèces</option>
                    <option value="AirtelMoney">AirtelMoney</option>
                    <option value="Mvola">Mvola</option>
                    <option value="OrangeMoney">OrangeMoney</option>
                    <option value="banque">Banque</option>
                </select>

                <button wire:click="clearFilters" 
                        class="px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 transition-colors" 
                        title="Effacer les filtres">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    @if ($achats->count() > 0)
        <!-- Liste des achats -->
        <div class="space-y-4">
            @foreach ($achats as $achat)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden transition-all duration-200 hover:shadow-md">
                    
                    <!-- Ligne principale -->
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            
                            <!-- Informations principales -->
                            <div class="flex items-center space-x-4 flex-1">
                                
                                <!-- Badge type -->
                                <div class="flex-shrink-0">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                        🛒 Achat
                                    </span>
                                </div>

                                <!-- Détails -->
                                <div>
                                    <div class="font-semibold text-gray-900 dark:text-gray-100">
                                        {{ $achat->reference }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $achat->objet ?: 'Achat' }} • {{ $achat->date?->format('d/m/Y') ?? $achat->created_at->format('d/m/Y') }}
                                    </div>
                                </div>
                            </div>

                            <!-- Montant et statut -->
                            <div class="flex items-center space-x-4">
                                <div class="text-right">
                                    <div class="text-xl font-bold text-red-600 dark:text-red-400">
                                        -{{ $achat->montant_mga_formatted }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $achat->mode_paiement_label }}
                                    </div>
                                </div>

                                <div class="flex-shrink-0">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $achat->statut ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' }}">
                                        {{ $achat->statut ? '✅ Confirmé' : '⏳ En attente' }}
                                    </span>
                                </div>

                                <!-- Actions - PURE LIVEWIRE -->
                                <div class="flex items-center space-x-2">
                                    {{-- Bouton confirmer (seulement si pas confirmé) --}}
                                    @if (!$achat->statut)
                                        <button wire:click="confirmerAchat({{ $achat->id }})" 
                                                class="p-2 text-emerald-600 hover:text-emerald-800 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 rounded-lg transition-colors" 
                                                title="Confirmer l'achat">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </button>
                                    @endif

                                    {{-- Bouton modifier --}}
                                    <button wire:click="editAchat({{ $achat->id }})" 
                                            class="p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors" 
                                            title="Modifier l'achat">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>

                                    {{-- Bouton supprimer --}}
                                    <button wire:click="deleteAchat({{ $achat->id }})" 
                                            wire:confirm="Êtes-vous sûr de vouloir supprimer cet achat ?" 
                                            class="p-2 text-red-600 hover:text-red-800 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors" 
                                            title="Supprimer l'achat">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>

                                    {{-- Bouton toggle détails --}}
                                    <button wire:click="toggleDetails({{ $achat->id }})" 
                                            class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded-lg transition-all" 
                                            title="Voir les détails">
                                        <svg class="w-5 h-5 {{ in_array($achat->id, $expandedAchats ?? []) ? 'rotate-180' : '' }} transition-transform" 
                                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Détails expandables - LIVEWIRE TOGGLE -->
                    @if(in_array($achat->id, $expandedAchats ?? []))
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
                                                <span class="font-medium text-gray-900 dark:text-gray-100">{{ $achat->reference }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600 dark:text-gray-400">Date :</span>
                                                <span class="font-medium text-gray-900 dark:text-gray-100">{{ $achat->date?->format('d/m/Y à H:i') ?? $achat->created_at->format('d/m/Y à H:i') }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600 dark:text-gray-400">Objet :</span>
                                                <span class="font-medium text-gray-900 dark:text-gray-100">{{ $achat->objet ?: 'Non spécifié' }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Participants -->
                                    @if($achat->from_nom || $achat->to_nom)
                                    <div class="space-y-3">
                                        <h4 class="flex items-center font-semibold text-gray-900 dark:text-gray-100">
                                            <div class="w-8 h-8 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center mr-3">
                                                👥
                                            </div>
                                            Participants
                                        </h4>
                                        <div class="space-y-2 text-sm">
                                            @if($achat->from_nom)
                                            <div class="flex justify-between">
                                                <span class="text-gray-600 dark:text-gray-400">Émetteur :</span>
                                                <span class="font-medium text-gray-900 dark:text-gray-100 text-right max-w-32 truncate" title="{{ $achat->from_nom }}">{{ $achat->from_nom }}</span>
                                            </div>
                                            @endif
                                            @if($achat->to_nom)
                                            <div class="flex justify-between">
                                                <span class="text-gray-600 dark:text-gray-400">Bénéficiaire :</span>
                                                <span class="font-medium text-gray-900 dark:text-gray-100 text-right max-w-32 truncate" title="{{ $achat->to_nom }}">{{ $achat->to_nom }}</span>
                                            </div>
                                            @endif
                                            @if($achat->to_compte)
                                            <div class="flex justify-between">
                                                <span class="text-gray-600 dark:text-gray-400">Compte destination :</span>
                                                <span class="font-medium text-gray-900 dark:text-gray-100 text-right max-w-32 truncate" title="{{ $achat->to_compte }}">{{ $achat->to_compte }}</span>
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
                                                <span class="text-gray-600 dark:text-gray-400">Montant :</span>
                                                <span class="font-medium text-red-600 dark:text-red-400">-{{ $achat->montant_mga_formatted }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600 dark:text-gray-400">Mode paiement :</span>
                                                <span class="font-medium text-gray-900 dark:text-gray-100">{{ $achat->mode_paiement_label }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Observations -->
                                @if($achat->observation)
                                <div class="pt-4 border-t border-gray-200 dark:border-gray-600">
                                    <h4 class="flex items-center font-semibold text-gray-900 dark:text-gray-100 mb-3">
                                        <div class="w-8 h-8 bg-yellow-100 dark:bg-yellow-900 rounded-lg flex items-center justify-center mr-3">
                                            📝
                                        </div>
                                        Observations
                                    </h4>
                                    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border-l-4 border-blue-500 text-gray-700 dark:text-gray-300">
                                        {{ $achat->observation }}
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
            {{ $achats->links() }}
        </div>

    @else
        <!-- État vide -->
        <div class="text-center py-12 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="mx-auto h-12 w-12 text-gray-400 mb-4">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-12 h-12">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-1">Aucun achat trouvé</h3>
            <p class="text-gray-500 dark:text-gray-400 mb-4">Commencez par ajouter un nouveau achat.</p>
            <button wire:click="createAchat" 
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Créer un achat
            </button>
        </div>
    @endif
</div>