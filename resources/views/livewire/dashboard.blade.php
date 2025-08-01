<div class="min-h-screen bg-gray-50 dark:bg-gray-900 pt-12 sm:pt-16 pb-6 px-4 sm:px-6 lg:px-8">
    <div class="space-y-6 sm:space-y-8 max-w-7xl mx-auto">
        <!-- En-tête avec filtres -->
        <div class="flex flex-col space-y-2 sm:space-y-3">
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                <span>Bonjour, {{ Auth::user()->name }} !</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6 text-blue-600 dark:text-blue-400 animate-pulse"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                </svg>
            </h1>
            <p class="text-sm sm:text-base text-gray-600 dark:text-gray-300 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5 text-green-500 dark:text-green-400" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 012-2h2a2 2 0 012 2v12a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                Suivi en temps réel de vos mouvements logistiques
            </p>
        </div>

        <!-- Carte Guide d'utilisation -->
        <div x-data="{ openGuide: false }"
            class="bg-white dark:bg-gray-800 rounded-xl sm:rounded-2xl shadow-sm dark:shadow-gray-900/50 border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-lg dark:hover:shadow-gray-900/70 transition-shadow duration-300">
            <div class="p-4 sm:p-6 bg-gradient-to-r from-orange-50 to-amber-50 dark:from-orange-900/30 dark:to-amber-900/30 border-b border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2 sm:gap-3">
                        <div class="p-1 sm:p-2 bg-orange-100 dark:bg-orange-800/50 rounded-lg sm:rounded-xl">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5 text-orange-600 dark:text-orange-400"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white">Guide d'utilisation rapide</h3>
                    </div>
                    <button @click="openGuide = !openGuide" class="text-gray-500 dark:text-gray-400 hover:text-orange-600 dark:hover:text-orange-400">
                        <svg x-show="!openGuide" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                        <svg x-show="openGuide" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7" />
                        </svg>
                    </button>
                </div>
            </div>
            <div x-show="openGuide" x-transition class="divide-y divide-gray-100 dark:divide-gray-700">
                <div class="p-4 sm:p-5">
                    <div class="prose prose-sm max-w-none">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Colonne Gestions -->
                            <div class="bg-blue-50 dark:bg-blue-900/20 p-3 rounded-lg border border-blue-100 dark:border-blue-800/30">
                                <h4 class="font-medium text-blue-800 dark:text-blue-300 flex items-center gap-2 mb-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    Gestions
                                </h4>
                                <ul class="text-xs text-gray-700 dark:text-gray-300 space-y-1.5">
                                    <li class="flex items-start gap-2">
                                        <span class="text-blue-600 dark:text-blue-400">›</span>
                                        <span><strong>Lieux</strong> : Gestion des emplacements</span>
                                    </li>
                                    <li class="flex items-start gap-2">
                                        <span class="text-blue-600 dark:text-blue-400">›</span>
                                        <span><strong>Produits</strong> : Articles logistiques</span>
                                    </li>
                                    <li class="flex items-start gap-2">
                                        <span class="text-blue-600 dark:text-blue-400">›</span>
                                        <span><strong>Véhicules</strong> : Gestion de flotte</span>
                                    </li>
                                    <li class="flex items-start gap-2">
                                        <span class="text-blue-600 dark:text-blue-400">›</span>
                                        <span><strong>Voyages</strong> : Planification des trajets</span>
                                    </li>
                                </ul>
                            </div>

                            <!-- Colonne Finance -->
                            <div class="bg-green-50 dark:bg-green-900/20 p-3 rounded-lg border border-green-100 dark:border-green-800/30">
                                <h4 class="font-medium text-green-800 dark:text-green-300 flex items-center gap-2 mb-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Finance
                                </h4>
                                <ul class="text-xs text-gray-700 dark:text-gray-300 space-y-1.5">
                                    <li class="flex items-start gap-2">
                                        <span class="text-green-600 dark:text-green-400">›</span>
                                        <span><strong>Transactions</strong> : Suivi des opérations</span>
                                    </li>
                                    <li class="flex items-start gap-2">
                                        <span class="text-green-600 dark:text-green-400">›</span>
                                        <span><strong>Bilans</strong> : Situations financières</span>
                                    </li>
                                </ul>
                            </div>

                            <!-- Colonne Stocks -->
                            <div class="bg-purple-50 dark:bg-purple-900/20 p-3 rounded-lg border border-purple-100 dark:border-purple-800/30">
                                <h4 class="font-medium text-purple-800 dark:text-purple-300 flex items-center gap-2 mb-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 7l-8-4-8 4m16 0v6l-8 4m0-10l-8 4m8 4V3m0 10l-8-4m8 4l8-4V7" />
                                    </svg>
                                    Stocks (Admin)
                                </h4>
                                <ul class="text-xs text-gray-700 dark:text-gray-300 space-y-1.5">
                                    <li class="flex items-start gap-2">
                                        <span class="text-purple-600 dark:text-purple-400">›</span>
                                        <span>Gestion des inventaires</span>
                                    </li>
                                    <li class="flex items-start gap-2">
                                        <span class="text-purple-600 dark:text-purple-400">›</span>
                                        <span>Suivi des niveaux de stock</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="mt-4 text-xs text-gray-500 dark:text-gray-400 italic">
                            Astuce : Cliquez sur les en-têtes des cartes pour déployer les options disponibles.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contenu principal : Cartes pour chaque menu -->
        <div class="grid grid-cols-1 gap-4 sm:gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <!-- Carte Gestions (avec sous-menu déroulant sur clic) -->
            <div x-data="{ openGestions: false }"
                class="bg-white dark:bg-gray-800 rounded-xl sm:rounded-2xl shadow-sm dark:shadow-gray-900/50 border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-lg dark:hover:shadow-gray-900/70 transition-shadow duration-300">
                <div class="p-4 sm:p-6 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/30 dark:to-indigo-900/30 border-b border-gray-100 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2 sm:gap-3">
                            <div class="p-1 sm:p-2 bg-blue-100 dark:bg-blue-800/50 rounded-lg sm:rounded-xl">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5 text-blue-600 dark:text-blue-400"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 012-2h2a2 2 0 012 2v12a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                            <h3 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white">Gestions</h3>
                        </div>
                        <button @click="openGestions = !openGestions" class="text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">
                            <svg x-show="!openGestions" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                            <svg x-show="openGestions" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div x-show="openGestions" x-transition class="divide-y divide-gray-100 dark:divide-gray-700">
                    <a href="{{ route('lieux.index') }}"
                        class="block p-3 sm:p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">Lieux</p>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Gérer les emplacements et destinations.</p>
                    </a>
                    <a href="{{ route('produits.index') }}"
                        class="block p-3 sm:p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">Produits</p>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Gérer les produits logistiques.</p>
                    </a>
                    <a href="{{ route('vehicules.index') }}"
                        class="block p-3 sm:p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">Véhicules</p>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Gérer la flotte de véhicules.</p>
                    </a>
                    <a href="{{ route('voyages.index') }}"
                        class="block p-3 sm:p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">Voyages</p>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Gérer les itinéraires et trajets.</p>
                    </a>
                </div>
            </div>

            <!-- Carte Finance (avec sous-menu déroulant sur clic) -->
            <div x-data="{ openFinance: false }"
                class="bg-white dark:bg-gray-800 rounded-xl sm:rounded-2xl shadow-sm dark:shadow-gray-900/50 border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-lg dark:hover:shadow-gray-900/70 transition-shadow duration-300">
                <div class="p-4 sm:p-6 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/30 dark:to-emerald-900/30 border-b border-gray-100 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2 sm:gap-3">
                            <div class="p-1 sm:p-2 bg-green-100 dark:bg-green-800/50 rounded-lg sm:rounded-xl">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5 text-green-600 dark:text-green-400"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h3 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white">Finance</h3>
                        </div>
                        <button @click="openFinance = !openFinance" class="text-gray-500 dark:text-gray-400 hover:text-green-600 dark:hover:text-green-400">
                            <svg x-show="!openFinance" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                            <svg x-show="openFinance" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div x-show="openFinance" x-transition class="divide-y divide-gray-100 dark:divide-gray-700">
                    <a href="{{ route('finance.index') }}"
                        class="block p-3 sm:p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">Gestion financière</p>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Suivi général des transactions.</p>
                    </a>
                    <a href="{{ route('finance.situations') }}"
                        class="block p-3 sm:p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">Situations financières</p>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Bilan des revenus/dépenses.</p>
                    </a>
                </div>
            </div>

            <!-- Carte Stocks (conditionnelle pour admins) -->
            @if(Auth::user()->isAdmin())
                <a href="{{ route('admin.stocks') }}"
                    class="bg-white dark:bg-gray-800 rounded-xl sm:rounded-2xl shadow-sm dark:shadow-gray-900/50 border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-lg dark:hover:shadow-gray-900/70 transition-shadow duration-300">
                    <div class="p-4 sm:p-6 bg-gradient-to-r from-purple-50 to-violet-50 dark:from-purple-900/30 dark:to-violet-900/30 border-b border-gray-100 dark:border-gray-700">
                        <div class="flex items-center gap-2 sm:gap-3">
                            <div class="p-1 sm:p-2 bg-purple-100 dark:bg-purple-800/50 rounded-lg sm:rounded-xl">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5 text-purple-600 dark:text-purple-400"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M20 7l-8-4-8 4m16 0v6l-8 4m0-10l-8 4m8 4V3m0 10l-8-4m8 4l8-4V7" />
                                </svg>
                            </div>
                            <h3 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white">Stocks</h3>
                        </div>
                    </div>
                    <div class="p-3 sm:p-4">
                        <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400">Gestion des stocks et inventaires.</p>
                        <div class="mt-3 sm:mt-4 flex justify-end">
                            <span
                                class="text-xs sm:text-sm font-medium text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition">Accéder
                                →</span>
                        </div>
                    </div>
                </a>
            @endif
        </div>
    </div>
</div>