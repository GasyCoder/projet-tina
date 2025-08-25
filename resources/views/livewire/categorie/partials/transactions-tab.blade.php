{{-- resources/views/livewire/categorie/partials/transactions-tab.blade.php --}}
<div class="space-y-6">
    <!-- Statistiques rapides des transactions -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/50 dark:to-blue-800/30 p-4 rounded-lg border border-blue-200 dark:border-blue-700">
            <div class="text-center">
                <p class="text-sm font-medium text-blue-700 dark:text-blue-300">Total</p>
                <p class="text-xl font-bold text-blue-900 dark:text-blue-100">
                    {{ $dashboardData['temporel']['mois_courant']['nombre'] ?? 0 }}
                </p>
                <p class="text-xs text-blue-600 dark:text-blue-400">ce mois</p>
            </div>
        </div>
        
        <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/50 dark:to-green-800/30 p-4 rounded-lg border border-green-200 dark:border-green-700">
            <div class="text-center">
                <p class="text-sm font-medium text-green-700 dark:text-green-300">Recettes</p>
                <p class="text-xl font-bold text-green-900 dark:text-green-100">
                    {{ number_format($dashboardData['temporel']['mois_courant']['recettes'] ?? 0, 0, ',', ' ') }}
                </p>
                <p class="text-xs text-green-600 dark:text-green-400">Ar</p>
            </div>
        </div>
        
        <div class="bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900/50 dark:to-red-800/30 p-4 rounded-lg border border-red-200 dark:border-red-700">
            <div class="text-center">
                <p class="text-sm font-medium text-red-700 dark:text-red-300">Dépenses</p>
                <p class="text-xl font-bold text-red-900 dark:text-red-100">
                    {{ number_format($dashboardData['temporel']['mois_courant']['depenses'] ?? 0, 0, ',', ' ') }}
                </p>
                <p class="text-xs text-red-600 dark:text-red-400">Ar</p>
            </div>
        </div>
        
        <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/50 dark:to-purple-800/30 p-4 rounded-lg border border-purple-200 dark:border-purple-700">
            <div class="text-center">
                <p class="text-sm font-medium text-purple-700 dark:text-purple-300">Moyenne</p>
                <p class="text-xl font-bold text-purple-900 dark:text-purple-100">
                    {{ number_format($dashboardData['financier']['transaction_quotidienne_moyenne'] ?? 0, 0, ',', ' ') }}
                </p>
                <p class="text-xs text-purple-600 dark:text-purple-400">Ar/jour</p>
            </div>
        </div>
    </div>

    <!-- Filtres avancés -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <!-- Recherche -->
            <div class="flex-1 max-w-md">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Rechercher</label>
                <div class="relative">
                    <input type="text" wire:model.live.debounce.300ms="search"
                           placeholder="Référence, description, partenaire..."
                           class="w-full pl-10 pr-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm">
                    <svg class="absolute left-3 top-3 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>

            <!-- Filtres -->
            <div class="flex flex-col sm:flex-row gap-4">
                <!-- Type de transaction -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Type</label>
                    <div class="flex bg-gray-100 dark:bg-gray-700 p-1 rounded-lg">
                        <button wire:click="filterTransactions('all')" 
                                class="px-3 py-1.5 text-sm rounded-md transition-colors {{ $filter === 'all' ? 'bg-white dark:bg-gray-600 shadow-sm text-blue-600 dark:text-blue-400 font-medium' : 'text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-gray-100' }}">
                            Toutes
                        </button>
                        <button wire:click="filterTransactions('recette')" 
                                class="px-3 py-1.5 text-sm rounded-md transition-colors {{ $filter === 'recette' ? 'bg-white dark:bg-gray-600 shadow-sm text-blue-600 dark:text-blue-400 font-medium' : 'text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-gray-100' }}">
                            Recettes
                        </button>
                        <button wire:click="filterTransactions('depense')" 
                                class="px-3 py-1.5 text-sm rounded-md transition-colors {{ $filter === 'depense' ? 'bg-white dark:bg-gray-600 shadow-sm text-blue-600 dark:text-blue-400 font-medium' : 'text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-gray-100' }}">
                            Dépenses
                        </button>
                    </div>
                </div>

                <!-- Période -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Période</label>
                    <select wire:model.live="periode"
                            class="px-3 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm">
                        <option value="all">Toute période</option>
                        <option value="mois_courant">Ce mois</option>
                        <option value="annee_courante">Cette année</option>
                    </select>
                </div>

                <!-- Boutons d'action -->
                <div class="flex items-end space-x-2">
                    <button wire:click="exportTransactions"
                            class="px-4 py-2.5 bg-gray-600 hover:bg-gray-700 text-white text-sm rounded-lg transition-colors flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Export
                    </button>
                    
                    @if($categorie->type === 'recette')
                        <button wire:click="openRecetteModal"
                                class="px-4 py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm rounded-lg transition-colors flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Nouvelle Recette
                        </button>
                    @else
                        <button wire:click="openDepenseModal"
                                class="px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm rounded-lg transition-colors flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                            </svg>
                            Nouvelle Dépense
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des transactions -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        {{-- En-tête desktop --}}
        <div class="hidden lg:grid grid-cols-12 gap-4 px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 border-b border-gray-200 dark:border-gray-700">
            <div class="col-span-3 text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Référence & Date</div>
            <div class="col-span-4 text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Description</div>
            <div class="col-span-2 text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Partenaire</div>
            <div class="col-span-2 text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider text-right">Montant</div>
            <div class="col-span-1 text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider text-center">Actions</div>
        </div>

        {{-- Liste des transactions --}}
        <div class="divide-y divide-gray-200 dark:divide-gray-700">
            @forelse($transactions as $transaction)
                @php
                    $isRecette = $transaction->type === 'recette';
                @endphp

                {{-- Version Desktop --}}
                <div class="hidden lg:grid grid-cols-12 gap-4 p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all duration-300 group">
                    
                    {{-- Référence & Date --}}
                    <div wire:click="showTransactionDetail({{ $transaction->id }})" class="col-span-3 cursor-pointer">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-lg flex items-center justify-center {{ $isRecette ? 'bg-green-100 dark:bg-green-900/30' : 'bg-red-100 dark:bg-red-900/30' }}">
                                @if($isRecette)
                                    <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                    </svg>
                                @endif
                            </div>
                            <div>
                                <div class="font-semibold text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                    {{ $transaction->reference }}
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $transaction->date_formattee }}
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Description --}}
                    <div wire:click="showTransactionDetail({{ $transaction->id }})" class="col-span-4 cursor-pointer flex items-center">
                        <div class="flex items-center space-x-2">
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $isRecette ? 'bg-green-50 text-green-700 dark:bg-green-900/20 dark:text-green-300' : 'bg-red-50 text-red-700 dark:bg-red-900/20 dark:text-red-300' }}">
                                {{ $isRecette ? 'Recette' : 'Dépense' }}
                            </span>
                            <span class="text-gray-700 dark:text-gray-300 truncate flex-1">{{ $transaction->description }}</span>
                            @if ($transaction->statut === 'sortie')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">
                                    En attente
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Partenaire --}}
                    <div wire:click="showTransactionDetail({{ $transaction->id }})" class="col-span-2 cursor-pointer flex items-center">
                        <div class="text-sm text-gray-500 dark:text-gray-400 truncate">
                            {{ $transaction->partenaire?->nom ?? '-' }}
                        </div>
                    </div>

                    {{-- Montant --}}
                    <div wire:click="showTransactionDetail({{ $transaction->id }})" class="col-span-2 cursor-pointer text-right">
                        <div class="text-lg font-bold {{ $isRecette ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                            {{ $transaction->montant_formate }}
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">
                            {{ ucfirst($transaction->mode_paiement) }}
                            @if($transaction->type_compte_mobilemoney_or_banque)
                                ({{ $transaction->type_compte_mobilemoney_or_banque }})
                            @endif
                        </div>
                    </div>
                    
                    {{-- Actions --}}
                    <div class="col-span-1 flex items-center justify-center">
                        <div class="flex items-center space-x-1 opacity-0 group-hover:opacity-100 transition-opacity">
                            <button wire:click.stop="showTransactionDetail({{ $transaction->id }})"
                                    class="px-2 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs rounded-md transition-all duration-200 flex items-center"
                                    title="Voir détails">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Version Mobile/Tablet --}}
                <div class="lg:hidden bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    
                    {{-- Header Mobile --}}
                    <div wire:click="showTransactionDetail({{ $transaction->id }})" class="p-4 cursor-pointer">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center {{ $isRecette ? 'bg-green-100 dark:bg-green-900/30' : 'bg-red-100 dark:bg-red-900/30' }}">
                                    @if($isRecette)
                                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                    @else
                                        <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                        </svg>
                                    @endif
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900 dark:text-white">
                                        {{ $transaction->reference }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $transaction->date_formattee }}
                                    </div>
                                </div>
                            </div>
                            <span class="px-3 py-1 rounded-full text-xs font-medium {{ $isRecette ? 'bg-green-50 text-green-700 dark:bg-green-900/20 dark:text-green-300' : 'bg-red-50 text-red-700 dark:bg-red-900/20 dark:text-red-300' }}">
                                {{ $isRecette ? 'Recette' : 'Dépense' }}
                            </span>
                        </div>

                        {{-- Description & Partenaire --}}
                        <div class="mb-3">
                            <div class="text-sm font-medium text-gray-900 dark:text-white mb-1">
                                {{ $transaction->description }}
                            </div>
                            @if($transaction->partenaire)
                                <div class="text-xs text-gray-600 dark:text-gray-400 flex items-center">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    {{ $transaction->partenaire->nom }}
                                </div>
                            @endif
                            <div class="text-xs text-gray-500 dark:text-gray-400 flex items-center mt-1">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                </svg>
                                {{ ucfirst($transaction->mode_paiement) }}
                                @if($transaction->type_compte_mobilemoney_or_banque)
                                    ({{ $transaction->type_compte_mobilemoney_or_banque }})
                                @endif
                            </div>
                            @if ($transaction->statut === 'sortie')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300 mt-2">
                                    En attente
                                </span>
                            @endif
                        </div>

                        {{-- Montant --}}
                        <div class="flex items-center justify-between">
                            <div class="text-xl font-bold {{ $isRecette ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                {{ $transaction->montant_formate }}
                            </div>
                            <button wire:click.stop="showTransactionDetail({{ $transaction->id }})"
                                    class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs rounded-lg transition-colors flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                Détails
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-16">
                    <div class="w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6M7 21h10a2 2 0 002-2V7a2 2 0 00-2-2h-6.586a1 1 0 00-.707.293l-5.414 5.414A1 1 0 003 11.414V19a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Aucune transaction trouvée</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-6">
                        @if ($search || $filter !== 'all' || $periode !== 'all')
                            Modifiez vos critères de recherche pour voir plus de résultats
                        @else
                            Créez votre première transaction pour cette catégorie
                        @endif
                    </p>
                    
                    @if(!$search && $filter === 'all' && $periode === 'all')
                        <div class="flex justify-center space-x-3">
                            @if($categorie->type === 'recette')
                                <button wire:click="openRecetteModal"
                                        class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Première Recette
                                </button>
                            @else
                                <button wire:click="openDepenseModal"
                                        class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                    </svg>
                                    Première Dépense
                                </button>
                            @endif
                        </div>
                    @endif
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($transactions->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>

    <!-- Résumé des filtres actifs -->
    @if($search || $filter !== 'all' || $periode !== 'all')
        <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg border border-blue-200 dark:border-blue-700">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.414A1 1 0 013 6.707V4z" />
                    </svg>
                    <span class="text-sm font-medium text-blue-800 dark:text-blue-200">Filtres actifs :</span>
                    <div class="flex items-center space-x-2">
                        @if($search)
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-blue-100 dark:bg-blue-800 text-blue-800 dark:text-blue-200">
                                Recherche: "{{ $search }}"
                            </span>
                        @endif
                        @if($filter !== 'all')
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-blue-100 dark:bg-blue-800 text-blue-800 dark:text-blue-200">
                                Type: {{ ucfirst($filter) }}
                            </span>
                        @endif
                        @if($periode !== 'all')
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-blue-100 dark:bg-blue-800 text-blue-800 dark:text-blue-200">
                                Période: {{ $periode === 'mois_courant' ? 'Ce mois' : 'Cette année' }}
                            </span>
                        @endif
                    </div>
                </div>
                <button wire:click="$set('search', ''); $set('filter', 'all'); $set('periode', 'all')"
                        class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-200 text-sm font-medium">
                    Effacer filtres
                </button>
            </div>
        </div>
    @endif
</div>