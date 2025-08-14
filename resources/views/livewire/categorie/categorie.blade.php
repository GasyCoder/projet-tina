<div class="min-h-screen bg-gray-100 dark:bg-gray-900 pt-20 pb-6 md:px-6">
    <!-- Header Mobile/Desktop -->
    <div class="px-3 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-row items-center justify-between space-x-3 sm:flex-row sm:items-center sm:justify-between sm:space-x-0">
                <!-- Titre -->
                <div class="flex items-center space-x-4">
                    <h1 class="text-base sm:text-2xl lg:text-3xl font-semibold text-gray-900 dark:text-gray-100 tracking-tight">
                        Catégories Comptables
                    </h1>
                </div>

                <!-- Bouton d'action -->
                @if (auth()->user()->isAdmin())
                <div class="flex justify-end">
                    <button wire:click="openModal" 
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
                            Nouvelle catégorie
                        </span>
                    </button>
                </div>
                @endif
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-red-800 dark:text-red-200 text-sm font-medium">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <!-- Container Principal -->
            <div class="bg-white dark:bg-gray-800 shadow-sm mx-0 sm:mx-0 sm:rounded-xl overflow-hidden">
                
                <!-- Statistiques générales -->
                <div class="p-3 sm:p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/50 dark:to-blue-800/30 rounded-lg p-4 border border-blue-200 dark:border-blue-700">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs font-semibold text-blue-700 dark:text-blue-300 uppercase tracking-wide">Total Catégories</p>
                                    <p class="text-2xl font-bold text-blue-900 dark:text-blue-100 mt-1">{{ $rows->total() }}</p>
                                </div>
                                <div class="p-2 bg-blue-200 dark:bg-blue-700 rounded-lg">
                                    <svg class="w-6 h-6 text-blue-700 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/50 dark:to-green-800/30 rounded-lg p-4 border border-green-200 dark:border-green-700">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs font-semibold text-green-700 dark:text-green-300 uppercase tracking-wide">Recettes</p>
                                    <p class="text-2xl font-bold text-green-900 dark:text-green-100 mt-1">{{ \App\Models\Categorie::where('type', 'recette')->count() }}</p>
                                </div>
                                <div class="p-2 bg-green-200 dark:bg-green-700 rounded-lg">
                                    <svg class="w-6 h-6 text-green-700 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900/50 dark:to-red-800/30 rounded-lg p-4 border border-red-200 dark:border-red-700">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs font-semibold text-red-700 dark:text-red-300 uppercase tracking-wide">Dépenses</p>
                                    <p class="text-2xl font-bold text-red-900 dark:text-red-100 mt-1">{{ \App\Models\Categorie::where('type', 'depense')->count() }}</p>
                                </div>
                                <div class="p-2 bg-red-200 dark:bg-red-700 rounded-lg">
                                    <svg class="w-6 h-6 text-red-700 dark:text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 dark:from-yellow-900/50 dark:to-yellow-800/30 rounded-lg p-4 border border-yellow-200 dark:border-yellow-700">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs font-semibold text-yellow-700 dark:text-yellow-300 uppercase tracking-wide">Actives</p>
                                    <p class="text-2xl font-bold text-yellow-900 dark:text-yellow-100 mt-1">{{ \App\Models\Categorie::where('is_active', true)->count() }}</p>
                                </div>
                                <div class="p-2 bg-yellow-200 dark:bg-yellow-700 rounded-lg">
                                    <svg class="w-6 h-6 text-yellow-700 dark:text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filtres -->
                <div class="p-3 sm:p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex flex-col sm:flex-row gap-4">
                        <div class="flex-1">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Rechercher une catégorie</label>
                            <div class="relative">
                                <input type="text" 
                                       wire:model.live.debounce.300ms="search"
                                       placeholder="Code, nom ou description..." 
                                       class="w-full pl-10 pr-3 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 transition-all duration-200">
                                <svg class="absolute left-3 top-3 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>
                        
                        <div class="sm:w-56">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Filtrer par type</label>
                            <select wire:model.live="filterType" class="w-full px-3 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                <option value="all">🔍 Tous les types</option>
                                <option value="recette">📈 Recettes</option>
                                <option value="depense">📉 Dépenses</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Version Mobile -->
                <div class="md:hidden">
                    <div class="p-3">
                        <div class="grid grid-cols-1 gap-3">
                            @forelse($rows as $categorie)
                                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 hover:shadow-md transition-all duration-200">
                                    <div class="flex items-center justify-between mb-3">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-12 h-12 rounded-lg {{ $categorie->type == 'recette' ? 'bg-green-100 dark:bg-green-900' : 'bg-red-100 dark:bg-red-900' }} flex items-center justify-center">
                                                <span class="text-lg font-bold {{ $categorie->type == 'recette' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                                    {{ $categorie->code_comptable }}
                                                </span>
                                            </div>
                                            <div>
                                                <h3 class="font-semibold text-gray-900 dark:text-white">{{ $categorie->nom }}</h3>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $categorie->transactions_count }} transaction(s)</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-lg font-bold {{ $categorie->type == 'recette' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                                {{ number_format($categorie->montant_total, 0, ',', ' ') }} Ar
                                            </p>
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $categorie->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
                                                {{ $categorie->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center justify-between">
                                        <button wire:click="show({{ $categorie->id }})" 
                                                class="flex-1 mr-2 px-3 py-2 text-sm bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400 rounded-lg hover:bg-blue-200 dark:hover:bg-blue-800 transition-colors">
                                            Voir détails
                                        </button>
                                        @if (auth()->user()->isAdmin())
                                        <button wire:click="editModal({{ $categorie->id }})" 
                                                class="px-3 py-2 text-sm bg-orange-100 dark:bg-orange-900 text-orange-600 dark:text-orange-400 rounded-lg hover:bg-orange-200 dark:hover:bg-orange-800 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8">
                                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Aucune catégorie trouvée</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Version Desktop -->
                <div class="hidden md:block">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Code</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Catégorie</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Type</th>
                                    <th class="px-4 py-3 text-right text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Montant Total</th>
                                    <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Statut</th>
                                    <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($rows as $categorie)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors cursor-pointer" 
                                        wire:click="show({{ $categorie->id }})">
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="w-10 h-10 rounded-lg {{ $categorie->type == 'recette' ? 'bg-green-100 dark:bg-green-900' : 'bg-red-100 dark:bg-red-900' }} flex items-center justify-center mr-3">
                                                    <span class="text-sm font-bold {{ $categorie->type == 'recette' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                                        {{ $categorie->code_comptable }}
                                                    </span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <div>
                                                <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ $categorie->nom }}</div>
                                                @if($categorie->description)
                                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ Str::limit($categorie->description, 50) }}</div>
                                                @endif
                                                <div class="text-xs text-gray-400">{{ $categorie->transactions_count }} transaction(s)</div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $categorie->type == 'recette' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
                                                @if($categorie->type == 'recette')
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.293l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13a1 1 0 102 0V9.414l1.293 1.293a1 1 0 001.414-1.414z" clip-rule="evenodd" />
                                                    </svg>
                                                    Recette
                                                @else
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 2a8 8 0 100 16 8 8 0 000-16zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V9a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                    Dépense
                                                @endif
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-right">
                                            <div class="text-sm font-bold {{ $categorie->type == 'recette' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                                {{ number_format($categorie->montant_total, 0, ',', ' ') }} Ar
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-center">
                                            <button wire:click.stop="toggle({{ $categorie->id }})" 
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold transition-all duration-200 hover:scale-105 {{ $categorie->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300 hover:bg-green-200 dark:hover:bg-green-800' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300 hover:bg-red-200 dark:hover:bg-red-800' }}">
                                                <span class="w-2 h-2 mr-1.5 rounded-full {{ $categorie->is_active ? 'bg-green-400' : 'bg-red-400' }}"></span>
                                                {{ $categorie->is_active ? 'Active' : 'Inactive' }}
                                            </button>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-center">
                                            <div class="flex items-center justify-center space-x-2">
                                                <button wire:click.stop="show({{ $categorie->id }})" 
                                                        class="inline-flex items-center justify-center w-8 h-8 text-blue-600 dark:text-blue-400 bg-blue-100 dark:bg-blue-900 rounded-lg hover:bg-blue-200 dark:hover:bg-blue-800 transition-all duration-200">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </button>
                                                @if (auth()->user()->isAdmin())
                                                <button wire:click.stop="editModal({{ $categorie->id }})" 
                                                        class="inline-flex items-center justify-center w-8 h-8 text-orange-600 dark:text-orange-400 bg-orange-100 dark:bg-orange-900 rounded-lg hover:bg-orange-200 dark:hover:bg-orange-800 transition-all duration-200">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </button>
                                                <button wire:click.stop="delete({{ $categorie->id }})" 
                                                        onclick="return confirm('Supprimer cette catégorie ?')"
                                                        class="inline-flex items-center justify-center w-8 h-8 text-red-600 dark:text-red-400 bg-red-100 dark:bg-red-900 rounded-lg hover:bg-red-200 dark:hover:bg-red-800 transition-all duration-200">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-12 text-center">
                                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                            </svg>
                                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Aucune catégorie trouvée</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination desktop -->
                    <div class="bg-gray-50 dark:bg-gray-900 px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                        {{ $rows->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Inclusion des modales -->
    @include('livewire.categorie.modales.categories-modal')
</div>