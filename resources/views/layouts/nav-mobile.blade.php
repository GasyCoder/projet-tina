<!-- MENU MOBILE -->
<div x-show="open"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 -translate-y-2"
     x-transition:enter-end="opacity-100 translate-y-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 translate-y-0"
     x-transition:leave-end="opacity-0 -translate-y-2"
     class="lg:hidden bg-white/95 dark:bg-gray-900/95 backdrop-blur-md border-t border-gray-200/20 dark:border-gray-700/30">

    <div class="px-4 py-6 space-y-2">

        <!-- Accueil -->
        <a href="{{ route('dashboard') }}"
           class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200
                  {{ request()->routeIs('dashboard') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800/50' }}">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span>Accueil</span>
        </a>

        <!-- Gestions (Partenaires, Catégories) -->
        <div x-data="{ openMobileG: false }" class="space-y-1">
            <button @click="openMobileG = !openMobileG"
                    class="flex w-full items-center justify-between px-4 py-3 rounded-xl text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800/50 transition">
                <div class="flex items-center space-x-3">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1"/>
                    </svg>
                    <span>Gestions</span>
                </div>
                <svg class="w-4 h-4 transition-transform duration-300" :class="openMobileG ? 'rotate-180' : ''"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M19 9l-7 7-7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
            <div x-show="openMobileG" x-transition class="pl-8 space-y-1">
                <a href="{{ route('partenaire.index') }}"
                   class="flex items-center space-x-3 px-4 py-2.5 rounded-lg text-sm transition
                          {{ request()->routeIs('partenaire.index', 'partenaire.show') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-white' }}">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7M7 20v-2a3 3 0 015.356-1.857"/>
                    </svg>
                    Partenaires
                </a>
                <a href="{{ route('categories.index') }}"
                   class="flex items-center space-x-3 px-4 py-2.5 rounded-lg text-sm transition
                          {{ request()->routeIs('categories.index') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-white' }}">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M7 7h.01M7 3h5l8 8-8 8-8-8z"/>
                    </svg>
                    Catégories
                </a>
            </div>
        </div>

        <!-- Finance (Ventes, Comptes) -->
        <div x-data="{ openMobileF: false }" class="space-y-1">
            <button @click="openMobileF = !openMobileF"
                    class="flex w-full items-center justify-between px-4 py-3 rounded-xl text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800/50 transition">
                <div class="flex items-center space-x-3">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2"/>
                    </svg>
                    <span>Finance</span>
                </div>
                <svg class="w-4 h-4 transition-transform duration-300" :class="openMobileF ? 'rotate-180' : ''"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M19 9l-7 7-7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
            <div x-show="openMobileF" x-transition class="pl-8 space-y-1">
                <a href="{{ route('vente.index') }}"
                   class="flex items-center space-x-3 px-4 py-2.5 rounded-lg text-sm transition
                          {{ request()->routeIs('vente.index') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-white' }}">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14z"/>
                    </svg>
                    Ventes
                </a>
                <a href="{{ route('compte.index') }}"
                   class="flex items-center space-x-3 px-4 py-2.5 rounded-lg text-sm transition
                          {{ request()->routeIs('compte.index') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-white' }}">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 21V5H7a2 2 0 00-2 2v14"/>
                    </svg>
                    Comptes
                </a>
            </div>
        </div>

        <!-- Logistiques (Lieux, Produits, Véhicules, Voyages) -->
        <div x-data="{ openMobileL: false }" class="space-y-1">
            <button @click="openMobileL = !openMobileL"
                    class="flex w-full items-center justify-between px-4 py-3 rounded-xl text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800/50 transition">
                <div class="flex items-center space-x-3">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 19v-6a2 2 0 00-2-2H5v8h2zM13 21h2a2 2 0 002-2V7h-4v14z"/>
                    </svg>
                    <span>Logistiques</span>
                </div>
                <svg class="w-4 h-4 transition-transform duration-300" :class="openMobileL ? 'rotate-180' : ''"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M19 9l-7 7-7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
            <div x-show="openMobileL" x-transition class="pl-8 space-y-1">
                <a href="{{ route('lieux.index') }}"
                   class="flex items-center space-x-3 px-4 py-2.5 rounded-lg text-sm transition
                          {{ request()->routeIs('lieux.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-white' }}">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9A2 2 0 0110.586 21l-4.243-4.243a8 8 0 1111.314 0z"/></svg>
                    Lieux
                </a>
                <a href="{{ route('produits.index') }}"
                   class="flex items-center space-x-3 px-4 py-2.5 rounded-lg text-sm transition
                          {{ request()->routeIs('produits.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-white' }}">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4M12 11L4 7m8 4v10M4 7v10l8 4"/></svg>
                    Produits
                </a>
                <a href="{{ route('vehicules.index') }}"
                   class="flex items-center space-x-3 px-4 py-2.5 rounded-lg text-sm transition
                          {{ request()->routeIs('vehicules.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-white' }}">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586L20 7.414V15a2 2 0 01-2 2H8a2 2 0 01-2-2V9"/></svg>
                    Véhicules
                </a>
                <a href="{{ route('voyages.index') }}"
                   class="flex items-center space-x-3 px-4 py-2.5 rounded-lg text-sm transition
                          {{ request()->routeIs('voyages.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-white' }}">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l6-3m0 0V4L9 7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618L15 4"/></svg>
                    Voyages
                </a>
            </div>
        </div>

        <!-- Admin -->
        <div x-data="{ openAdmin: false }" class="space-y-1">
            <button @click="openAdmin = !openAdmin"
                    class="flex w-full items-center justify-between px-4 py-3 rounded-xl text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800/50 transition">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-gradient-to-br from-blue-600 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold text-xs">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div class="text-left">
                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ Auth::user()->name }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">Administration</div>
                    </div>
                </div>
                <svg class="w-4 h-4 transition-transform duration-300" :class="openAdmin ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M19 9l-7 7-7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>

            <div x-show="openAdmin" x-transition class="pl-8 space-y-1">
                <a href="{{ route('profile.edit') }}"
                   class="flex items-center space-x-3 px-4 py-2.5 rounded-lg text-sm text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-white transition">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    <span>Profil</span>
                </a>

                @if(Auth::user()->isAdmin())
                    <a href="{{ route('users.index') }}"
                       class="flex items-center space-x-3 px-4 py-2.5 rounded-lg text-sm text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-white transition">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm6 0v-1a6 6 0 00-9-5.197"/></svg>
                        <span>Utilisateurs</span>
                    </a>
                @endif

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="flex items-center space-x-3 w-full px-4 py-2.5 rounded-lg text-sm text-gray-600 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6"/></svg>
                        <span>Déconnexion</span>
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>