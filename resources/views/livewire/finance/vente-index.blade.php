{{-- resources/views/livewire/finance/vente-index.blade.php --}}
{{-- PURE LIVEWIRE + TAILWIND - SANS ALPINE.JS --}}

<div class="min-h-screen bg-gray-100 dark:bg-gray-900 pt-20 pb-6 md:px-6">
    
    <!-- Header -->
    <div class="px-3 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="flex items-center justify-between">
                <!-- Titre -->
                <div class="flex items-center space-x-4">
                    <h1 class="text-base sm:text-2xl lg:text-3xl font-semibold text-gray-900 dark:text-gray-100 tracking-tight">
                        Gestion des Ventes
                    </h1>
                </div>

                <!-- Actions -->
                <div class="flex items-center space-x-3">
                    <button wire:click="clearFilters" 
                            class="hidden sm:inline-flex px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Effacer filtres
                    </button>
                    
                    @if($activeTab === 'ventes')
                        <button wire:click="createVente" 
                                class="w-10 h-10 sm:w-auto sm:h-auto inline-flex items-center justify-center p-0 sm:px-4 sm:py-2.5 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white text-xs sm:text-sm font-medium rounded-full sm:rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all duration-150">
                            <!-- Icône mobile -->
                            <svg class="w-5 h-5 sm:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            <!-- Texte + icône desktop -->
                            <span class="hidden sm:inline-flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Nouvelle vente
                            </span>
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Contenu principal -->
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

            @if (session()->has('error'))
                <div class="mb-4 mx-3 sm:mx-0 bg-red-50 dark:bg-red-900/20 border-l-4 border-red-400 dark:border-red-600 p-4 rounded-r-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-red-500 dark:text-red-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        <p class="text-red-800 dark:text-red-200 text-sm font-medium">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <!-- Statistiques rapides -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center mr-3">
                            💰
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Total Ventes</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                {{ number_format($statistiques['totalVentes'] ?? 0, 0, ',', ' ') }} MGA
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-yellow-100 dark:bg-yellow-900 rounded-lg flex items-center justify-center mr-3">
                            ⏳
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Paiements partiels</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                {{ $statistiques['ventesPartielles'] ?? 0 }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center mr-3">
                            📊
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Nombre de ventes</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                {{ $statistiques['nombreVentes'] ?? 0 }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-red-100 dark:bg-red-900 rounded-lg flex items-center justify-center mr-3">
                            💸
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Montant restant</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                {{ number_format($statistiques['montantRestant'] ?? 0, 0, ',', ' ') }} MGA
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Container principal -->
            <div class="bg-white dark:bg-gray-800 shadow-sm mx-0 sm:mx-0 sm:rounded-xl overflow-hidden">
                
                <!-- Navigation onglets -->
                <div class="border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
                    <div class="overflow-x-auto scrollbar-hide">
                        <nav class="flex min-w-max sm:min-w-0">
                            <!-- Onglet ventes -->
                            <button wire:click="setActiveTab('ventes')"
                                    class="flex-shrink-0 px-3 sm:px-4 py-4 text-sm font-medium border-b-2 transition-all duration-150 {{ $activeTab === 'ventes' ? 'text-green-600 dark:text-green-400 border-green-500 dark:border-green-400' : 'text-gray-500 dark:text-gray-400 border-transparent hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600' }}">
                                <span class="flex items-center whitespace-nowrap">
                                    <span class="inline-flex items-center justify-center w-6 h-6 bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 rounded-full mr-2">
                                        💰
                                    </span>
                                    <span class="hidden sm:inline">Ventes</span>
                                    <span class="ml-1 text-xs">({{ $ventes->total() ?? 0 }})</span>
                                </span>
                            </button>

                            <!-- Onglet Rapports -->
                            <button wire:click="setActiveTab('rapports')"
                                    class="flex-shrink-0 px-3 sm:px-4 py-4 text-sm font-medium border-b-2 transition-all duration-150 {{ $activeTab === 'rapports' ? 'text-green-600 dark:text-green-400 border-green-500 dark:border-green-400' : 'text-gray-500 dark:text-gray-400 border-transparent hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600' }}">
                                <span class="flex items-center whitespace-nowrap">
                                    <span class="inline-flex items-center justify-center w-6 h-6 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 rounded-full mr-2">
                                        📊
                                    </span>
                                    <span class="hidden sm:inline">Rapports</span>
                                </span>
                            </button>
                        </nav>
                    </div>
                </div>

                <!-- Contenu des onglets -->
                <div class="p-3 sm:p-6">
                    @if($activeTab === 'ventes')
                        <div wire:key="tab-ventes">
                            @if($ventes->isEmpty())
                                <div class="text-center py-12 text-gray-500 dark:text-gray-400">
                                    <svg class="mx-auto h-16 w-16 text-gray-400 dark:text-gray-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                                    </svg>
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Aucune vente trouvée</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Commencez par créer votre première vente.</p>
                                    <button wire:click="createVente" 
                                            class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        Créer une vente
                                    </button>
                                </div>
                            @else
                                @include('livewire.finance.tabs.ventes')
                            @endif
                        </div>    
                    @elseif($activeTab === 'rapports')
                        <div wire:key="tab-rapports">
                            <div class="text-center py-12">
                                <svg class="mx-auto h-16 w-16 text-gray-400 dark:text-gray-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Rapports des ventes</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Les rapports et statistiques seront disponibles prochainement.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de vente -->
    @include('livewire.finance.modals.vente-modal')

    <!-- Wire Loading States -->
    <div wire:loading.flex wire:target="editVente,deleteVente,marquerPaye,createVente,saveVente" 
         class="fixed inset-0 bg-gray-500 bg-opacity-75 z-50 items-center justify-center">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-xl">
            <div class="flex items-center space-x-3">
                <svg class="animate-spin h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-gray-900 dark:text-gray-100 font-medium">Traitement en cours...</span>
            </div>
        </div>
    </div>
</div>