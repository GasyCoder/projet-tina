<div
    class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50 to-indigo-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 pt-12 sm:pt-16 pb-6 px-4 sm:px-6 lg:px-8">
    <div class="space-y-6 sm:space-y-8 max-w-7xl mx-auto">
        <!-- En-tête avec animation -->
        <div class="flex flex-col space-y-2 sm:space-y-3 animate-fade-in">
            <div class="flex items-center justify-between">
                <div>
                    <h1
                        class="text-2xl sm:text-3xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 dark:from-blue-400 dark:to-purple-400 bg-clip-text text-transparent flex items-center gap-3">
                        <span>Bonjour, {{ $currentUser->name }} !</span>
                        <div class="relative">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-6 w-6 sm:h-8 sm:w-8 text-blue-500 dark:text-blue-400 animate-bounce"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            <div class="absolute -top-1 -right-1 w-3 h-3 bg-green-400 rounded-full animate-ping"></div>
                        </div>
                    </h1>
                    <p class="text-sm sm:text-base text-gray-600 dark:text-gray-300 flex items-center gap-2 mt-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5 text-green-500"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Tableau de bord - Dernière mise à jour il y a {{ date('i') }} minutes
                    </p>
                </div>
                <div class="hidden sm:flex items-center gap-3">
                    <div class="text-right">
                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ now()->format('d/m/Y') }}</div>
                        <div class="text-sm font-semibold text-gray-700 dark:text-gray-300" id="current-time">
                            {{ $currentTime }}</div>
                    </div>
                    <button wire:click="refreshStats"
                        class="p-2 text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-400 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </button>
                    <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                </div>
            </div>
        </div>

        <!-- Statistiques rapides -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 sm:gap-6 animate-slide-up" style="animation-delay: 0.1s">
            <!-- Total Ventes -->
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg dark:shadow-gray-900/50 border border-gray-100 dark:border-gray-700 p-4 sm:p-6 hover:shadow-xl hover:scale-105 transition-all duration-300 group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400">Ventes Aujourd'hui
                        </p>
                        <p class="text-lg sm:text-2xl font-bold text-green-600 dark:text-green-400 mt-1">
                            {{ $formattedVentesToday }}</p>
                        <div class="flex items-center gap-1 mt-1">
                            @if ($tendanceVentes['type'] === 'positive')
                                <svg class="w-3 h-3 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M3.293 9.707a1 1 0 010-1.414l6-6a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L4.707 9.707a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span
                                    class="text-xs text-green-600 dark:text-green-400">{{ $tendanceVentes['text'] }}</span>
                            @elseif($tendanceVentes['type'] === 'negative')
                                <svg class="w-3 h-3 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 10.293a1 1 0 010 1.414l-6 6a1 1 0 01-1.414 0l-6-6a1 1 0 111.414-1.414L9 14.586V3a1 1 0 012 0v11.586l4.293-4.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span
                                    class="text-xs text-red-600 dark:text-red-400">{{ $tendanceVentes['text'] }}</span>
                            @else
                                <svg class="w-3 h-3 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span
                                    class="text-xs text-gray-600 dark:text-gray-400">{{ $tendanceVentes['text'] }}</span>
                            @endif
                        </div>
                    </div>
                    <div
                        class="p-3 bg-green-100 dark:bg-green-900/30 rounded-xl group-hover:bg-green-200 dark:group-hover:bg-green-900/50 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600 dark:text-green-400"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Voyages Actifs -->
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg dark:shadow-gray-900/50 border border-gray-100 dark:border-gray-700 p-4 sm:p-6 hover:shadow-xl hover:scale-105 transition-all duration-300 group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400">Voyages Actifs</p>
                        <p class="text-lg sm:text-2xl font-bold text-blue-600 dark:text-blue-400 mt-1">
                            {{ $voyagesActifs }}</p>
                        <div class="flex items-center gap-1 mt-1">
                            <div class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></div>
                            <span class="text-xs text-blue-600 dark:text-blue-400">En cours</span>
                        </div>
                    </div>
                    <div
                        class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-xl group-hover:bg-blue-200 dark:group-hover:bg-blue-900/50 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 dark:text-blue-400"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Solde Total -->
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg dark:shadow-gray-900/50 border border-gray-100 dark:border-gray-700 p-4 sm:p-6 hover:shadow-xl hover:scale-105 transition-all duration-300 group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400">Solde Total</p>
                        <p class="text-lg sm:text-2xl font-bold text-purple-600 dark:text-purple-400 mt-1">
                            {{ $formattedSoldeTotal }}</p>
                        <div class="flex items-center gap-1 mt-1">
                            <svg class="w-3 h-3 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-xs text-purple-600 dark:text-purple-400">Stable</span>
                        </div>
                    </div>
                    <div
                        class="p-3 bg-purple-100 dark:bg-purple-900/30 rounded-xl group-hover:bg-purple-200 dark:group-hover:bg-purple-900/50 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600 dark:text-purple-400"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Alertes -->
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg dark:shadow-gray-900/50 border border-gray-100 dark:border-gray-700 p-4 sm:p-6 hover:shadow-xl hover:scale-105 transition-all duration-300 group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400">Notifications</p>
                        <p class="text-lg sm:text-2xl font-bold text-orange-600 dark:text-orange-400 mt-1">
                            {{ $notifications }}</p>
                        <div class="flex items-center gap-1 mt-1">
                            @if ($notifications > 0)
                                <div class="w-2 h-2 bg-orange-500 rounded-full animate-pulse"></div>
                                <span class="text-xs text-orange-600 dark:text-orange-400">Nouvelles</span>
                            @else
                                <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                <span class="text-xs text-green-600 dark:text-green-400">Aucune</span>
                            @endif
                        </div>
                    </div>
                    <div
                        class="p-3 bg-orange-100 dark:bg-orange-900/30 rounded-xl group-hover:bg-orange-200 dark:group-hover:bg-orange-900/50 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-orange-600 dark:text-orange-400"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-5-5-5 5h5zm0 0v5" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg dark:shadow-gray-900/50 border border-gray-100 dark:border-gray-700 overflow-hidden animate-slide-up"
            style="animation-delay: 0.2s">
            <div
                class="p-4 sm:p-6 bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-indigo-900/30 dark:to-purple-900/30 border-b border-gray-100 dark:border-gray-700">
                <h3 class="text-lg sm:text-xl font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600 dark:text-indigo-400"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    Actions Rapides
                </h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Accès direct aux fonctions principales</p>
            </div>
            <div class="p-4 sm:p-6">
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4">
                    <button wire:click="createVente"
                        class="group flex flex-col items-center p-3 sm:p-4 bg-green-50 dark:bg-green-900/20 rounded-xl border border-green-100 dark:border-green-800/30 hover:bg-green-100 dark:hover:bg-green-900/40 hover:scale-105 transition-all duration-200">
                        <div
                            class="p-2 bg-green-100 dark:bg-green-800/50 rounded-lg group-hover:bg-green-200 dark:group-hover:bg-green-800/70 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600 dark:text-green-400"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                        </div>
                        <span
                            class="text-xs sm:text-sm font-medium text-green-700 dark:text-green-300 mt-2 text-center">Nouvelle
                            Vente</span>
                    </button>

                    <button wire:click="createVoyage"
                        class="group flex flex-col items-center p-3 sm:p-4 bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-100 dark:border-blue-800/30 hover:bg-blue-100 dark:hover:bg-blue-900/40 hover:scale-105 transition-all duration-200">
                        <div
                            class="p-2 bg-blue-100 dark:bg-blue-800/50 rounded-lg group-hover:bg-blue-200 dark:group-hover:bg-blue-800/70 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 dark:text-blue-400"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                            </svg>
                        </div>
                        <span
                            class="text-xs sm:text-sm font-medium text-blue-700 dark:text-blue-300 mt-2 text-center">Nouveau
                            Voyage</span>
                    </button>

                    <button wire:click="viewInventaire"
                        class="group flex flex-col items-center p-3 sm:p-4 bg-purple-50 dark:bg-purple-900/20 rounded-xl border border-purple-100 dark:border-purple-800/30 hover:bg-purple-100 dark:hover:bg-purple-900/40 hover:scale-105 transition-all duration-200">
                        <div
                            class="p-2 bg-purple-100 dark:bg-purple-800/50 rounded-lg group-hover:bg-purple-200 dark:group-hover:bg-purple-800/70 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5 text-purple-600 dark:text-purple-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <span
                            class="text-xs sm:text-sm font-medium text-purple-700 dark:text-purple-300 mt-2 text-center">Inventaire</span>
                    </button>

                    <button wire:click="viewRapports"
                        class="group flex flex-col items-center p-3 sm:p-4 bg-orange-50 dark:bg-orange-900/20 rounded-xl border border-orange-100 dark:border-orange-800/30 hover:bg-orange-100 dark:hover:bg-orange-900/40 hover:scale-105 transition-all duration-200">
                        <div
                            class="p-2 bg-orange-100 dark:bg-orange-800/50 rounded-lg group-hover:bg-orange-200 dark:group-hover:bg-orange-800/70 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5 text-orange-600 dark:text-orange-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 012-2h2a2 2 0 012 2v12a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <span
                            class="text-xs sm:text-sm font-medium text-orange-700 dark:text-orange-300 mt-2 text-center">Rapport</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Guide d'utilisation amélioré -->
        <!-- Guide d'utilisation adapté -->
<div x-data="{ openGuide: false }"
    class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg dark:shadow-gray-900/50 border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-xl transition-shadow duration-300 animate-slide-up"
    style="animation-delay: 0.3s">
    <div class="p-4 sm:p-6 bg-gradient-to-r from-orange-50 to-amber-50 dark:from-orange-900/30 dark:to-amber-900/30 border-b border-gray-100 dark:border-gray-700">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2 sm:gap-3">
                <div class="p-2 bg-orange-100 dark:bg-orange-800/50 rounded-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-orange-600 dark:text-orange-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Guide d'utilisation</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Découvrez toutes les fonctionnalités</p>
                </div>
            </div>
            <button @click="openGuide = !openGuide" class="text-gray-500 dark:text-gray-400 hover:text-orange-600 dark:hover:text-orange-400 p-2 rounded-lg hover:bg-orange-100 dark:hover:bg-orange-900/30 transition-all duration-200">
                <svg x-show="!openGuide" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
                <svg x-show="openGuide" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7" />
                </svg>
            </button>
        </div>
    </div>
    <div x-show="openGuide" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" class="p-4 sm:p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            
            <!-- Gestions (Partenaires & Catégories) -->
            <div class="bg-gradient-to-br from-orange-50 to-amber-50 dark:from-orange-900/20 dark:to-amber-900/20 p-4 rounded-xl border border-orange-100 dark:border-orange-800/30">
                <h4 class="font-semibold text-orange-800 dark:text-orange-300 flex items-center gap-2 mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Gestions
                </h4>
                <div class="space-y-2">
                    <div class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                        <div class="w-2 h-2 bg-orange-500 rounded-full"></div>
                        <span><strong>Partenaires</strong> : Gestion des relations commerciales</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                        <div class="w-2 h-2 bg-orange-500 rounded-full"></div>
                        <span><strong>Catégories</strong> : Classification des transactions</span>
                    </div>
                </div>
            </div>

            <!-- Finance -->
            <div class="bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 p-4 rounded-xl border border-green-100 dark:border-green-800/30">
                <h4 class="font-semibold text-green-800 dark:text-green-300 flex items-center gap-2 mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Finance
                </h4>
                <div class="space-y-2">
                    <div class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                        <span><strong>Ventes</strong> : Suivi des transactions</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                        <span><strong>Comptes</strong> : Gestion des comptes</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                        <span><strong>Situations</strong> : Bilans financiers</span>
                    </div>
                </div>
            </div>

            <!-- Logistiques -->
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 p-4 rounded-xl border border-blue-100 dark:border-blue-800/30">
                <h4 class="font-semibold text-blue-800 dark:text-blue-300 flex items-center gap-2 mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 012-2h2a2 2 0 012 2v12a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    Logistiques
                </h4>
                <div class="space-y-2">
                    <div class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                        <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                        <span><strong>Lieux</strong> : Gestion des emplacements</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                        <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                        <span><strong>Produits</strong> : Articles logistiques</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                        <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                        <span><strong>Véhicules</strong> : Gestion de flotte</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                        <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                        <span><strong>Voyages</strong> : Planification trajets</span>
                    </div>
                </div>
            </div>

            <!-- Stocks (Admin) -->
            <div class="bg-gradient-to-br from-purple-50 to-violet-50 dark:from-purple-900/20 dark:to-violet-900/20 p-4 rounded-xl border border-purple-100 dark:border-purple-800/30">
                <h4 class="font-semibold text-purple-800 dark:text-purple-300 flex items-center gap-2 mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0v6l-8 4m0-10l-8 4m8 4V3m0 10l-8-4m8 4l8-4V7" />
                    </svg>
                    Stocks (Admin)
                </h4>
                <div class="space-y-2">
                    <div class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                        <div class="w-2 h-2 bg-purple-500 rounded-full"></div>
                        <span>Gestion des inventaires</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                        <div class="w-2 h-2 bg-purple-500 rounded-full"></div>
                        <span>Suivi niveaux de stock</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                        <div class="w-2 h-2 bg-purple-500 rounded-full"></div>
                        <span>Alertes automatiques</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Note informative -->
        <div class="mt-6 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-200 dark:border-gray-600">
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h5 class="text-sm font-medium text-gray-900 dark:text-white mb-1">Astuce d'utilisation</h5>
                    <p class="text-xs text-gray-600 dark:text-gray-400">
                        Cliquez sur les en-têtes des cartes ci-dessus ou utilisez la barre de navigation pour accéder rapidement aux différents modules. 
                        Les statistiques en temps réel sont automatiquement mises à jour.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

        <!-- Cartes principales avec animations -->
<div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 animate-slide-up" style="animation-delay: 0.4s">
    
    <!-- Carte Gestions (Partenaires & Catégories) -->
    <div x-data="{ openGestions: false }" class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg dark:shadow-gray-900/50 border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-xl hover:scale-[1.02] transition-all duration-300 group">
        <div class="p-6 bg-gradient-to-r from-orange-50 to-amber-50 dark:from-orange-900/30 dark:to-amber-900/30 border-b border-gray-100 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="p-3 bg-orange-100 dark:bg-orange-800/50 rounded-xl group-hover:bg-orange-200 dark:group-hover:bg-orange-800/70 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-orange-600 dark:text-orange-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Gestions</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Partenaires & Catégories</p>
                    </div>
                </div>
                <button @click="openGestions = !openGestions" class="text-gray-500 dark:text-gray-400 hover:text-orange-600 dark:hover:text-orange-400 p-2 rounded-lg hover:bg-orange-100 dark:hover:bg-orange-900/30 transition-all duration-200">
                    <svg x-show="!openGestions" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                    <svg x-show="openGestions" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7" />
                    </svg>
                </button>
            </div>
        </div>
        <div x-show="openGestions" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 max-h-0" x-transition:enter-end="opacity-100 max-h-96" class="divide-y divide-gray-100 dark:divide-gray-700">
            <a href="{{ route('finance.partenaires') }}" class="group/item block p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-orange-50 dark:bg-orange-900/30 rounded-lg group-hover/item:bg-orange-100 dark:group-hover/item:bg-orange-900/50 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-orange-600 dark:text-orange-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900 dark:text-white">Partenaires</p>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Gestion des partenaires commerciaux</p>
                    </div>
                </div>
            </a>
            <a href="{{ route('categories.index') }}" class="group/item block p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-orange-50 dark:bg-orange-900/30 rounded-lg group-hover/item:bg-orange-100 dark:group-hover/item:bg-orange-900/50 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-orange-600 dark:text-orange-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900 dark:text-white">Catégories</p>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Classification des transactions</p>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Carte Finance -->
    <div x-data="{ openFinance: false }" class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg dark:shadow-gray-900/50 border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-xl hover:scale-[1.02] transition-all duration-300 group">
        <div class="p-6 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/30 dark:to-emerald-900/30 border-b border-gray-100 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="p-3 bg-green-100 dark:bg-green-800/50 rounded-xl group-hover:bg-green-200 dark:group-hover:bg-green-800/70 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Finance</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Ventes & Comptes</p>
                    </div>
                </div>
                <button @click="openFinance = !openFinance" class="text-gray-500 dark:text-gray-400 hover:text-green-600 dark:hover:text-green-400 p-2 rounded-lg hover:bg-green-100 dark:hover:bg-green-900/30 transition-all duration-200">
                    <svg x-show="!openFinance" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                    <svg x-show="openFinance" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7" />
                    </svg>
                </button>
            </div>
        </div>
        <div x-show="openFinance" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 max-h-0" x-transition:enter-end="opacity-100 max-h-96" class="divide-y divide-gray-100 dark:divide-gray-700">
            <a href="{{ route('vente.index') }}" class="group/item block p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-green-50 dark:bg-green-900/30 rounded-lg group-hover/item:bg-green-100 dark:group-hover/item:bg-green-900/50 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900 dark:text-white">Ventes</p>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Gestion des ventes</p>
                    </div>
                </div>
            </a>
            <a href="{{ route('compte.index') }}" class="group/item block p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-green-50 dark:bg-green-900/30 rounded-lg group-hover/item:bg-green-100 dark:group-hover/item:bg-green-900/50 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900 dark:text-white">Comptes</p>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Gestion des comptes</p>
                    </div>
                </div>
            </a>
            <a href="{{ route('finance.situations') }}" class="group/item block p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-green-50 dark:bg-green-900/30 rounded-lg group-hover/item:bg-green-100 dark:group-hover/item:bg-green-900/50 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900 dark:text-white">Situations financières</p>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Bilan revenus/dépenses</p>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Carte Logistiques -->
    <div x-data="{ openLogistiques: false }" class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg dark:shadow-gray-900/50 border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-xl hover:scale-[1.02] transition-all duration-300 group">
        <div class="p-6 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/30 dark:to-indigo-900/30 border-b border-gray-100 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="p-3 bg-blue-100 dark:bg-blue-800/50 rounded-xl group-hover:bg-blue-200 dark:group-hover:bg-blue-800/70 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 012-2h2a2 2 0 012 2v12a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Logistiques</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">4 modules disponibles</p>
                    </div>
                </div>
                <button @click="openLogistiques = !openLogistiques" class="text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 p-2 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-all duration-200">
                    <svg x-show="!openLogistiques" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                    <svg x-show="openLogistiques" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7" />
                    </svg>
                </button>
            </div>
        </div>
        <div x-show="openLogistiques" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 max-h-0" x-transition:enter-end="opacity-100 max-h-96" class="divide-y divide-gray-100 dark:divide-gray-700">
            <a href="{{ route('lieux.index') }}" class="group/item block p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-blue-50 dark:bg-blue-900/30 rounded-lg group-hover/item:bg-blue-100 dark:group-hover/item:bg-blue-900/50 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900 dark:text-white">Lieux</p>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Gérer emplacements et destinations</p>
                    </div>
                </div>
            </a>
            <a href="{{ route('produits.index') }}" class="group/item block p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-blue-50 dark:bg-blue-900/30 rounded-lg group-hover/item:bg-blue-100 dark:group-hover/item:bg-blue-900/50 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m0-11v18" />
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900 dark:text-white">Produits</p>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Gérer produits logistiques</p>
                    </div>
                </div>
            </a>
            <a href="{{ route('vehicules.index') }}" class="group/item block p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-blue-50 dark:bg-blue-900/30 rounded-lg group-hover/item:bg-blue-100 dark:group-hover/item:bg-blue-900/50 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2v0a2 2 0 01-2 2H8a2 2 0 01-2-2v0a2 2 0 01-2-2V9a2 2 0 012-2h0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900 dark:text-white">Véhicules</p>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Gérer flotte de véhicules</p>
                    </div>
                </div>
            </a>
            <a href="{{ route('voyages.index') }}" class="group/item block p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-blue-50 dark:bg-blue-900/30 rounded-lg group-hover/item:bg-blue-100 dark:group-hover/item:bg-blue-900/50 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900 dark:text-white">Voyages</p>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Gérer itinéraires et trajets</p>
                    </div>
                </div>
            </a>
        </div>
    </div>

    {{-- <!-- Carte Stocks (Admin seulement) - OPTIONNELLE -->
    @if($currentUser->isAdmin())
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg dark:shadow-gray-900/50 border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-xl hover:scale-[1.02] transition-all duration-300 group">
        <div class="p-6 bg-gradient-to-r from-purple-50 to-violet-50 dark:from-purple-900/30 dark:to-violet-900/30 border-b border-gray-100 dark:border-gray-700">
            <div class="flex items-center gap-3">
                <div class="p-3 bg-purple-100 dark:bg-purple-800/50 rounded-xl group-hover:bg-purple-200 dark:group-hover:bg-purple-800/70 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600 dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0v6l-8 4m0-10l-8 4m8 4V3m0 10l-8-4m8 4l8-4V7" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Stocks</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Accès administrateur</p>
                </div>
            </div>
        </div>
        <div class="p-4">
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Gestion avancée des stocks et inventaires.</p>
            <div class="flex justify-end">
                <span class="text-sm font-medium text-purple-600 dark:text-purple-400 hover:text-purple-800 dark:hover:text-purple-300 transition cursor-pointer">
                    Accéder →
                </span>
            </div>
        </div>
    </div>
    @endif --}}
</div>

        <!-- Messages Flash -->
        @if (session()->has('message'))
            <div
                class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-slide-up">
                {{ session('message') }}
            </div>
        @endif
    </div>

    <!-- Styles CSS -->
    <style>
        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slide-up {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fade-in 0.6s ease-out;
        }

        .animate-slide-up {
            animation: slide-up 0.6s ease-out;
            animation-fill-mode: both;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(156, 163, 175, 0.4);
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(156, 163, 175, 0.6);
        }
    </style>

    <!-- JavaScript pour l'horloge temps réel -->
    <script>
        function updateTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('fr-FR', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
            const timeElement = document.getElementById('current-time');
            if (timeElement) {
                timeElement.textContent = timeString;
            }
        }

        // Mettre à jour l'heure toutes les secondes
        setInterval(updateTime, 1000);

        // Animation d'entrée progressive pour les cartes
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.animate-slide-up');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.style.animationPlayState = 'running';
                    }
                });
            }, {
                threshold: 0.1
            });

            cards.forEach((card) => {
                card.style.animationPlayState = 'paused';
                observer.observe(card);
            });
        });
    </script>
</div>
