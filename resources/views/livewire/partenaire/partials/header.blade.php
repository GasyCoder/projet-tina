<div class="px-3 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <div class="flex items-center justify-between py-4">
            
            <!-- Section Gauche: Navigation + Partenaire -->
            <div class="flex items-center space-x-4 min-w-0 flex-1">
                
                <!-- Bouton Retour Stylisé -->
                <a href="{{ route('partenaire.index') }}" class="group relative w-10 h-10 inline-flex items-center justify-center rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 hover:border-gray-300 dark:hover:border-gray-500 hover:shadow-md transition-all duration-200">
                    <svg class="w-5 h-5 group-hover:-translate-x-0.5 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    <!-- Tooltip -->
                    <div class="absolute -top-10 left-1/2 transform -translate-x-1/2 px-2 py-1 bg-gray-900 dark:bg-gray-700 text-white text-xs rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-50">
                        Retour
                        <div class="absolute top-full left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-2 border-r-2 border-t-2 border-transparent border-t-gray-900 dark:border-t-gray-700"></div>
                    </div>
                </a>
                <!-- Info Partenaire avec Avatar -->
                <div class="flex items-center space-x-4 min-w-0">
                    <!-- Avatar Partenaire -->
                    <div class="relative flex-shrink-0">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 via-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-lg shadow-lg ring-4 ring-white dark:ring-gray-800">
                            {{ strtoupper(substr($partenaire->nom, 0, 2)) }}
                        </div>
                        
                        <!-- Indicateur de Statut -->
                        <div class="absolute -bottom-1 -right-1 w-4 h-4 rounded-full border-2 border-white dark:border-gray-800 {{ $partenaire->is_active ? 'bg-green-500' : 'bg-red-500' }}">
                            @if($partenaire->is_active)
                                <div class="absolute inset-0 rounded-full bg-green-500 animate-ping"></div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Détails Partenaire -->
                    <div class="min-w-0 flex-1">
                        <h1 class="text-lg sm:text-2xl lg:text-3xl font-bold text-gray-900 dark:text-gray-100 tracking-tight truncate">
                            {{ $partenaire->nom }}
                        </h1>
                        
                        <div class="flex items-center space-x-3 mt-1">
                            <!-- Badge Type -->
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-200 border border-blue-200 dark:border-blue-700">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V8a2 2 0 012-2h2zm6-1a1 1 0 00-1-1h-2a1 1 0 00-1 1v1h4V5z" clip-rule="evenodd"/>
                                </svg>
                                {{ ucfirst($partenaire->type) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section Droite: Actions -->
            <div class="flex items-center space-x-3 flex-shrink-0">
                <!-- Boutons d'Action Principaux -->
                <div class="flex items-center space-x-2">
                    
                    <!-- Nouvelle Entrée -->
                    <button wire:click="openNewEntreeModal" 
                            class="group relative inline-flex items-center justify-center w-10 h-10 sm:w-auto sm:h-auto sm:px-4 sm:py-2.5 bg-gradient-to-r from-emerald-600 to-green-600 hover:from-emerald-700 hover:to-green-700 focus:from-emerald-700 focus:to-green-700 text-white font-medium rounded-xl shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 transition-all duration-200 transform hover:scale-105 sm:hover:scale-100">
                        
                        <!-- Icône avec Animation -->
                        <div class="relative">
                            <svg class="w-5 h-5 sm:mr-2 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            <!-- Effet de pulsation -->
                            <div class="absolute inset-0 bg-white/20 rounded-full scale-0 group-hover:scale-100 opacity-0 group-hover:opacity-100 transition-all duration-500"></div>
                        </div>
                        
                        <span class="hidden sm:inline text-sm font-semibold">Nouvelle Entrée</span>
                        
                        <!-- Tooltip Mobile -->
                        <div class="sm:hidden absolute -top-12 left-1/2 transform -translate-x-1/2 px-3 py-1.5 bg-gray-900/95 dark:bg-gray-700/95 backdrop-blur-sm text-white text-xs rounded-lg opacity-0 group-hover:opacity-100 transition-all duration-200 pointer-events-none whitespace-nowrap z-50 shadow-xl">
                            Nouvelle Entrée
                            <div class="absolute top-full left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-gray-900/95 dark:border-t-gray-700/95"></div>
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>