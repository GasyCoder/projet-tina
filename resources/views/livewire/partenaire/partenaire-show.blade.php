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
                        <button class="w-8 h-8 sm:w-auto sm:h-auto inline-flex items-center justify-center p-0 sm:px-3 sm:py-2 bg-red-600 dark:bg-red-700 text-white text-xs sm:text-sm font-medium rounded-lg shadow-sm hover:bg-red-700 dark:hover:bg-red-600 transition-all duration-150">
                            <svg class="w-4 h-4 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                            </svg>
                            <span class="hidden sm:inline">Nouvelle Sortie</span>
                        </button>
                        <button class="w-8 h-8 sm:w-auto sm:h-auto inline-flex items-center justify-center p-0 sm:px-3 sm:py-2 bg-blue-600 dark:bg-blue-700 text-white text-xs sm:text-sm font-medium rounded-lg shadow-sm hover:bg-blue-700 dark:hover:bg-blue-600 transition-all duration-150">
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
                                    <button 
                                        onclick="showDetailsModal('entrees', 500000, [
                                            {description: 'Vente produit A', montant: 300000, date: '15/01/2025'},
                                            {description: 'Vente produit B', montant: 200000, date: '10/01/2025'}
                                        ])"
                                        class="text-xl sm:text-2xl font-bold text-blue-900 dark:text-blue-100 mt-1 hover:underline cursor-pointer transition-all hover:scale-105"
                                    >
                                        500 000 Ar
                                    </button>
                                </div>
                                <div class="p-1.5 bg-blue-200 dark:bg-blue-700 rounded-lg">
                                    <svg class="w-4 h-4 text-blue-700 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                    </svg>
                                </div>
                            </div>
                            <p class="text-xs text-blue-500 dark:text-blue-400 mt-1 flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                </svg>
                                +12% vs mois dernier
                            </p>
                        </div>
                        
                        <div class="bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900/50 dark:to-red-800/30 p-3 rounded-lg border border-red-200 dark:border-red-700">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-xs font-semibold text-red-700 dark:text-red-300 uppercase tracking-wide">Total Sorties</p>
                                    <button 
                                        onclick="showDetailsModal('sorties', 300000, [
                                            {description: 'Achat corde', montant: 100000, date: '20/01/2025'},
                                            {description: 'Frais bajaj', montant: 50000, date: '18/01/2025'},
                                            {description: 'Frais transport', montant: 75000, date: '15/01/2025'},
                                            {description: 'Maintenance véhicule', montant: 75000, date: '12/01/2025'}
                                        ])"
                                        class="text-xl sm:text-2xl font-bold text-red-900 dark:text-red-100 mt-1 hover:underline cursor-pointer transition-all hover:scale-105"
                                    >
                                        300 000 Ar
                                    </button>
                                </div>
                                <div class="p-1.5 bg-red-200 dark:bg-red-700 rounded-lg">
                                    <svg class="w-4 h-4 text-red-700 dark:text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" />
                                    </svg>
                                </div>
                            </div>
                            <p class="text-xs text-red-500 dark:text-red-400 mt-1 flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" />
                                </svg>
                                +5% vs mois dernier
                            </p>
                        </div>
                        
                        <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/50 dark:to-green-800/30 p-3 rounded-lg border border-green-200 dark:border-green-700">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-xs font-semibold text-green-700 dark:text-green-300 uppercase tracking-wide">Solde Actuel</p>
                                    <button 
                                        onclick="showDetailsModal('solde', 200000, [
                                            {description: 'Solde disponible', montant: 150000, date: 'Aujourd\'hui'},
                                            {description: 'En attente validation', montant: 50000, date: 'En cours'}
                                        ])"
                                        class="text-xl sm:text-2xl font-bold text-green-900 dark:text-green-100 mt-1 hover:underline cursor-pointer transition-all hover:scale-105"
                                    >
                                        200 000 Ar
                                    </button>
                                </div>
                                <div class="p-1.5 bg-green-200 dark:bg-green-700 rounded-lg">
                                    <svg class="w-4 h-4 text-green-700 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                </div>
                            </div>
                            <p class="text-xs text-green-500 dark:text-green-400 mt-1 flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                </svg>
                                +7% vs mois dernier
                            </p>
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
                        <div class="col-span-3">Description</div>
                    </div>
                    
                    <!-- Liste des transactions -->
                    <div class="space-y-2">
                        @foreach($transactions as $transaction)
                            <div 
                                wire:click="showTransactionDetail({{ $transaction['id'] }})"
                                class="grid grid-cols-1 sm:grid-cols-12 gap-3 p-3 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 cursor-pointer transition-colors"
                            >
                                <!-- Version mobile -->
                                <div class="sm:hidden flex justify-between items-start">
                                    <div>
                                        <h3 class="font-medium text-gray-900 dark:text-white text-sm">{{ $transaction['reference'] }}</h3>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $transaction['date'] }}</p>
                                    </div>
                                    <span class="px-2 py-1 text-xs rounded-full {{ $transaction['type'] === 'entree' ? 'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-300' : 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-300' }}">
                                        {{ $transaction['type'] === 'entree' ? 'Entrée' : 'Sortie' }}
                                    </span>
                                </div>
                                
                                <!-- Version desktop -->
                                <div class="hidden sm:block col-span-3 font-medium text-gray-900 dark:text-white text-sm">
                                    {{ $transaction['reference'] }}
                                </div>
                                <div class="hidden sm:block col-span-2 text-gray-500 dark:text-gray-400 text-sm">
                                    {{ $transaction['date'] }}
                                </div>
                                <div class="hidden sm:block col-span-2">
                                    <span class="px-2 py-1 text-xs rounded-full {{ $transaction['type'] === 'entree' ? 'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-300' : 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-300' }}">
                                        {{ $transaction['type'] === 'entree' ? 'Entrée' : 'Sortie' }}
                                    </span>
                                </div>
                                <div class="hidden sm:block col-span-2 text-right font-medium text-sm {{ $transaction['type'] === 'entree' ? 'text-blue-600 dark:text-blue-400' : 'text-red-600 dark:text-red-400' }}">
                                    {{ number_format($transaction['montant'], 0, ',', ' ') }} Ar
                                </div>
                                <div class="hidden sm:block col-span-3 text-gray-500 dark:text-gray-400 truncate text-sm">
                                    {{ $transaction['description'] }}
                                </div>
                                
                                <!-- Montant pour mobile -->
                                <div class="sm:hidden flex justify-between items-center mt-2">
                                    <p class="text-xs text-gray-600 dark:text-gray-400 truncate">{{ $transaction['description'] }}</p>
                                    <p class="font-medium text-sm {{ $transaction['type'] === 'entree' ? 'text-blue-600 dark:text-blue-400' : 'text-red-600 dark:text-red-400' }}">
                                        {{ number_format($transaction['montant'], 0, ',', ' ') }} Ar
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- Pagination -->
                    <div class="mt-4 flex items-center justify-between">
                        <div class="text-xs text-gray-500 dark:text-gray-400">
                            Affichage de <span class="font-medium">1</span> à <span class="font-medium">5</span> sur <span class="font-medium">12</span> transactions
                        </div>
                        <div class="flex space-x-1">
                            <button class="px-3 py-1 border border-gray-300 dark:border-gray-600 rounded-md text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 disabled:opacity-50 text-xs" disabled>
                                Précédent
                            </button>
                            <button class="px-3 py-1 border border-gray-300 dark:border-gray-600 rounded-md text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 text-xs">
                                Suivant
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal pour les détails de dépenses -->
    <div id="detailsModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-md transform transition-all duration-300">
            <div class="p-4 sm:p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 id="modalTitle" class="text-lg font-bold text-gray-900 dark:text-white"></h3>
                        <p id="modalTotal" class="text-2xl font-bold mt-1"></p>
                    </div>
                    <button onclick="closeDetailsModal()" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <div id="modalContent" class="space-y-3 max-h-64 overflow-y-auto">
                    <!-- Le contenu sera généré dynamiquement -->
                </div>
                
                <div class="mt-6 flex justify-end">
                    <button onclick="closeDetailsModal()" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors text-sm">
                        Fermer
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal pour les détails de transaction -->
    @if($showTransactionModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-md transform transition-all duration-300">
                <div class="p-4 sm:p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $selectedTransaction['reference'] }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $selectedTransaction['date'] }}</p>
                        </div>
                        <button wire:click="closeTransactionModal" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    
                    <div class="space-y-4">
                        <!-- Montant -->
                        <div class="bg-gradient-to-r {{ $selectedTransaction['type'] === 'entree' ? 'from-blue-50 to-blue-100 dark:from-blue-900/50 dark:to-blue-800/30' : 'from-red-50 to-red-100 dark:from-red-900/50 dark:to-red-800/30' }} p-4 rounded-lg">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Montant</p>
                            <p class="text-xl font-bold {{ $selectedTransaction['type'] === 'entree' ? 'text-blue-600 dark:text-blue-400' : 'text-red-600 dark:text-red-400' }}">
                                {{ number_format($selectedTransaction['montant'], 0, ',', ' ') }} Ar
                            </p>
                        </div>
                        
                        <!-- Type et statut -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Type</p>
                                <p class="{{ $selectedTransaction['type'] === 'entree' ? 'text-blue-600 dark:text-blue-400' : 'text-red-600 dark:text-red-400' }}">
                                    {{ $selectedTransaction['type'] === 'entree' ? 'Entrée' : 'Sortie' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Statut</p>
                                <p class="text-green-600 dark:text-green-400">Comptabilisée</p>
                            </div>
                        </div>
                        
                        <!-- Description -->
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Description</p>
                            <p class="text-gray-800 dark:text-gray-200">{{ $selectedTransaction['description'] }}</p>
                        </div>
                        
                        @if($selectedTransaction['type'] === 'sortie')
                            <!-- Bénéficiaire -->
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Bénéficiaire</p>
                                <p class="text-gray-800 dark:text-gray-200">{{ $selectedTransaction['beneficiaire'] ?? 'Non spécifié' }}</p>
                            </div>
                            
                            <!-- Justificatif -->
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Justificatif</p>
                                <div class="mt-1">
                                    @if(isset($selectedTransaction['justificatif']))
                                        <a href="#" class="inline-flex items-center text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                            </svg>
                                            {{ $selectedTransaction['justificatif'] }}
                                        </a>
                                    @else
                                        <p class="text-gray-500 dark:text-gray-400">Aucun justificatif</p>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    <div class="mt-6 flex justify-end space-x-3">
                        <button wire:click="closeTransactionModal" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors text-sm">
                            Fermer
                        </button>
                        @if($selectedTransaction['type'] === 'sortie')
                            <button wire:click="editSortie({{ $selectedTransaction['id'] }})" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors flex items-center text-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Modifier
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    <script>
        // Exemples de données pour les transactions
        const transactions = [
            {id: 1, reference: 'T001', date: '25/01/2025', type: 'entree', montant: 150000, description: 'Vente produit A'},
            {id: 2, reference: 'T002', date: '24/01/2025', type: 'sortie', montant: 75000, description: 'Achat matériel'},
            {id: 3, reference: 'T003', date: '23/01/2025', type: 'entree', montant: 200000, description: 'Paiement client'},
            {id: 4, reference: 'T004', date: '22/01/2025', type: 'sortie', montant: 50000, description: 'Frais transport'},
            {id: 5, reference: 'T005', date: '21/01/2025', type: 'entree', montant: 100000, description: 'Vente produit B'}
        ];

        function showDetailsModal(type, total, details) {
            const modal = document.getElementById('detailsModal');
            const modalTitle = document.getElementById('modalTitle');
            const modalTotal = document.getElementById('modalTotal');
            const modalContent = document.getElementById('modalContent');
            
            // Configuration du titre et couleur
            let titleText = '';
            let colorClass = '';
            
            switch(type) {
                case 'entrees':
                    titleText = 'Détails des Entrées';
                    colorClass = 'text-blue-600 dark:text-blue-400';
                    break;
                case 'sorties':
                    titleText = 'Détails des Sorties';
                    colorClass = 'text-red-600 dark:text-red-400';
                    break;
                case 'solde':
                    titleText = 'Détails du Solde';
                    colorClass = 'text-green-600 dark:text-green-400';
                    break;
            }
            
            modalTitle.textContent = titleText;
            modalTotal.textContent = new Intl.NumberFormat('fr-FR').format(total) + ' Ar';
            modalTotal.className = `text-2xl font-bold mt-1 ${colorClass}`;
            
            // Génération du contenu
            modalContent.innerHTML = '';
            details.forEach(item => {
                const div = document.createElement('div');
                div.className = 'flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600';
                div.innerHTML = `
                    <div>
                        <p class="font-medium text-gray-900 dark:text-white text-sm">${item.description}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">${item.date}</p>
                    </div>
                    <p class="font-bold ${colorClass} text-sm">${new Intl.NumberFormat('fr-FR').format(item.montant)} Ar</p>
                `;
                modalContent.appendChild(div);
            });
            
            modal.classList.remove('hidden');
        }

        function closeDetailsModal() {
            document.getElementById('detailsModal').classList.add('hidden');
        }

        // Fermer le modal en cliquant en dehors
        document.getElementById('detailsModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDetailsModal();
            }
</script>
</div>script>
</div>