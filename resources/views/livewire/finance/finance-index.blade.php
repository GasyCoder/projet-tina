<!-- resources/views/livewire/finance/finance-index.blade.php -->
<div class="min-h-screen bg-gray-100 pt-16 pb-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <h1 class="text-3xl font-semibold text-gray-900 tracking-tight">Gestion Financière</h1>
            <div wire:key="action-buttons-{{ $activeTab }}" class="flex gap-3">
                @if($activeTab === 'transactions')
                    <button 
                        wire:click="createTransaction" 
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-150"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Nouvelle Transaction
                    </button>
                @elseif($activeTab === 'comptes')
                    <button 
                        wire:click="createCompte" 
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-150"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        Nouveau Compte
                    </button>
                @endif
            </div>
        </div>

        <!-- Alertes -->
        @if (session()->has('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4 rounded-r-lg">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <p class="text-green-800 text-sm font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <!-- Onglets -->
        <div class="bg-white rounded-lg sm:rounded-xl shadow-sm mx-2 sm:mx-0">
            <div class="border-b border-gray-200">
                <nav class="flex -mb-px overflow-x-auto scrollbar-hide px-2 sm:px-4"
                    style="scrollbar-width: none; -ms-overflow-style: none;">
                    <a href="{{ request()->url() }}?tab=suivi" wire:click.prevent="setActiveTab('suivi')"
                        class="flex-shrink-0 py-3 sm:py-4 px-2 sm:px-3 text-xs sm:text-sm font-medium transition-all duration-150 {{ $activeTab === 'suivi' ? 'text-indigo-600 border-b-2 border-indigo-500' : 'text-gray-500 hover:text-gray-700 hover:border-b-2 hover:border-gray-300' }}">
                        <span class="flex items-center whitespace-nowrap">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-1 sm:mr-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2M9 19" />
                            </svg>
                            <span class="hidden sm:inline">Suivi Global</span>
                            <span class="sm:hidden">Suivi</span>
                        </span>
                    </a>
                    <a href="{{ request()->url() }}?tab=revenus" wire:click.prevent="setActiveTab('revenus')"
                        class="flex-shrink-0 py-3 sm:py-4 px-2 sm:px-3 text-xs sm:text-sm font-medium transition-all duration-150 {{ $activeTab === 'revenus' ? 'text-indigo-600 border-b-2 border-indigo-500' : 'text-gray-500 hover:text-gray-700 hover:border-b-2 hover:border-gray-300' }}">
                        <span class="flex items-center whitespace-nowrap">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-1 sm:mr-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Revenus
                        </span>
                    </a>
                    <a href="{{ request()->url() }}?tab=depenses" wire:click.prevent="setActiveTab('depenses')"
                        class="flex-shrink-0 py-3 sm:py-4 px-2 sm:px-3 text-xs sm:text-sm font-medium transition-all duration-150 {{ $activeTab === 'depenses' ? 'text-indigo-600 border-b-2 border-indigo-500' : 'text-gray-500 hover:text-gray-700 hover:border-b-2 hover:border-gray-300' }}">
                        <span class="flex items-center whitespace-nowrap">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-1 sm:mr-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Dépenses
                        </span>
                    </a>
                    <button wire:click="setActiveTab('transactions')"
                        class="flex-shrink-0 py-3 sm:py-4 px-2 sm:px-3 text-xs sm:text-sm font-medium transition-all duration-150 {{ $activeTab === 'transactions' ? 'text-indigo-600 border-b-2 border-indigo-500' : 'text-gray-500 hover:text-gray-700 hover:border-b-2 hover:border-gray-300' }}">
                        <span class="flex items-center whitespace-nowrap">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-1 sm:mr-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                            <span class="hidden sm:inline">Transactions</span>
                            <span class="sm:hidden">Trans.</span>
                            @if($transactionsEnAttente > 0)
                                <span
                                    class="ml-1 sm:ml-2 inline-flex items-center px-1.5 sm:px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    {{ $transactionsEnAttente }}
                                </span>
                            @endif
                        </span>
                    </button>
                    <button wire:click="setActiveTab('comptes')"
                        class="flex-shrink-0 py-3 sm:py-4 px-2 sm:px-3 text-xs sm:text-sm font-medium transition-all duration-150 {{ $activeTab === 'comptes' ? 'text-indigo-600 border-b-2 border-indigo-500' : 'text-gray-500 hover:text-gray-700 hover:border-b-2 hover:border-gray-300' }}">
                        <span class="flex items-center whitespace-nowrap">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-1 sm:mr-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <span class="hidden sm:inline">Comptes ({{ $comptes->count() }})</span>
                            <span class="sm:hidden">Comptes</span>
                            <span class="sm:hidden ml-1 text-xs">({{ $comptes->count() }})</span>
                        </span>
                    </button>
                    <button wire:click="setActiveTab('rapports')"
                        class="flex-shrink-0 py-3 sm:py-4 px-2 sm:px-3 text-xs sm:text-sm font-medium transition-all duration-150 {{ $activeTab === 'rapports' ? 'text-indigo-600 border-b-2 border-indigo-500' : 'text-gray-500 hover:text-gray-700 hover:border-b-2 hover:border-gray-300' }}">
                        <span class="flex items-center whitespace-nowrap">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-1 sm:mr-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2M9 19" />
                            </svg>
                            Rapports
                        </span>
                    </button>
                </nav>
            </div>
            <!-- Contenu des tabs -->
            <div class="p-6">
                @if($activeTab === 'suivi')
                    <div wire:key="tab-suivi">
                        @if($transactions->isEmpty())
                            <div class="text-center py-8 text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2M9 19" />
                                </svg>
                                <p class="mt-2">Aucune transaction trouvée pour cette période.</p>
                            </div>
                        @else
                            @include('livewire.finance.tabs.suivi-globale', [
            'transactions' => $transactions,
            'dateDebut' => $dateDebut,
            'dateFin' => $dateFin,
            'filterType' => $filterType,
            'filterStatut' => $filterStatut,
            'totalEntrees' => $totalEntrees,
            'totalSorties' => $totalSorties,
            'beneficeNet' => $beneficeNet,
            'repartitionParType' => $repartitionParType,
            'repartitionParStatut' => $repartitionParStatut,
            'transactionsVoyage' => $transactionsVoyage,
            'transactionsAutre' => $transactionsAutre
        ])
                        @endif
                    </div>
                @elseif($activeTab === 'revenus')
                    <div wire:key="tab-revenus">
                        @if($revenus->isEmpty())
                            <div class="text-center py-8 text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="mt-2">Aucun revenu trouvé pour cette période.</p>
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
                            <div class="text-center py-8 text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="mt-2">Aucune dépense trouvée pour cette période.</p>
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
                @elseif($activeTab === 'transactions')
                    <div wire:key="tab-transactions">
                        @if($transactions->isEmpty())
                            <div class="text-center py-8 text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                </svg>
                                <p class="mt-2">Aucune transaction trouvée.</p>
                            </div>
                        @else
                            @include('livewire.finance.tabs.transactions', [
            'transactions' => $transactions
        ])
                        @endif
                    </div>
                @elseif($activeTab === 'comptes')
                    <div wire:key="tab-comptes">
                        @if($comptes->isEmpty())
                            <div class="text-center py-8 text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                <p class="mt-2">Aucun compte trouvé.</p>
                            </div>
                        @else
                            @include('livewire.finance.tabs.comptes', [
            'comptes' => $comptes
        ])
                        @endif
                    </div>
                @elseif($activeTab === 'rapports')
                    <div wire:key="tab-rapports">
                        @include('livewire.finance.tabs.rapports', [
        'totalEntrees' => $totalEntrees,
        'totalSorties' => $totalSorties,
        'beneficeNet' => $beneficeNet
    ])
                    </div>
                @endif
            </div>
        </div>

        <!-- Modals -->
        <div x-data="{ transactionModal: @entangle('showTransactionModal'), compteModal: @entangle('showCompteModal') }">
            @include('livewire.finance.modals.transaction-modal', [
    'voyagesDisponibles' => $voyagesDisponibles,
    'comptes' => $comptes
])
            @include('livewire.finance.modals.compte-modal')
        </div>
    </div>

    <!-- Client-side script for Livewire 3 event handling -->
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('tab-changed', (event) => {
                console.log('Tab changed to:', event.tab);
                // Force re-render of Livewire component to ensure bindings
                Livewire.refresh();
            });

            Livewire.on('open-transaction-modal', () => {
                console.log('Opening transaction modal');
            });

            Livewire.on('open-compte-modal', () => {
                console.log('Opening compte modal');
            });

            Livewire.on('close-transaction-modal', () => {
                console.log('Closing transaction modal');
            });

            Livewire.on('close-compte-modal', () => {
                console.log('Closing compte modal');
            });
        });
    </script>
</div>