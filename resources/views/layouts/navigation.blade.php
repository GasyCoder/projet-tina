<!-- NAVBAR FIXE EN HAUT -->
<nav x-data="{ open: false, openGestions: false }" class="fixed top-0 inset-x-0 z-50 bg-white border-b border-gray-200 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <!-- Logo + liens -->
            <div class="flex items-center">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-2 text-xl font-bold text-gray-800">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                        </svg>
                        <span>Gestion-Mme Tina</span>
                    </a>
                </div>

                <!-- Liens de navigation (grands écrans) -->
                <div class="hidden sm:flex sm:items-center sm:ms-10 sm:space-x-6">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')"
                        class="flex items-center space-x-2 text-gray-700 hover:text-blue-600 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span>Accueil</span>
                    </x-nav-link>

                    <!-- Dropdown Gestions -->
                    <div x-data="{ openGestions: false }" class="relative group">
                        <button @click="openGestions = !openGestions" @click.away="openGestions = false"
                            class="flex items-center px-3 py-2 text-sm font-medium text-gray-700 hover:text-blue-600 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 012-2h2a2 2 0 012 2v12a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            <span>Gestions</span>
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M19 9l-7 7-7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                        <div x-show="openGestions" x-transition
                            class="absolute left-0 mt-2 w-48 bg-white rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 z-10">
                            <x-nav-link :href="route('voyages.index')" :active="request()->routeIs('voyages.*')"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition">
                                Voyages
                            </x-nav-link>
                            <x-nav-link :href="route('produits.index')" :active="request()->routeIs('produits.*')"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition">
                                Produits
                            </x-nav-link>
                            <x-nav-link :href="route('lieux.index')" :active="request()->routeIs('lieux.*')"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition">
                                Lieux
                            </x-nav-link>
                            <x-nav-link :href="route('vehicules.index')" :active="request()->routeIs('vehicules.*')"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition">
                                Véhicules
                            </x-nav-link>
                        </div>
                    </div>
                    <x-nav-link :href="route('finance.index')" :active="request()->routeIs('finance.*')"
                        class="flex items-center space-x-2 text-gray-700 hover:text-blue-600 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Finance</span>
                    </x-nav-link>
                    @if(Auth::user()->isAdmin())
                        <x-nav-link :href="route('admin.stocks')" :active="request()->routeIs('admin.stocks')"
                            class="flex items-center space-x-2 text-gray-700 hover:text-blue-600 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0v6l-8 4m0-10l-8 4m8 4V3m0 10l-8-4m8 4l8-4V7" />
                            </svg>
                            <span>Stocks</span>
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Bouton hamburger (mobile) -->
            <div class="flex items-center sm:hidden">
                <button @click="open = !open" class="p-2 rounded-md text-gray-500 hover:text-blue-600 hover:bg-blue-50 focus:outline-none transition">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16m-7 6h7" />
                        <path x-show="open" stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Dropdown utilisateur (desktop) -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center px-3 py-2 text-sm font-medium text-gray-700 hover:text-blue-600 rounded-md transition">
                            <span>{{ Auth::user()->name }}</span>
                            <svg class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">Profil</x-dropdown-link>
                        @if(Auth::user()->isAdmin())
                        <x-dropdown-link :href="route('users.index')">Utilisateurs</x-dropdown-link>
                        @endif
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                Déconnexion
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
        </div>
    </div>

    <!-- Menu responsive (mobile) -->
    <div x-show="open" x-transition class="sm:hidden bg-white border-t border-gray-200">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')"
                class="flex items-center px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Accueil
            </x-responsive-nav-link>
            <div x-data="{ openGestionsMobile: false }" class="px-4 py-2">
                <button @click="openGestionsMobile = !openGestionsMobile"
                    class="flex items-center w-full text-left text-gray-700 hover:text-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 012-2h2a2 2 0 012 2v12a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    Gestions
                    <svg class="h-4 w-4 ml-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="openGestionsMobile" x-transition class="pl-8 space-y-1">
                    <x-responsive-nav-link :href="route('voyages.index')" :active="request()->routeIs('voyages.*')"
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600">
                        Voyages
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('produits.index')" :active="request()->routeIs('produits.*')"
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600">
                        Produits
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('lieux.index')" :active="request()->routeIs('lieux.*')"
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600">
                        Lieux
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('vehicules.index')" :active="request()->routeIs('vehicules.*')"
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600">
                        Véhicules
                    </x-responsive-nav-link>
                </div>
            </div>
            <x-responsive-nav-link :href="route('finance.index')" :active="request()->routeIs('finance.*')"
                class="flex items-center px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Finance
            </x-responsive-nav-link>
            @if(Auth::user()->isAdmin())
                <x-responsive-nav-link :href="route('admin.stocks')" :active="request()->routeIs('admin.stocks')"
                    class="flex items-center px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0v6l-8 4m0-10l-8 4m8 4V3m0 10l-8-4m8 4l8-4V7" />
                    </svg>
                    Stocks
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Compte utilisateur (mobile) -->
        <div class="pt-4 pb-3 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')"
                    class="flex items-center px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Profil
                </x-responsive-nav-link>
                @if(Auth::user()->isAdmin())
                <x-responsive-nav-link :href="route('users.index')"
                    class="flex items-center px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Utilisateurs
                </x-responsive-nav-link>
                @endif
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();"
                        class="flex items-center px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Déconnexion
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>