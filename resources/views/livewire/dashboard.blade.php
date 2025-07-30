<div class="min-h-screen bg-gray-50 pt-20 pb-6 px-4 md:px-6">
    <div class="space-y-8 max-w-7xl mx-auto">
        <!-- En-tête avec filtres (inchangé, repris de votre code) -->
        <div class="flex flex-col space-y-2">
            <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                <span>Bonjour, {{ Auth::user()->name }} !</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                </svg>
            </h1>
            <p class="text-gray-600 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 012-2h2a2 2 0 012 2v12a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                Suivi en temps réel de vos mouvements logistiques
            </p>
        </div>

        <!-- Contenu principal : Cartes pour chaque menu -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Carte Gestions (avec sous-menu déroulant sur clic) -->
            <div x-data="{ openGestions: false }" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-shadow duration-300">
                <div class="p-6 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-blue-100 rounded-xl">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 012-2h2a2 2 0 012 2v12a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">Gestions</h3>
                        </div>
                        <button @click="openGestions = !openGestions" class="text-gray-500 hover:text-blue-600">
                            <svg x-show="!openGestions" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                            <svg x-show="openGestions" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div x-show="openGestions" x-transition class="divide-y divide-gray-100">
                    <a href="{{ route('voyages.index') }}" class="block p-4 hover:bg-gray-50 transition-colors duration-200">
                        <p class="text-sm font-medium text-gray-900">Voyages</p>
                        <p class="text-xs text-gray-600">Gérer les itinéraires et trajets.</p>
                    </a>
                    <a href="{{ route('produits.index') }}" class="block p-4 hover:bg-gray-50 transition-colors duration-200">
                        <p class="text-sm font-medium text-gray-900">Produits</p>
                        <p class="text-xs text-gray-600">Gérer les produits logistiques.</p>
                    </a>
                    <a href="{{ route('lieux.index') }}" class="block p-4 hover:bg-gray-50 transition-colors duration-200">
                        <p class="text-sm font-medium text-gray-900">Lieux</p>
                        <p class="text-xs text-gray-600">Gérer les emplacements et destinations.</p>
                    </a>
                    <a href="{{ route('vehicules.index') }}" class="block p-4 hover:bg-gray-50 transition-colors duration-200">
                        <p class="text-sm font-medium text-gray-900">Véhicules</p>
                        <p class="text-xs text-gray-600">Gérer la flotte de véhicules.</p>
                    </a>
                </div>
            </div>
<!-- Carte Finance (avec sous-menu déroulant sur clic) -->
<div x-data="{ openFinance: false }" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-shadow duration-300">
    <div class="p-6 bg-gradient-to-r from-green-50 to-emerald-50 border-b border-gray-100">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-green-100 rounded-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900">Finance</h3>
            </div>
            <button @click="openFinance = !openFinance" class="text-gray-500 hover:text-green-600">
                <svg x-show="!openFinance" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
                <svg x-show="openFinance" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7" />
                </svg>
            </button>
        </div>
    </div>
    <div x-show="openFinance" x-transition class="divide-y divide-gray-100">
        <a href="{{ route('finance.index') }}" class="block p-4 hover:bg-gray-50 transition-colors duration-200">
            <p class="text-sm font-medium text-gray-900">Gestion financière</p>
            <p class="text-xs text-gray-600">Suivi général des transactions.</p>
        </a>
        <a href="{{ route('finance.situations') }}" class="block p-4 hover:bg-gray-50 transition-colors duration-200">
            <p class="text-sm font-medium text-gray-900">Situations financières</p>
            <p class="text-xs text-gray-600">Bilan des revenus/dépenses.</p>
        </a>
    </div>
</div>

            <!-- Carte Stocks (conditionnelle pour admins) -->
            @if(Auth::user()->isAdmin())
            <a href="{{ route('admin.stocks') }}" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-shadow duration-300">
                <div class="p-6 bg-gradient-to-r from-purple-50 to-violet-50 border-b border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-purple-100 rounded-xl">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0v6l-8 4m0-10l-8 4m8 4V3m0 10l-8-4m8 4l8-4V7" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Stocks</h3>
                    </div>
                </div>
                <div class="p-4">
                    <p class="text-sm text-gray-600">Gestion des stocks et inventaires.</p>
                    <div class="mt-4 flex justify-end">
                        <span class="text-sm font-medium text-blue-600 hover:text-blue-800 transition">Accéder →</span>
                    </div>
                </div>
            </a>
            @endif
        </div>
    </div>
</div>