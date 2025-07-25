<!-- NAVBAR FIXE EN HAUT -->
<nav x-data="{ open: false }" class="fixed top-0 inset-x-0 z-50 bg-white border-b border-gray-100 shadow-md">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Logo + liens -->
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="text-xl font-bold text-gray-800">
                        🚛 LogistiqueNG
                    </a>
                </div>

                <!-- Liens de navigation (grands écrans) -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">Dashboard</x-nav-link>
                    <x-nav-link :href="route('voyages.index')" :active="request()->routeIs('voyages.*')">Voyages</x-nav-link>
                    <x-nav-link :href="route('produits.index')" :active="request()->routeIs('produits.*')">Produits</x-nav-link>
                    <x-nav-link :href="route('lieux.index')" :active="request()->routeIs('lieux.*')">Lieux</x-nav-link>
                    <x-nav-link :href="route('vehicules.index')" :active="request()->routeIs('vehicules.*')">Véhicules</x-nav-link>
                    <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')">Utilisateurs</x-nav-link>
                    <x-nav-link :href="route('finance.index')" :active="request()->routeIs('finance.*')">Finance</x-nav-link>
                    <x-nav-link :href="route('stocks')" :active="request()->routeIs('stocks')">Stocks</x-nav-link>
                </div>
            </div>

            <!-- Bouton hamburger (mobile) -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = !open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Dropdown utilisateur (desktop) -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 transition">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-1">
                                <svg class="h-4 w-4 fill-current" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0..." clip-rule="evenodd"/></svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">Profile</x-dropdown-link>
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
    <div x-show="open" class="sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">Dashboard</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('voyages.index')" :active="request()->routeIs('voyages.*')">Voyages</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('produits.index')" :active="request()->routeIs('produits.*')">Produits</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('lieux.index')" :active="request()->routeIs('lieux.*')">Lieux</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('vehicules.index')" :active="request()->routeIs('vehicules.*')">Véhicules</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')">Utilisateurs</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('finance.index')" :active="request()->routeIs('finance.*')">Finance</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('stocks')" :active="request()->routeIs('stocks')">Stocks</x-responsive-nav-link>
        </div>

        <!-- Compte utilisateur (mobile) -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">Profile</x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                        Déconnexion
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
