<!-- NAVBAR MODERNE AMÉLIORÉE -->
<nav x-data="{ 
        open: false, 
        openGestions: false,
        openFinance: false,
        darkMode: localStorage.getItem('darkMode') === 'true' || (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches),
        toggleDarkMode() {
            this.darkMode = !this.darkMode;
            localStorage.setItem('darkMode', this.darkMode);
            if (this.darkMode) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        }
    }"
    x-init="
        if (darkMode) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    "
    class="fixed top-0 inset-x-0 z-50 bg-white/95 dark:bg-gray-900/95 backdrop-blur-md border-b border-gray-200/20 dark:border-gray-700/30 shadow-lg shadow-gray-900/5">
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            
            <!-- Logo + Navigation Links -->
            <div class="flex items-center space-x-8">
                <!-- Logo Amélioré -->
                <div class="shrink-0">
                    <a href="{{ route('dashboard') }}"
                        class="group flex items-center space-x-3 text-xl font-bold text-gray-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 transition-all duration-300">
                        <div class="relative">
                            <div class="absolute -inset-1 bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg blur opacity-25 group-hover:opacity-75 transition duration-300"></div>
                            <div class="relative w-8 h-8 bg-gradient-to-br from-blue-600 to-purple-600 rounded-lg flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                                </svg>
                            </div>
                        </div>
                        <span class="bg-gradient-to-r from-gray-900 to-gray-600 dark:from-white dark:to-gray-300 bg-clip-text text-transparent">
                            Gestion-Mme Tina
                        </span>
                    </a>
                </div>

                <!-- Navigation Links Desktop -->
                <div class="hidden lg:flex lg:items-center lg:space-x-1">
                    <!-- Accueil -->
                    <a href="{{ route('dashboard') }}"
                        class="group flex items-center space-x-2 px-4 py-2 rounded-xl text-sm font-medium transition-all duration-300 hover:bg-gray-100 dark:hover:bg-gray-800/50 {{ request()->routeIs('dashboard') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span>Accueil</span>
                    </a>

                    <!-- Dropdown Gestions -->
                    @php
                    $isGestionsActive = request()->routeIs('lieux.*', 'produits.*', 'vehicules.*', 'voyages.*', 'partenaires.*');
                    @endphp
                    <div x-data="{ open: false }" @click.away="open = false" class="relative">
                        <button @click="open = !open"
                            class="group flex items-center space-x-2 px-4 py-2 rounded-xl text-sm font-medium transition-all duration-300 hover:bg-gray-100 dark:hover:bg-gray-800/50 {{ $isGestionsActive ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 012-2h2a2 2 0 012 2v12a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            <span>Gestions</span>
                            <svg class="w-3 h-3 transition-transform duration-300" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M19 9l-7 7-7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>

                        <div x-show="openGestions" x-transition
                            class="absolute left-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 dark:ring-gray-600 z-10">
                            <x-nav-link :href="route('lieux.index')" :active="request()->routeIs('lieux.*')"
                                class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 hover:text-blue-600 dark:hover:text-blue-400 transition">
                                Lieux
                            </x-nav-link>
                            <x-nav-link :href="route('produits.index')" :active="request()->routeIs('produits.*')"
                                class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 hover:text-blue-600 dark:hover:text-blue-400 transition">
                                Produits
                            </x-nav-link>
                            <x-nav-link :href="route('vehicules.index')" :active="request()->routeIs('vehicules.*')"
                                class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 hover:text-blue-600 dark:hover:text-blue-400 transition">
                                Véhicules
                            </x-nav-link>
                            <x-nav-link :href="route('finance.partenaires')" :active="request()->routeIs('partenaires.*')"
                                class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 hover:text-blue-600 dark:hover:text-blue-400 transition">
                                Partenaires
                            </x-nav-link>
                            <x-nav-link :href="route('voyages.index')" :active="request()->routeIs('voyages.*')"
                                class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 hover:text-blue-600 dark:hover:text-blue-400 transition">
                                Voyages
                            </x-nav-link>
                        </div>
                    </div>

                    <!-- Dropdown Finance -->
                    @php
                    $isFinanceActive = request()->routeIs('finance.index', 'finance.situations', 'finance.dashboard.situations');
                    @endphp
                    <div x-data="{ open: false }" @click.away="open = false" class="relative">
                        <button @click="open = !open"
                            class="group flex items-center space-x-2 px-4 py-2 rounded-xl text-sm font-medium transition-all duration-300 hover:bg-gray-100 dark:hover:bg-gray-800/50 {{ $isFinanceActive ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Finance</span>
                            <svg class="w-3 h-3 transition-transform duration-300" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M19 9l-7 7-7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>

                        <!-- Dropdown Menu Finance -->
                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95 transform translate-y-2"
                             x-transition:enter-end="opacity-100 scale-100 transform translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute left-0 mt-2 w-56 rounded-2xl bg-white dark:bg-gray-800 shadow-xl ring-1 ring-black/5 dark:ring-gray-700/50 backdrop-blur-xl border border-gray-200/50 dark:border-gray-700/50">
                            <div class="p-2 space-y-1">
                                <a href="{{ route('finance.partenaires') }}"
                                    class="group flex items-center px-3 py-2.5 text-sm text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-white transition-all duration-200 {{ request()->routeIs('finance.partenaires') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : '' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    Partenaires
                                </a>
                                <a href="{{ route('finance.index') }}"
                                    class="group flex items-center px-3 py-2.5 text-sm text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-white transition-all duration-200 {{ request()->routeIs('finance.index') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : '' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17M17 13v4a2 2 0 01-2 2H9a2 2 0 01-2-2v-4m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01" />
                                    </svg>
                                    Achats
                                </a>
                                <a href="{{ route('finance.situations') }}"
                                    class="group flex items-center px-3 py-2.5 text-sm text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-white transition-all duration-200 {{ request()->routeIs('finance.situations') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : '' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                    Ventes
                                </a>
                                <a href="{{ route('finance.situations') }}"
                                    class="group flex items-center px-3 py-2.5 text-sm text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-white transition-all duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 012-2h2a2 2 0 012 2v12a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                    Situations journaliers
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Stocks (Admin) -->
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('admin.stocks') }}"
                            class="group flex items-center space-x-2 px-4 py-2 rounded-xl text-sm font-medium transition-all duration-300 hover:bg-gray-100 dark:hover:bg-gray-800/50 {{ request()->routeIs('admin.stocks') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M20 7l-8-4-8 4m16 0v6l-8 4m0-10l-8 4m8 4V3m0 10l-8-4m8 4l8-4V7" />
                            </svg>
                            <span>Stocks</span>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Actions Right -->
            <div class="flex items-center space-x-4">
                <!-- Dark Mode Toggle -->
                <button @click="toggleDarkMode()" 
                    class="group relative p-2.5 rounded-xl text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800/50 focus:outline-none transition-all duration-300">
                    <div class="absolute -inset-1 bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl blur opacity-0 group-hover:opacity-25 transition duration-300"></div>
                    <div class="relative">
                        <!-- Sun Icon (Light Mode) -->
                        <svg x-show="!darkMode" x-transition:enter="transition ease-in-out duration-300 transform" 
                             x-transition:enter-start="opacity-0 rotate-90 scale-75" x-transition:enter-end="opacity-100 rotate-0 scale-100"
                             xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <!-- Moon Icon (Dark Mode) -->
                        <svg x-show="darkMode" x-transition:enter="transition ease-in-out duration-300 transform" 
                             x-transition:enter-start="opacity-0 rotate-90 scale-75" x-transition:enter-end="opacity-100 rotate-0 scale-100"
                             xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                    </div>
                </button>

                <!-- User Dropdown (Desktop) -->
                <div class="hidden lg:block">
                    <div x-data="{ open: false }" @click.away="open = false" class="relative">
                        <button @click="open = !open"
                            class="group flex items-center space-x-3 px-4 py-2 rounded-xl text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800/50 transition-all duration-300">
                            <div class="w-8 h-8 bg-gradient-to-br from-blue-600 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <span class="hidden md:block">{{ Auth::user()->name }}</span>
                            <svg class="w-3 h-3 transition-transform duration-300" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M19 9l-7 7-7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>

                        <!-- User Dropdown Menu -->
                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95 transform translate-y-2"
                             x-transition:enter-end="opacity-100 scale-100 transform translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-56 rounded-2xl bg-white dark:bg-gray-800 shadow-xl ring-1 ring-black/5 dark:ring-gray-700/50 backdrop-blur-xl border border-gray-200/50 dark:border-gray-700/50">
                            <div class="p-2 space-y-1">
                                <div class="px-3 py-2 border-b border-gray-200 dark:border-gray-700">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</p>
                                </div>
                                <a href="{{ route('profile.edit') }}"
                                    class="group flex items-center px-3 py-2.5 text-sm text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-white transition-all duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    Profil
                                </a>
                                @if(Auth::user()->isAdmin())
                                    <a href="{{ route('users.index') }}"
                                        class="group flex items-center px-3 py-2.5 text-sm text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-white transition-all duration-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                                        </svg>
                                        Utilisateurs
                                    </a>
                                @endif
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="group flex items-center w-full px-3 py-2.5 text-sm text-gray-700 dark:text-gray-300 rounded-xl hover:bg-red-50 dark:hover:bg-red-900/20 hover:text-red-600 dark:hover:text-red-400 transition-all duration-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                        </svg>
                                        Déconnexion
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mobile Menu Button -->
                <button @click="open = !open" class="lg:hidden p-2.5 rounded-xl text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800/50 focus:outline-none transition-all duration-300">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16m-7 6h7" />
                        <path x-show="open" stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform -translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform -translate-y-2"
         class="lg:hidden bg-white/95 dark:bg-gray-900/95 backdrop-blur-md border-t border-gray-200/20 dark:border-gray-700/30">
        <div class="px-4 py-6 space-y-2">
            
            <!-- Mobile Navigation Links -->
            <a href="{{ route('dashboard') }}"
                class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800/50' }} transition-all duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span>Accueil</span>
            </a>

            <!-- Mobile Gestions -->
            <div x-data="{ openMobile: false }" class="space-y-1">
                <button @click="openMobile = !openMobile"
                    class="flex items-center justify-between w-full px-4 py-3 rounded-xl text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800/50 transition-all duration-200">
                    <div class="flex items-center space-x-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 012-2h2a2 2 0 012 2v12a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <span>Gestions</span>
                    </div>
                    <svg class="w-4 h-4 transition-transform duration-300" :class="openMobile ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M19 9l-7 7-7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
                <div x-show="openMobile" x-transition class="pl-8 space-y-1">
                    <a href="{{ route('lieux.index') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-lg text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        </svg>
                        Lieux
                    </a>
                    <a href="{{ route('produits.index') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-lg text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        Produits
                    </a>
                    <a href="{{ route('vehicules.index') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-lg text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2v0a2 2 0 01-2 2H8a2 2 0 01-2-2v0a2 2 0 01-2-2V9a2 2 0 012-2h0z" />
                        </svg>
                        Véhicules
                    </a>
                    <a href="{{ route('finance.partenaires') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-lg text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Partenaires
                    </a>
                    <a href="{{ route('voyages.index') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-lg text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                        </svg>
                        Voyages
                    </a>
                </div>
            </div>

            <!-- Mobile Finance -->
            <div x-data="{ openMobile: false }" class="space-y-1">
                <button @click="openMobile = !openMobile"
                    class="flex items-center justify-between w-full px-4 py-3 rounded-xl text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800/50 transition-all duration-200">
                    <div class="flex items-center space-x-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Finance</span>
                    </div>
                    <svg class="w-4 h-4 transition-transform duration-300" :class="openMobile ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M19 9l-7 7-7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
                <div x-show="openMobile" x-transition class="pl-8 space-y-1">
                    <a href="{{ route('finance.partenaires') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-lg text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Partenaires
                    </a>
                    <a href="{{ route('finance.index') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-lg text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17M17 13v4a2 2 0 01-2 2H9a2 2 0 01-2-2v-4m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01" />
                        </svg>
                        Achats
                    </a>
                    <a href="{{ route('finance.situations') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-lg text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        Situations journaliers
                    </a>
                </div>
            </div>

            @if(Auth::user()->isAdmin())
                <a href="{{ route('admin.stocks') }}"
                    class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium {{ request()->routeIs('admin.stocks') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800/50' }} transition-all duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0v6l-8 4m0-10l-8 4m8 4V3m0 10l-8-4m8 4l8-4V7" />
                    </svg>
                    <span>Stocks</span>
                </a>
            @endif

            <!-- Mobile User Section -->
            <div class="pt-6 border-t border-gray-200 dark:border-gray-700 space-y-2">
                <div class="flex items-center space-x-3 px-4 py-3 rounded-xl bg-gray-50 dark:bg-gray-800/50">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ Auth::user()->name }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</div>
                    </div>
                </div>
                
                <a href="{{ route('profile.edit') }}"
                    class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800/50 transition-all duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span>Profil</span>
                </a>

                @if(Auth::user()->isAdmin())
                    <a href="{{ route('users.index') }}"
                        class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800/50 transition-all duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                        </svg>
                        <span>Utilisateurs</span>
                    </a>
                @endif

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="flex items-center space-x-3 w-full px-4 py-3 rounded-xl text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-red-50 dark:hover:bg-red-900/20 hover:text-red-600 dark:hover:text-red-400 transition-all duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span>Déconnexion</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>