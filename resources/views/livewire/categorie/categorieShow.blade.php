{{-- resources/views/livewire/categorie/categorieShow.blade.php --}}
<div class="min-h-screen bg-gray-100 dark:bg-gray-900 pt-20 pb-6 md:px-6">
    <!-- Header Mobile/Desktop -->
    <div class="px-3 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div
                class="flex flex-row items-center justify-between space-x-3 sm:flex-row sm:items-center sm:justify-between sm:space-x-0">
                <!-- Titre et info catégorie -->
                <div class="flex items-center space-x-4">
                    <a href="{{ route('categories.index') }}" 
                       class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                    <div class="flex items-center space-x-3">
                        <div
                            class="w-12 h-12 rounded-xl {{ $categorie->type == 'recette' ? 'bg-green-100 dark:bg-green-900' : 'bg-red-100 dark:bg-red-900' }} flex items-center justify-center">
                            <span
                                class="text-lg font-bold {{ $categorie->type == 'recette' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                {{ $categorie->code_comptable }}
                            </span>
                        </div>
                        <div>
                            <h1
                                class="text-base sm:text-xl lg:text-2xl font-semibold text-gray-900 dark:text-gray-100 tracking-tight">
                                {{ $categorie->nom }}
                            </h1>
                            <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 flex items-center">
                                <span
                                    class="w-2 h-2 mr-2 rounded-full {{ $categorie->is_active ? 'bg-green-400' : 'bg-red-400' }}"></span>
                                {{ ucfirst($categorie->type) }} - {{ $categorie->is_active ? 'Active' : 'Inactive' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Boutons d'action séparés -->
                <div class="flex justify-end space-x-2">
                    <!-- Bouton Nouvelle Recette -->
                    <button wire:click="openRecetteModal"
                        class="w-8 h-8 sm:w-auto sm:h-auto inline-flex items-center justify-center p-0 sm:px-3 sm:py-2 bg-green-600 dark:bg-green-700 text-white text-xs sm:text-sm font-medium rounded-lg shadow-sm hover:bg-green-700 dark:hover:bg-green-600 transition-all duration-150">
                        <svg class="w-4 h-4 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span class="hidden sm:inline">Nouvelle Recette</span>
                    </button>
                    
                    <!-- Bouton Nouvelle Dépense -->
                    <button wire:click="openDepenseModal"
                        class="w-8 h-8 sm:w-auto sm:h-auto inline-flex items-center justify-center p-0 sm:px-3 sm:py-2 bg-red-600 dark:bg-red-700 text-white text-xs sm:text-sm font-medium rounded-lg shadow-sm hover:bg-red-700 dark:hover:bg-red-600 transition-all duration-150">
                        <svg class="w-4 h-4 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                        </svg>
                        <span class="hidden sm:inline">Nouvelle Dépense</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="px-0 sm:px-6 lg:px-8 py-4 sm:py-6">
        <div class="max-w-7xl mx-auto">
            <!-- Alertes -->
            @if (session()->has('success'))
                <div
                    class="mb-4 mx-3 sm:mx-0 bg-green-50 dark:bg-green-900/20 border-l-4 border-green-400 dark:border-green-600 p-4 rounded-r-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 dark:text-green-400 mr-3 flex-shrink-0" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <p class="text-green-800 dark:text-green-200 text-sm font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <!-- Container Principal -->
            <div class="bg-white dark:bg-gray-800 shadow-sm mx-0 sm:mx-0 sm:rounded-xl overflow-hidden">

                <!-- Statistiques de la catégorie -->
                <div class="p-3 sm:p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between mb-3">
                        <h2 class="text-sm sm:text-base font-semibold text-gray-900 dark:text-white flex items-center">
                            <svg class="w-4 h-4 mr-2 text-blue-600 dark:text-blue-400" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Statistiques
                        </h2>
                        <div class="flex items-center space-x-1">
                            <button wire:click="exportTransactions"
                                class="text-xs bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-600 dark:text-gray-300 px-2 py-1 rounded-md flex items-center transition-colors">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Export
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
                        <div
                            class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/50 dark:to-blue-800/30 p-3 rounded-lg border border-blue-200 dark:border-blue-700">
                            <div class="text-center">
                                <p
                                    class="text-xs font-semibold text-blue-700 dark:text-blue-300 uppercase tracking-wide">
                                    Total</p>
                                <p class="text-lg sm:text-xl font-bold text-blue-900 dark:text-blue-100 mt-1">
                                    {{ number_format($statistiques['montant_total'], 0, ',', ' ') }} Ar
                                </p>
                            </div>
                        </div>

                        <div
                            class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/50 dark:to-green-800/30 p-3 rounded-lg border border-green-200 dark:border-green-700">
                            <div class="text-center">
                                <p
                                    class="text-xs font-semibold text-green-700 dark:text-green-300 uppercase tracking-wide">
                                    Ce mois</p>
                                <p class="text-lg sm:text-xl font-bold text-green-900 dark:text-green-100 mt-1">
                                    {{ number_format($statistiques['montant_mois'], 0, ',', ' ') }} Ar
                                </p>
                            </div>
                        </div>

                        <div
                            class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/50 dark:to-purple-800/30 p-3 rounded-lg border border-purple-200 dark:border-purple-700">
                            <div class="text-center">
                                <p
                                    class="text-xs font-semibold text-purple-700 dark:text-purple-300 uppercase tracking-wide">
                                    Transactions</p>
                                <p class="text-lg sm:text-xl font-bold text-purple-900 dark:text-purple-100 mt-1">
                                    {{ $statistiques['total_transactions'] }}
                                </p>
                            </div>
                        </div>

                        <div
                            class="bg-gradient-to-br from-yellow-50 to-yellow-100 dark:from-yellow-900/50 dark:to-yellow-800/30 p-3 rounded-lg border border-yellow-200 dark:border-yellow-700">
                            <div class="text-center">
                                <p
                                    class="text-xs font-semibold text-yellow-700 dark:text-yellow-300 uppercase tracking-wide">
                                    Moyenne</p>
                                <p class="text-lg sm:text-xl font-bold text-yellow-900 dark:text-yellow-100 mt-1">
                                    {{ number_format($statistiques['moyenne_transaction'], 0, ',', ' ') }} Ar
                                </p>
                            </div>
                        </div>

                        <div
                            class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600 p-3 rounded-lg border border-gray-200 dark:border-gray-500 col-span-2 sm:col-span-1">
                            <div class="text-center">
                                <p
                                    class="text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wide">
                                    Dernière</p>
                                <p class="text-lg sm:text-xl font-bold text-gray-900 dark:text-gray-100 mt-1">
                                    {{ $statistiques['derniere_transaction'] ?? 'Aucune' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filtres des transactions -->
                <div class="p-3 sm:p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex flex-col sm:flex-row gap-4 flex-1">
                            <div class="flex-1">
                                <div class="relative">
                                    <input type="text" wire:model.live.debounce.300ms="search"
                                        placeholder="Rechercher une transaction..."
                                        class="w-full pl-8 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm">
                                    <svg class="absolute left-2.5 top-2.5 h-4 w-4 text-gray-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                            </div>

                            <div class="flex gap-2">
                                 <div class="flex bg-gray-100 dark:bg-gray-700 p-1 rounded-lg">
                                <button wire:click="filterTransactions('all')" class="px-2 py-1 text-xs rounded-md {{ $filter === 'all' ? 'bg-white dark:bg-gray-600 shadow-sm text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-gray-100' }}">
                                    Toutes
                                </button>
                                <button wire:click="filterTransactions('recette')" class="px-2 py-1 text-xs rounded-md {{ $filter === 'recette' ? 'bg-white dark:bg-gray-600 shadow-sm text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-gray-100' }}">
                                    Recette
                                </button>
                                <button wire:click="filterTransactions('depense')" class="px-2 py-1 text-xs rounded-md {{ $filter === 'depense' ? 'bg-white dark:bg-gray-600 shadow-sm text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-gray-100' }}">
                                      Dépense
                                </button>
                            </div>

                                <select wire:model.live="periode"
                                    class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm">
                                    <option value="all">Toute période</option>
                                    <option value="mois_courant">Ce mois</option>
                                    <option value="annee_courante">Cette année</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

<!-- Liste des transactions -->
<div class="p-3 sm:p-6">
    {{-- En-tête desktop --}}
    <div class="hidden lg:grid grid-cols-12 gap-4 mb-3 px-4 py-3 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
        <div class="col-span-3 text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Référence & Date</div>
        <div class="col-span-4 text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Description</div>
        <div class="col-span-2 text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Partenaire</div>
        <div class="col-span-2 text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider text-right">Montant</div>
        <div class="col-span-1 text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider text-center">Actions</div>
    </div>

    {{-- Liste --}}
    <div class="space-y-3">
        @forelse($transactions as $transaction)
            @php
                $isRecette = $categorie->type === 'recette';
            @endphp

            {{-- Version Desktop --}}
            <div class="hidden lg:grid grid-cols-12 gap-4 p-4 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 hover:shadow-lg hover:border-blue-300 dark:hover:border-blue-600 transition-all duration-300 group">
                
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
                        <span class="px-3 py-1 rounded-full text-xs font-medium {{ $isRecette ? 'bg-green-50 text-green-700 dark:bg-green-900/20 dark:text-green-300' : 'bg-red-50 text-red-700 dark:bg-red-900/20 dark:text-red-300' }}">
                            {{ $isRecette ? 'Recette' : 'Dépense' }}
                        </span>
                        <span class="text-gray-700 dark:text-gray-300 truncate">{{ $transaction->description }}</span>
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
                    @if($transaction->justificatif)
                        <div class="text-xs text-gray-500 dark:text-gray-400">
                            {{ $transaction->justificatif }}
                        </div>
                    @endif
                </div>
                
                {{-- Actions --}}
                <div class="col-span-1 flex items-center justify-center space-x-2">
                    <button wire:click.stop="showTransactionDetail({{ $transaction->id }})"
                            class="px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs rounded-lg transition-all duration-200 flex items-center space-x-1 shadow-sm hover:shadow-md transform hover:-translate-y-0.5"
                            title="Voir détails">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Version Mobile/Tablet --}}
            <div class="lg:hidden bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-lg transition-all duration-300">
                
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
                        @if($transaction->justificatif)
                            <div class="text-xs text-gray-500 dark:text-gray-400 flex items-center mt-1">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6M7 21h10a2 2 0 002-2V7a2 2 0 00-2-2h-6.586a1 1 0 00-.707.293l-5.414 5.414A1 1 0 003 11.414V19a2 2 0 002 2z" />
                                </svg>
                                {{ $transaction->justificatif }}
                            </div>
                        @endif
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
                    </div>
                </div>

                {{-- Actions Mobile --}}
                <div class="border-t border-gray-100 dark:border-gray-700 p-4">
                    <div class="flex space-x-3">
                        <button wire:click="showTransactionDetail({{ $transaction->id }})"
                                class="flex-1 px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition-all duration-200 flex items-center justify-center space-x-2 shadow-sm active:scale-95"
                                title="Voir détails">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <span>Voir Détails</span>
                        </button>
                        
                        @if($transaction->notes)
                            <button class="px-4 py-3 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-sm rounded-lg flex items-center justify-center space-x-2"
                                    title="Notes disponibles">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-16 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
                <div class="w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6M7 21h10a2 2 0 002-2V7a2 2 0 00-2-2h-6.586a1 1 0 00-.707.293l-5.414 5.414A1 1 0 003 11.414V19a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Aucune transaction trouvée</h3>
                <p class="text-gray-500 dark:text-gray-400 mb-4">
                    @if ($search || $filter !== 'all' || $periode !== 'all')
                        Modifiez vos critères de recherche pour voir plus de résultats
                    @else
                        Créez votre première transaction pour cette catégorie
                    @endif
                </p>
                @if (!$search && $filter === 'all' && $periode === 'all')
                    <div class="flex flex-col sm:flex-row gap-3 justify-center items-center">
                        <button wire:click="openRecetteModal"
                                class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Créer une Recette
                        </button>
                        <button wire:click="openDepenseModal"
                                class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                            </svg>
                            Créer une Dépense
                        </button>
                    </div>
                @endif
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $transactions->links() }}
    </div>
</div>
            </div>
        </div>
    </div>

    <!-- Modales -->
    @include('livewire.categorie.modales.categories-transaction-modales')
</div>