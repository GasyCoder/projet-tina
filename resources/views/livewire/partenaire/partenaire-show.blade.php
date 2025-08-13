<div>
    <!-- Section d'information sur le partenaire - Version améliorée -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6 border border-gray-100">
        <div class="flex justify-between items-start mb-4">
            <div>
                <div class="flex items-center space-x-3">
                    <h1 class="text-2xl font-bold text-gray-900">{{ $partenaire->nom }}</h1>
                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $partenaire->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $partenaire->is_active ? 'Actif' : 'Inactif' }}
                    </span>
                </div>
                <p class="text-gray-600 mt-1 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    {{ ucfirst($partenaire->type) }}
                </p>
            </div>
            <div class="flex space-x-2">
                <button class="p-2 rounded-lg hover:bg-gray-100 text-gray-500 hover:text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </button>
                <button class="p-2 rounded-lg hover:bg-gray-100 text-gray-500 hover:text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                    </svg>
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 hover:border-blue-200 transition-colors">
                <div class="flex items-center space-x-3">
                    <div class="p-2 rounded-full bg-blue-50 text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-gray-500 text-xs font-medium uppercase tracking-wider">Téléphone</h3>
                        <p class="text-gray-900 font-medium">{{ $partenaire->telephone ?? 'Non renseigné' }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 hover:border-blue-200 transition-colors">
                <div class="flex items-center space-x-3">
                    <div class="p-2 rounded-full bg-purple-50 text-purple-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-gray-500 text-xs font-medium uppercase tracking-wider">Adresse</h3>
                        <p class="text-gray-900 font-medium">{{ $partenaire->adresse ?? 'Non renseigné' }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 hover:border-blue-200 transition-colors">
                <div class="flex items-center space-x-3">
                    <div class="p-2 rounded-full bg-green-50 text-green-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-gray-500 text-xs font-medium uppercase tracking-wider">Date création</h3>
                        <p class="text-gray-900 font-medium">{{ $partenaire->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Notes/commentaires -->
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-r-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700">
                        Ce partenaire a un solde impayé depuis plus de 30 jours. Veuillez suivre avec le service comptabilité.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Compte global du partenaire - Version améliorée -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6 border border-gray-100">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z" />
                    <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd" />
                </svg>
                Compte Global
            </h2>
            <div class="flex items-center space-x-2">
                <button class="text-xs bg-gray-100 hover:bg-gray-200 text-gray-600 px-3 py-1 rounded-full flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Ce mois
                </button>
                <button class="text-xs bg-gray-100 hover:bg-gray-200 text-gray-600 px-3 py-1 rounded-full flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Filtrer
                </button>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-4 rounded-xl border border-blue-200">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs font-medium text-blue-600 uppercase tracking-wider">Total Entrées</p>
                        <p class="text-2xl font-bold text-blue-900 mt-1">500 000 Ar</p>
                    </div>
                    <div class="p-2 rounded-lg bg-blue-100 text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-blue-500 mt-2 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                    +12% vs mois dernier
                </p>
            </div>
            
            <div class="bg-gradient-to-br from-red-50 to-red-100 p-4 rounded-xl border border-red-200">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs font-medium text-red-600 uppercase tracking-wider">Total Sorties</p>
                        <p class="text-2xl font-bold text-red-900 mt-1">300 000 Ar</p>
                    </div>
                    <div class="p-2 rounded-lg bg-red-100 text-red-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" />
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-red-500 mt-2 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" />
                    </svg>
                    +5% vs mois dernier
                </p>
            </div>
            
            <div class="bg-gradient-to-br from-green-50 to-green-100 p-4 rounded-xl border border-green-200">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs font-medium text-green-600 uppercase tracking-wider">Solde Actuel</p>
                        <p class="text-2xl font-bold text-green-900 mt-1">200 000 Ar</p>
                    </div>
                    <div class="p-2 rounded-lg bg-green-100 text-green-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-green-500 mt-2 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                    +7% vs mois dernier
                </p>
            </div>
        </div>
        
        <!-- Boutons d'action -->
        <div class="flex space-x-3 justify-end">
            <button wire:click="addSortie" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg flex items-center shadow-sm hover:shadow-md transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Nouvelle Sortie
            </button>
            <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center shadow-sm hover:shadow-md transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Nouvelle Entrée
            </button>
        </div>
    </div>

    <!-- Liste des transactions - Version améliorée -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <h2 class="text-xl font-semibold text-gray-900 flex items-center mb-4 md:mb-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-600" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 2a8 8 0 100 16 8 8 0 000-16zm0 14a6 6 0 110-12 6 6 0 010 12z" clip-rule="evenodd" />
                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Historique des Transactions
            </h2>
            
            <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
                <div class="relative">
                    <input type="text" placeholder="Rechercher..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-full">
                    <div class="absolute left-3 top-2.5 text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>
                
                <div class="flex bg-gray-100 p-1 rounded-lg">
                    <button wire:click="filterTransactions('all')" class="px-3 py-1 text-sm rounded-md {{ $filter === 'all' ? 'bg-white shadow-sm text-blue-600' : 'text-gray-600 hover:text-gray-800' }}">
                        Toutes
                    </button>
                    <button wire:click="filterTransactions('entree')" class="px-3 py-1 text-sm rounded-md {{ $filter === 'entree' ? 'bg-white shadow-sm text-blue-600' : 'text-gray-600 hover:text-gray-800' }}">
                        Entrées
                    </button>
                    <button wire:click="filterTransactions('sortie')" class="px-3 py-1 text-sm rounded-md {{ $filter === 'sortie' ? 'bg-white shadow-sm text-blue-600' : 'text-gray-600 hover:text-gray-800' }}">
                        Sorties
                    </button>
                </div>
            </div>
        </div>
        
        <!-- En-tête du tableau -->
        <div class="hidden md:grid grid-cols-12 gap-4 mb-4 px-4 py-2 bg-gray-50 rounded-lg text-sm font-medium text-gray-500 uppercase tracking-wider">
            <div class="col-span-3">Référence</div>
            <div class="col-span-2">Date</div>
            <div class="col-span-2">Type</div>
            <div class="col-span-2 text-right">Montant</div>
            <div class="col-span-3">Description</div>
        </div>
        
        <!-- Liste des transactions - Version tableau -->
        <div class="space-y-2">
            @foreach($transactions as $transaction)
                <div 
                    wire:click="showTransactionDetail({{ $transaction['id'] }})"
                    class="grid grid-cols-1 md:grid-cols-12 gap-4 p-4 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors"
                >
                    <!-- Version mobile -->
                    <div class="md:hidden flex justify-between items-start">
                        <div>
                            <h3 class="font-medium text-gray-900">{{ $transaction['reference'] }}</h3>
                            <p class="text-sm text-gray-500">{{ $transaction['date'] }}</p>
                        </div>
                        <span class="px-2 py-1 text-xs rounded-full {{ $transaction['type'] === 'entree' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800' }}">
                            {{ $transaction['type'] === 'entree' ? 'Entrée' : 'Sortie' }}
                        </span>
                    </div>
                    
                    <!-- Version desktop -->
                    <div class="hidden md:block col-span-3 font-medium text-gray-900">
                        {{ $transaction['reference'] }}
                    </div>
                    <div class="hidden md:block col-span-2 text-gray-500">
                        {{ $transaction['date'] }}
                    </div>
                    <div class="hidden md:block col-span-2">
                        <span class="px-2 py-1 text-xs rounded-full {{ $transaction['type'] === 'entree' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800' }}">
                            {{ $transaction['type'] === 'entree' ? 'Entrée' : 'Sortie' }}
                        </span>
                    </div>
                    <div class="hidden md:block col-span-2 text-right font-medium {{ $transaction['type'] === 'entree' ? 'text-blue-600' : 'text-red-600' }}">
                        {{ number_format($transaction['montant'], 0, ',', ' ') }} Ar
                    </div>
                    <div class="hidden md:block col-span-3 text-gray-500 truncate">
                        {{ $transaction['description'] }}
                    </div>
                    
                    <!-- Montant pour mobile -->
                    <div class="md:hidden flex justify-between items-center mt-2">
                        <p class="text-sm text-gray-600 truncate">{{ $transaction['description'] }}</p>
                        <p class="font-medium {{ $transaction['type'] === 'entree' ? 'text-blue-600' : 'text-red-600' }}">
                            {{ number_format($transaction['montant'], 0, ',', ' ') }} Ar
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        <div class="mt-6 flex items-center justify-between">
            <div class="text-sm text-gray-500">
                Affichage de <span class="font-medium">1</span> à <span class="font-medium">5</span> sur <span class="font-medium">12</span> transactions
            </div>
            <div class="flex space-x-2">
                <button class="px-3 py-1 border border-gray-300 rounded-md text-gray-500 hover:bg-gray-50 disabled:opacity-50" disabled>
                    Précédent
                </button>
                <button class="px-3 py-1 border border-gray-300 rounded-md text-gray-500 hover:bg-gray-50">
                    Suivant
                </button>
            </div>
        </div>
    </div>

    <!-- Modal pour les détails de transaction - Version améliorée -->
    @if($showTransactionModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 transition-opacity duration-300">
            <div 
                class="bg-white rounded-xl shadow-xl w-full max-w-md transform transition-all duration-300"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
            >
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">{{ $selectedTransaction['reference'] }}</h3>
                            <p class="text-sm text-gray-500">{{ $selectedTransaction['date'] }}</p>
                        </div>
                        <button 
                            wire:click="closeTransactionModal" 
                            class="text-gray-400 hover:text-gray-500 transition-colors"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    
                    <div class="space-y-4">
                        <!-- Montant -->
                        <div class="bg-gradient-to-r {{ $selectedTransaction['type'] === 'entree' ? 'from-blue-50 to-blue-100' : 'from-red-50 to-red-100' }} p-4 rounded-lg">
                            <p class="text-sm font-medium text-gray-500">Montant</p>
                            <p class="text-2xl font-bold {{ $selectedTransaction['type'] === 'entree' ? 'text-blue-600' : 'text-red-600' }}">
                                {{ number_format($selectedTransaction['montant'], 0, ',', ' ') }} Ar
                            </p>
                        </div>
                        
                        <!-- Type -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Type</p>
                                <p class="{{ $selectedTransaction['type'] === 'entree' ? 'text-blue-600' : 'text-red-600' }}">
                                    {{ $selectedTransaction['type'] === 'entree' ? 'Entrée' : 'Sortie' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Statut</p>
                                <p class="text-green-600">Comptabilisée</p>
                            </div>
                        </div>
                        
                        <!-- Description -->
                        <div>
                            <p class="text-sm font-medium text-gray-500">Description</p>
                            <p class="text-gray-800">{{ $selectedTransaction['description'] }}</p>
                        </div>
                        
                        @if($selectedTransaction['type'] === 'sortie')
                            <!-- Bénéficiaire -->
                            <div>
                                <p class="text-sm font-medium text-gray-500">Bénéficiaire</p>
                                <p class="text-gray-800">{{ $selectedTransaction['beneficiaire'] ?? 'Non spécifié' }}</p>
                            </div>
                            
                            <!-- Justificatif -->
                            <div>
                                <p class="text-sm font-medium text-gray-500">Justificatif</p>
                                <div class="mt-1">
                                    @if(isset($selectedTransaction['justificatif']))
                                        <a href="#" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                            </svg>
                                            {{ $selectedTransaction['justificatif'] }}
                                        </a>
                                    @else
                                        <p class="text-gray-500">Aucun justificatif</p>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    <div class="mt-6 flex justify-end space-x-3">
                        <button 
                            wire:click="closeTransactionModal" 
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors"
                        >
                            Fermer
                        </button>
                        @if($selectedTransaction['type'] === 'sortie')
                            <button 
                                wire:click="editSortie({{ $selectedTransaction['id'] }})" 
                                class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors flex items-center"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
</div>