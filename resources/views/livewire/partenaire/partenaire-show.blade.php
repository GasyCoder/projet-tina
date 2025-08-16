<div>
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900 pt-20 pb-6 md:px-6">
        <!-- Header Mobile/Desktop -->
        <div class="px-3 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                <div class="flex flex-row items-center justify-between space-x-3 sm:flex-row sm:items-center sm:justify-between sm:space-x-0">
                    <!-- Titre et info partenaire -->
                    <div class="flex items-center space-x-4">
                        <button class="w-8 h-8 inline-flex items-center justify-center rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>
                        <div>
                            <h1 class="text-base sm:text-xl lg:text-2xl font-semibold text-gray-900 dark:text-gray-100 tracking-tight">
                                {{ $partenaire->nom }}
                            </h1>
                            <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 flex items-center">
                                <span class="w-2 h-2 mr-2 rounded-full {{ $partenaire->is_active ? 'bg-green-400' : 'bg-red-400' }}"></span>
                                {{ ucfirst($partenaire->type) }} - {{ $partenaire->is_active ? 'Actif' : 'Inactif' }}
                            </p>
                        </div>
                    </div>

                    <!-- Boutons d'action -->
                    <div class="flex justify-end space-x-2">
                        <button wire:click="openNewSortieModal" class="w-8 h-8 sm:w-auto sm:h-auto inline-flex items-center justify-center p-0 sm:px-3 sm:py-2 bg-red-600 dark:bg-red-700 text-white text-xs sm:text-sm font-medium rounded-lg shadow-sm hover:bg-red-700 dark:hover:bg-red-600 transition-all duration-150">
                            <svg class="w-4 h-4 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                            </svg>
                            <span class="hidden sm:inline">Nouvelle Sortie</span>
                        </button>
                        <button wire:click="openNewEntreeModal" class="w-8 h-8 sm:w-auto sm:h-auto inline-flex items-center justify-center p-0 sm:px-3 sm:py-2 bg-blue-600 dark:bg-blue-700 text-white text-xs sm:text-sm font-medium rounded-lg shadow-sm hover:bg-blue-700 dark:hover:bg-blue-600 transition-all duration-150">
                            <svg class="w-4 h-4 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            <span class="hidden sm:inline">Nouvelle Entrée</span>
                        </button>
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

                <!-- Container Principal -->
                <div class="bg-white dark:bg-gray-800 shadow-sm mx-0 sm:mx-0 sm:rounded-xl overflow-hidden">
                    
                    <!-- Section informations partenaire -->
                    <div class="p-3 sm:p-6 border-b border-gray-200 dark:border-gray-700">
                        <!-- Informations de contact (grid) -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-4">
                            <div class="bg-gray-50 dark:bg-gray-900 p-3 rounded-lg border border-gray-200 dark:border-gray-700">
                                <div class="flex items-center space-x-2">
                                    <div class="p-1.5 rounded-lg bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Téléphone</p>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $partenaire->telephone ?? 'Non renseigné' }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-gray-50 dark:bg-gray-900 p-3 rounded-lg border border-gray-200 dark:border-gray-700">
                                <div class="flex items-center space-x-2">
                                    <div class="p-1.5 rounded-lg bg-purple-100 dark:bg-purple-900 text-purple-600 dark:text-purple-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Adresse</p>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ Str::limit($partenaire->adresse ?? 'Non renseigné', 20) }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-gray-50 dark:bg-gray-900 p-3 rounded-lg border border-gray-200 dark:border-gray-700">
                                <div class="flex items-center space-x-2">
                                    <div class="p-1.5 rounded-lg bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Créé le</p>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $partenaire->created_at->format('d/m/Y') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Alerte si nécessaire -->
                        @if($partenaire->has_unpaid_debt)
                        <div class="bg-yellow-50 dark:bg-yellow-900/20 border-l-4 border-yellow-400 dark:border-yellow-600 p-3 rounded-r-lg">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-yellow-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                <p class="text-xs text-yellow-700 dark:text-yellow-200">
                                    Ce partenaire a un solde impayé depuis plus de 30 jours.
                                </p>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Statistiques financières -->
                    <div class="p-3 sm:p-6 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-3">
                            <h2 class="text-sm sm:text-base font-semibold text-gray-900 dark:text-white flex items-center">
                                <svg class="w-4 h-4 mr-2 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z" />
                                    <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd" />
                                </svg>
                                Compte Global
                            </h2>
                            <div class="flex items-center space-x-1">
                                <button class="text-xs bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-600 dark:text-gray-300 px-2 py-1 rounded-md flex items-center transition-colors">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Ce mois
                                </button>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/50 dark:to-blue-800/30 p-3 rounded-lg border border-blue-200 dark:border-blue-700">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="text-xs font-semibold text-blue-700 dark:text-blue-300 uppercase tracking-wide">Total Entrées</p>
                                        <p class="text-xl sm:text-2xl font-bold text-blue-900 dark:text-blue-100 mt-1">
                                            {{ number_format($statistiques['total_entrees'], 0, ',', ' ') }} Ar
                                        </p>
                                    </div>
                                    <div class="p-1.5 bg-blue-200 dark:bg-blue-700 rounded-lg">
                                        <svg class="w-4 h-4 text-blue-700 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900/50 dark:to-red-800/30 p-3 rounded-lg border border-red-200 dark:border-red-700">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="text-xs font-semibold text-red-700 dark:text-red-300 uppercase tracking-wide">Total Sorties</p>
                                        <p class="text-xl sm:text-2xl font-bold text-red-900 dark:text-red-100 mt-1">
                                            {{ number_format($statistiques['total_sorties'], 0, ',', ' ') }} Ar
                                        </p>
                                    </div>
                                    <div class="p-1.5 bg-red-200 dark:bg-red-700 rounded-lg">
                                        <svg class="w-4 h-4 text-red-700 dark:text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/50 dark:to-green-800/30 p-3 rounded-lg border border-green-200 dark:border-green-700">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="text-xs font-semibold text-green-700 dark:text-green-300 uppercase tracking-wide">Solde Actuel</p>
                                        <p class="text-xl sm:text-2xl font-bold text-green-900 dark:text-green-100 mt-1">
                                            {{ number_format($statistiques['solde_actuel'], 0, ',', ' ') }} Ar
                                        </p>
                                    </div>
                                    <div class="p-1.5 bg-green-200 dark:bg-green-700 rounded-lg">
                                        <svg class="w-4 h-4 text-green-700 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Historique des transactions -->
                    <div class="p-3 sm:p-6">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4">
                            <h2 class="text-sm sm:text-base font-semibold text-gray-900 dark:text-white flex items-center mb-3 sm:mb-0">
                                <svg class="w-4 h-4 mr-2 text-gray-600 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 2a8 8 0 100 16 8 8 0 000-16zm0 14a6 6 0 110-12 6 6 0 010 12z" clip-rule="evenodd" />
                                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                                </svg>
                                Historique des Transactions
                            </h2>
                            
                            <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
                                <div class="relative">
                                    <input type="text" placeholder="Rechercher..." class="pl-8 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm w-full sm:w-auto">
                                    <div class="absolute left-2.5 top-2.5 text-gray-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </div>
                                </div>
                                
                                <div class="flex bg-gray-100 dark:bg-gray-700 p-1 rounded-lg">
                                    <button wire:click="filterTransactions('all')" class="px-2 py-1 text-xs rounded-md {{ $filter === 'all' ? 'bg-white dark:bg-gray-600 shadow-sm text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-gray-100' }}">
                                        Toutes
                                    </button>
                                    <button wire:click="filterTransactions('entree')" class="px-2 py-1 text-xs rounded-md {{ $filter === 'entree' ? 'bg-white dark:bg-gray-600 shadow-sm text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-gray-100' }}">
                                        Entrées
                                    </button>
                                    <button wire:click="filterTransactions('sortie')" class="px-2 py-1 text-xs rounded-md {{ $filter === 'sortie' ? 'bg-white dark:bg-gray-600 shadow-sm text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-gray-100' }}">
                                        Sorties
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- En-tête du tableau (desktop) -->
                        <div class="hidden sm:grid grid-cols-12 gap-3 mb-3 px-3 py-2 bg-gray-50 dark:bg-gray-900 rounded-lg text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            <div class="col-span-3">Référence</div>
                            <div class="col-span-2">Date</div>
                            <div class="col-span-2">Type</div>
                            <div class="col-span-2 text-right">Montant</div>
                            <div class="col-span-3">Motif</div>
                        </div>
                        
                        <!-- Liste des transactions -->
                        <div class="space-y-2">
                            @forelse($transactions as $transaction)
                                <div 
                                    wire:click="showTransactionDetail({{ $transaction->id }})"
                                    class="grid grid-cols-1 sm:grid-cols-12 gap-3 p-3 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 cursor-pointer transition-colors"
                                >
                                    <!-- Version mobile -->
                                    <div class="sm:hidden flex justify-between items-start">
                                        <div>
                                            <h3 class="font-medium text-gray-900 dark:text-white text-sm">{{ $transaction->reference }}</h3>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $transaction->date_transaction->format('d/m/Y H:i') }}</p>
                                        </div>
                                        <span class="px-2 py-1 text-xs rounded-full {{ $transaction->type === 'entree' ? 'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-300' : 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-300' }}">
                                            {{ $transaction->type_libelle }}
                                        </span>
                                    </div>
                                    
                                    <!-- Version desktop -->
                                    <div class="hidden sm:block col-span-3 font-medium text-gray-900 dark:text-white text-sm">
                                        {{ $transaction->reference }}
                                    </div>
                                    <div class="hidden sm:block col-span-2 text-gray-500 dark:text-gray-400 text-sm">
                                        {{ $transaction->date_transaction->format('d/m/Y H:i') }}
                                    </div>
                                    <div class="hidden sm:block col-span-2">
                                        <span class="px-2 py-1 text-xs rounded-full {{ $transaction->type === 'entree' ? 'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-300' : 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-300' }}">
                                            {{ $transaction->type_libelle }}
                                        </span>
                                    </div>
                                    <div class="hidden sm:block col-span-2 text-right font-medium text-sm {{ $transaction->type === 'entree' ? 'text-blue-600 dark:text-blue-400' : 'text-red-600 dark:text-red-400' }}">
                                        {{ $transaction->montant_formatted }}
                                    </div>
                                    <div class="hidden sm:block col-span-3 text-gray-500 dark:text-gray-400 truncate text-sm">
                                        {{ $transaction->motif }}
                                    </div>
                                    
                                    <!-- Montant pour mobile -->
                                    <div class="sm:hidden flex justify-between items-center mt-2">
                                        <p class="text-xs text-gray-600 dark:text-gray-400 truncate">{{ $transaction->motif }}</p>
                                        <p class="font-medium text-sm {{ $transaction->type === 'entree' ? 'text-blue-600 dark:text-blue-400' : 'text-red-600 dark:text-red-400' }}">
                                            {{ $transaction->montant_formatted }}
                                        </p>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8">
                                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p class="text-gray-500 dark:text-gray-400">Aucune transaction trouvée</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Nouvelle Entrée -->
        @if($showNewEntreeModal)
            <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-md transform transition-all duration-300">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-6">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Nouvelle Entrée</h3>
                            <button wire:click="closeNewEntreeModal" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        
                        <form wire:submit="creerEntree" class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Montant (Ar)</label>
                                <input type="number" wire:model="entreeForm.montant_mga" step="0.01" 
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                       placeholder="Ex: 50000">
                                @error('entreeForm.montant_mga') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Motif</label>
                                <input type="text" wire:model="entreeForm.motif" 
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                       placeholder="Ex: Transfert d'argent">
                                @error('entreeForm.motif') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Mode de Paiement</label>
                                <select wire:model="entreeForm.mode_paiement" 
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                    <option value="especes">Espèces</option>
                                    <option value="AirtelMoney">AirtelMoney</option>
                                    <option value="OrangeMoney">OrangeMoney</option>
                                    <option value="Mvola">Mvola</option>
                                    <option value="banque">Banque</option>
                                </select>
                                @error('entreeForm.mode_paiement') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Compte Source (Mme Tina)</label>
                                <select wire:model="entreeForm.compte_source_id" 
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                    <option value="">Sélectionner un compte</option>
                                    @foreach($comptes as $compte)
                                        <option value="{{ $compte->id }}">{{ $compte->nom }} ({{ $compte->solde_formatted }})</option>
                                    @endforeach
                                </select>
                                @error('entreeForm.compte_source_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Observation (optionnel)</label>
                                <textarea wire:model="entreeForm.observation" 
                                          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                          rows="3" placeholder="Remarques supplémentaires..."></textarea>
                            </div>

                            <div class="flex justify-end space-x-3 pt-4">
                                <button type="button" wire:click="closeNewEntreeModal" 
                                        class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    Annuler
                                </button>
                                <button type="submit" 
                                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Créer l'Entrée
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif

        <!-- Modal Nouvelle Sortie -->
        @if($showNewSortieModal)
            <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-2xl transform transition-all duration-300 max-h-screen overflow-y-auto">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-6">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Nouvelle Sortie</h3>
                            <button wire:click="closeNewSortieModal" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        
                        <form wire:submit="creerSortie" class="space-y-6">
                            <!-- Informations générales -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Motif</label>
                                    <input type="text" wire:model="sortieForm.motif" 
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                           placeholder="Ex: Achat de produits">
                                    @error('sortieForm.motif') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Montant Total (Ar)</label>
                                    <input type="number" wire:model="sortieForm.montant_total" step="0.01" readonly
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-100 dark:bg-gray-600 text-gray-900 dark:text-white">
                                    @error('sortieForm.montant_total') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Observation (optionnel)</label>
                                <textarea wire:model="sortieForm.observation" 
                                          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                          rows="2" placeholder="Remarques supplémentaires..."></textarea>
                            </div>

                            <!-- Ajout de détail -->
                            <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                                <h4 class="text-md font-semibold text-gray-900 dark:text-white mb-4">Ajouter un détail</h4>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Type</label>
                                        <select wire:model="newDetail.type_detail" 
                                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                            <option value="achat_produit">Achat Produit</option>
                                            {{-- <option value="credit">Crédit</option>
                                            <option value="frais">Frais</option> --}}
                                            <option value="autre">Autre</option>
                                        </select>
                                    </div>

                                    @if($newDetail['type_detail'] === 'achat_produit')
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Produit</label>
                                            <select wire:model="newDetail.produit_id" 
                                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                                <option value="">Sélectionner un produit</option>
                                                @foreach($produits as $produit)
                                                    <option value="{{ $produit->id }}">{{ $produit->nom_complet }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description</label>
                                        <input type="text" wire:model="newDetail.description" 
                                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                               placeholder="Description du détail">
                                    </div>

                                    @if($newDetail['type_detail'] === 'achat_produit')
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Quantité</label>
                                            <input type="number" wire:model="newDetail.quantite" wire:change="calculerMontantDetail" step="0.01"
                                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                                   placeholder="0">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Prix Unitaire (Ar)</label>
                                            <input type="number" wire:model="newDetail.prix_unitaire_mga" wire:change="calculerMontantDetail" step="0.01"
                                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                                   placeholder="0">
                                        </div>
                                    @endif
                                </div>

                                <div class="mt-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Montant (Ar)</label>
                                    <div class="flex space-x-2">
                                        <input type="number" wire:model="newDetail.montant_mga" step="0.01"
                                               class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                               placeholder="0">
                                        <button type="button" wire:click="ajouterDetail" 
                                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                            Ajouter
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Liste des détails ajoutés -->
                            @if(!empty($sortieDetails))
                                <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                                    <h4 class="text-md font-semibold text-gray-900 dark:text-white mb-4">Détails ajoutés</h4>
                                    
                                    <div class="space-y-2">
                                        @foreach($sortieDetails as $index => $detail)
                                            <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                                <div class="flex-1">
                                                    <div class="flex items-center space-x-2">
                                                        <span class="px-2 py-1 text-xs rounded-full bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-300">
                                                            {{ ucfirst(str_replace('_', ' ', $detail['type_detail'])) }}
                                                        </span>
                                                        <span class="font-medium text-gray-900 dark:text-white">{{ $detail['description'] }}</span>
                                                    </div>
                                                    @if($detail['quantite'] > 0)
                                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                            Qté: {{ $detail['quantite'] }} × {{ number_format($detail['prix_unitaire_mga'], 0, ',', ' ') }} Ar
                                                        </p>
                                                    @endif
                                                </div>
                                                <div class="flex items-center space-x-2">
                                                    <span class="font-bold text-gray-900 dark:text-white">
                                                        {{ number_format($detail['montant_mga'], 0, ',', ' ') }} Ar
                                                    </span>
                                                    <button type="button" wire:click="supprimerDetail({{ $index }})" 
                                                            class="text-red-500 hover:text-red-700 transition-colors">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                                <button type="button" wire:click="closeNewSortieModal" 
                                        class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    Annuler
                                </button>
                                <button type="submit" 
                                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                    </svg>
                                    Créer la Sortie
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif

        <!-- Modal Détails Transaction -->
        @if($showTransactionDetailModal && $selectedTransaction)
            <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-2xl transform transition-all duration-300 max-h-screen overflow-y-auto">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-6">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $selectedTransaction->reference }}</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $selectedTransaction->date_transaction->format('d/m/Y H:i') }}</p>
                            </div>
                            <button wire:click="closeTransactionDetailModal" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        
                        <div class="space-y-6">
                            <!-- Informations générales -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="bg-gradient-to-r {{ $selectedTransaction->type === 'entree' ? 'from-blue-50 to-blue-100 dark:from-blue-900/50 dark:to-blue-800/30' : 'from-red-50 to-red-100 dark:from-red-900/50 dark:to-red-800/30' }} p-4 rounded-lg">
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Montant</p>
                                    <p class="text-xl font-bold {{ $selectedTransaction->type === 'entree' ? 'text-blue-600 dark:text-blue-400' : 'text-red-600 dark:text-red-400' }}">
                                        {{ $selectedTransaction->montant_formatted }}
                                    </p>
                                </div>
                                
                                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Type</p>
                                    <p class="text-lg font-semibold {{ $selectedTransaction->type === 'entree' ? 'text-blue-600 dark:text-blue-400' : 'text-red-600 dark:text-red-400' }}">
                                        {{ $selectedTransaction->type_libelle }}
                                    </p>
                                </div>
                            </div>

                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Motif</p>
                                <p class="text-gray-800 dark:text-gray-200">{{ $selectedTransaction->motif }}</p>
                            </div>

                            @if($selectedTransaction->type === 'entree')
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Mode de Paiement</p>
                                        <p class="text-gray-800 dark:text-gray-200">{{ $selectedTransaction->mode_paiement_libelle }}</p>
                                    </div>
                                    @if($selectedTransaction->compteSource)
                                        <div>
                                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Compte Source</p>
                                            <p class="text-gray-800 dark:text-gray-200">{{ $selectedTransaction->compteSource->nom }}</p>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            @if($selectedTransaction->observation)
                                <div>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Observation</p>
                                    <p class="text-gray-800 dark:text-gray-200">{{ $selectedTransaction->observation }}</p>
                                </div>
                            @endif

                            <!-- Détails pour les sorties -->
                            @if($selectedTransaction->type === 'sortie' && !empty($transactionDetails))
                                <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                                    <h4 class="text-md font-semibold text-gray-900 dark:text-white mb-4">Détails de la sortie</h4>
                                    
                                    <div class="space-y-3">
                                        @foreach($transactionDetails as $detail)
                                            <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                                <div class="flex-1">
                                                    <div class="flex items-center space-x-2">
                                                        <span class="px-2 py-1 text-xs rounded-full bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-300">
                                                            {{ ucfirst(str_replace('_', ' ', $detail['type_detail'])) }}
                                                        </span>
                                                        <span class="font-medium text-gray-900 dark:text-white">{{ $detail['description'] }}</span>
                                                    </div>
                                                    @if($detail['quantite'] > 0)
                                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                            Qté: {{ number_format($detail['quantite'], 2, ',', ' ') }} × {{ number_format($detail['prix_unitaire_mga'], 0, ',', ' ') }} Ar
                                                        </p>
                                                    @endif
                                                    @if(isset($detail['produit']) && $detail['produit'])
                                                        <p class="text-xs text-blue-600 dark:text-blue-400 mt-1">
                                                            Produit: {{ $detail['produit']['nom'] }}
                                                        </p>
                                                    @endif
                                                </div>
                                                <div class="text-right">
                                                    <span class="font-bold text-gray-900 dark:text-white">
                                                        {{ number_format($detail['montant_mga'], 0, ',', ' ') }} Ar
                                                    </span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    @if($selectedTransaction->solde_restant > 0)
                                        <div class="mt-4 p-3 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700 rounded-lg">
                                            <div class="flex justify-between items-center">
                                                <span class="text-sm font-medium text-yellow-800 dark:text-yellow-200">Solde restant</span>
                                                <span class="font-bold text-yellow-900 dark:text-yellow-100">
                                                    {{ number_format($selectedTransaction->solde_restant, 0, ',', ' ') }} Ar
                                                </span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                        
                        <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <button wire:click="closeTransactionDetailModal" 
                                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                Fermer
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>