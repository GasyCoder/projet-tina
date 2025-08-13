<div class="min-h-screen bg-gray-100 dark:bg-gray-900 pt-20 pb-6 md:px-6">
    <!-- Header Mobile/Desktop -->
    <div class="px-3 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-row items-center justify-between space-x-3 sm:flex-row sm:items-center sm:justify-between sm:space-x-0">
                <!-- Titre et bouton dark/light -->
                <div class="flex items-center space-x-4">
                    <h1 class="text-base sm:text-2xl lg:text-3xl font-semibold text-gray-900 dark:text-gray-100 tracking-tight">
                        Gestion Financière
                    </h1>
                </div>

                <!-- Boutons d'action -->
                <div wire:key="action-buttons-{{ $activeTab }}" class="flex justify-end">
                    @if($activeTab === 'transactions')
                        <button wire:click="createTransaction" class="w-9 h-9 sm:w-auto sm:h-auto 
                               inline-flex items-center justify-center 
                               p-0 sm:px-4 sm:py-2.5 
                               bg-indigo-600 dark:bg-indigo-700 text-white 
                               text-xs sm:text-sm font-medium 
                               rounded-full sm:rounded-lg shadow-sm 
                               hover:bg-indigo-700 dark:hover:bg-indigo-600
                               focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 
                               transition-all duration-150">
                            <!-- Icône mobile -->
                            <svg class="w-4 h-4 sm:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            <!-- Texte + icône desktop -->
                            <span class="hidden sm:inline-flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Nouvelle Transaction
                            </span>
                        </button>
                        @elseif($activeTab === 'comptes')
                            <button wire:click="$dispatch('create-compte')"
                                    class="w-9 h-9 sm:w-auto sm:h-auto 
                                        inline-flex items-center justify-center 
                                        p-0 sm:px-4 sm:py-2.5 
                                        bg-indigo-600 dark:bg-indigo-700 text-white 
                                        text-xs sm:text-sm font-medium 
                                        rounded-full sm:rounded-lg shadow-sm 
                                        hover:bg-indigo-700 dark:hover:bg-indigo-600
                                        focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 
                                        transition-all duration-150">
                                <!-- Icône mobile -->
                                <svg class="w-4 h-4 sm:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                <!-- Texte + icône desktop -->
                                <span class="hidden sm:inline-flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                   Recharger compte
                                </span>
                            </button>
                        @endif
                </div>
            </div>
        </div>
    </div>

    <div class="px-0 sm:px-6 lg:px-8 py-4 sm:py-6">
        <div class="max-w-7xl mx-auto">
            <!-- Alertes -->
            @if (session()->has('success'))
                <div class="mb-4 mx-3 sm:mx-0 bg-green-50 dark:bg-green-900/20 border-l-4 border-green-400 dark:border-green-600 p-4 rounded-r-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 dark:text-green-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <p class="text-green-800 dark:text-green-200 text-sm font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <!-- Container Principal -->
            <div class="bg-white dark:bg-gray-800 shadow-sm mx-0 sm:mx-0 sm:rounded-xl overflow-hidden">
                <!-- Navigation Onglets -->
                <div class="border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
                    <div class="overflow-x-auto scrollbar-hide">
                        <nav class="flex min-w-max sm:min-w-0">
                            <!-- Transactions -->
                            <button wire:click="setActiveTab('transactions')"
                                    class="flex-shrink-0 px-3 sm:px-4 py-4 text-sm font-medium border-b-2 transition-all duration-150 {{ $activeTab === 'transactions' ? 'text-indigo-600 dark:text-indigo-400 border-indigo-500 dark:border-indigo-400' : 'text-gray-500 dark:text-gray-400 border-transparent hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600' }}">
                                <span class="flex items-center whitespace-nowrap">
                                    <!-- Cercle bleu avec icône échange -->
                                    <span class="inline-flex items-center justify-center w-6 h-6 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-full mr-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <!-- Flèches d'échange -->
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m0 4l-4-4m0 0l4-4m-4 4h14" />
                                        </svg>
                                    </span>
                                    <span class="hidden sm:inline">Transactions</span>
                                    <span class="sm:hidden">Trans.</span>
                                    @if($transactionsEnAttente > 0)
                                        <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-200">
                                            {{ $transactionsEnAttente }}
                                        </span>
                                    @endif
                                </span>
                            </button>

                            <!-- Revenus -->
                            <a href="{{ request()->url() }}?tab=revenus" 
                            wire:click.prevent="setActiveTab('revenus')"
                            class="flex-shrink-0 px-3 sm:px-4 py-4 text-sm font-medium border-b-2 transition-all duration-150 {{ $activeTab === 'revenus' ? 'text-indigo-600 dark:text-indigo-400 border-indigo-500 dark:border-indigo-400' : 'text-gray-500 dark:text-gray-400 border-transparent hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600' }}">
                                <span class="flex items-center whitespace-nowrap">
                                    <!-- Cercle vert avec plus -->
                                    <span class="inline-flex items-center justify-center w-6 h-6 bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 rounded-full mr-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <circle cx="12" cy="12" r="11" stroke="currentColor" stroke-width="2" fill="none"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v8m-4-4h8" />
                                        </svg>
                                    </span>
                                    Revenus
                                </span>
                            </a>
                            <!-- Dépenses -->
                            <a href="{{ request()->url() }}?tab=depenses" 
                            wire:click.prevent="setActiveTab('depenses')"
                            class="flex-shrink-0 px-3 sm:px-4 py-4 text-sm font-medium border-b-2 transition-all duration-150 {{ $activeTab === 'depenses' ? 'text-indigo-600 dark:text-indigo-400 border-indigo-500 dark:border-indigo-400' : 'text-gray-500 dark:text-gray-400 border-transparent hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600' }}">
                                <span class="flex items-center whitespace-nowrap">
                                    <!-- Cercle rouge avec moins -->
                                    <span class="inline-flex items-center justify-center w-6 h-6 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-full mr-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <circle cx="12" cy="12" r="11" stroke="currentColor" stroke-width="2" fill="none"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h8" />
                                        </svg>
                                    </span>
                                    Dépenses
                                </span>
                            </a>
                            <!-- Comptes -->
                            <button wire:click="setActiveTab('comptes')"
                                    class="flex-shrink-0 px-3 sm:px-4 py-4 text-sm font-medium border-b-2 transition-all duration-150 {{ $activeTab === 'comptes' ? 'text-indigo-600 dark:text-indigo-400 border-indigo-500 dark:border-indigo-400' : 'text-gray-500 dark:text-gray-400 border-transparent hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600' }}">
                                <span class="flex items-center whitespace-nowrap">
                                    <!-- Cercle gris-bleu avec icône de portefeuille/carte -->
                                    <span class="inline-flex items-center justify-center w-6 h-6 bg-gray-100 dark:bg-gray-700 text-blue-600 dark:text-blue-400 rounded-full mr-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <!-- Carte bancaire stylisée -->
                                            <rect x="3" y="7" width="18" height="10" rx="2" stroke="currentColor" stroke-width="2" fill="none" />
                                            <path d="M3 10h18" stroke="currentColor" stroke-width="2" />
                                            <circle cx="7" cy="15" r="1" fill="currentColor" />
                                            <circle cx="11" cy="15" r="1" fill="currentColor" />
                                        </svg>
                                    </span>
                                    <span class="hidden sm:inline">Comptes</span>
                                    <span class="sm:hidden">Comptes</span>
                                    <span class="ml-1 text-xs">({{ $comptes->count() }})</span>
                                </span>
                            </button>
                        </nav>
                    </div>
                </div>
                <!-- Contenu des Onglets -->
                <div class="p-3 sm:p-6">
                    @elseif($activeTab === 'transactions')
                        <div wire:key="tab-transactions">
                            @if($transactions->isEmpty())
                                <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                    <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                    </svg>
                                    <p class="mt-2 text-sm">Aucune transaction trouvée.</p>
                                </div>
                            @else
                                @include('livewire.finance.tabs.transactions', [
                                    'transactions' => $transactions,
                                    'searchTerm' => $searchTerm,
                                    'filterType' => $filterType,
                                    'filterStatut' => $filterStatut,
                                    'filterPersonne' => $filterPersonne,
                                    'dateDebut' => $dateDebut,
                                    'dateFin' => $dateFin
                                ])
                            @endif
                        </div>    
                    @elseif($activeTab === 'revenus')
                        <div wire:key="tab-revenus">
                            @if($revenus->isEmpty())
                                <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                    <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <p class="mt-2 text-sm">Aucun revenu trouvé pour cette période.</p>
                                </div>
                            @else
                                @include('livewire.finance.tabs.revenus', [
                                    'revenus' => $revenus,
                                    'periodeRevenus' => $periodeRevenus,
                                    'dateDebutRevenus' => $dateDebutRevenus,
                                    'dateFinRevenus' => $dateFinRevenus,
                                    'totalRevenus' => $totalRevenus,
                                    'revenuMoyen' => $revenuMoyen,
                                    'nombreRevenus' => $nombreRevenus,
                                    'revenusEnAttente' => $revenusEnAttente,
                                    'repartitionRevenus' => $repartitionRevenus
                                ])
                            @endif
                        </div>
                    @elseif($activeTab === 'depenses')
                        <div wire:key="tab-depenses">
                            @if($depenses->isEmpty())
                                <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                    <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <p class="mt-2 text-sm">Aucune dépense trouvée pour cette période.</p>
                                </div>
                            @else
                                @include('livewire.finance.tabs.depenses', [
                                    'depenses' => $depenses,
                                    'periodeDepenses' => $periodeDepenses,
                                    'dateDebutDepenses' => $dateDebutDepenses,
                                    'dateFinDepenses' => $dateFinDepenses,
                                    'totalDepenses' => $totalDepenses,
                                    'depenseMoyenne' => $depenseMoyenne,
                                    'depensesEnAttente' => $depensesEnAttente,
                                    'nombreDepenses' => $nombreDepenses,
                                    'repartitionDepenses' => $repartitionDepenses
                                ])
                            @endif
                        </div>
                    @elseif($activeTab === 'comptes')
                           <div wire:key="tab-comptes">
                                <livewire:finance.compte-manager :key="'compte-manager-'.now()" />
                            </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Transaction Modal -->
    <div x-data="{ transactionModal: @entangle('showTransactionModal') }">
        @include('livewire.finance.modals.transaction-modal', [
            'voyagesDisponibles' => $voyagesDisponibles,
            'dechargementsDisponibles' => $dechargementsDisponibles,
            'produitsDisponibles' => $produitsDisponibles,
            'produitSelectionne' => $produitSelectionne,
            'comptes' => $comptes
        ])
    </div>
</div>