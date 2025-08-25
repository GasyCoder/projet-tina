<div>
    <!-- Contenu principal avec padding pour navbar fixe -->
    <div class="pt-16"> <!-- Padding-top global pour la navbar fixe -->
        <!-- Messages de notification (commun) -->
        @if (session('success'))
            <div
                class="mb-4 mx-3 sm:mx-0 bg-green-50 dark:bg-green-900/20 border-l-4 border-green-400 dark:border-green-600 p-4 rounded-r-lg">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-500 dark:text-green-400 mr-3 flex-shrink-0" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <p class="text-green-800 dark:text-green-200 text-sm font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <!-- ===================================== -->
        <!-- 📱 VERSION MOBILE (écrans < 768px)   -->
        <!-- ===================================== -->
        <div class="md:hidden">
            <div class="px-3 py-4">
                <!-- En-tête mobile -->
                <div class="mb-4">
                    <h1 class="text-base sm:text-xl font-semibold text-gray-900 dark:text-white mb-1">
                        Partenaires
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">
                        Gérez vos fournisseurs et clients
                    </p>
                </div>

                <!-- Bouton Nouveau - Mobile (en haut) - Seulement pour admin -->
                @if (auth()->user()->isAdmin())
                    <div class="mb-4">
                        <button wire:click="openModal"
                            class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200 shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Nouveau partenaire
                        </button>
                    </div>
                @endif

                <!-- Statistiques mobile (2x2 grid) -->
                <div class="grid grid-cols-2 gap-2 mb-4">
                    <div
                        class="bg-white dark:bg-gray-800 rounded-lg p-3 shadow-sm border border-gray-200 dark:border-gray-700">
                        <div class="text-center">
                            <div class="inline-flex p-1.5 bg-blue-100 dark:bg-blue-900 rounded-lg mb-1">
                                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <p class="text-xs font-medium text-gray-600 dark:text-gray-400 mb-0.5">Total</p>
                            <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $rows->total() }}</p>
                        </div>
                    </div>

                    <div
                        class="bg-white dark:bg-gray-800 rounded-lg p-3 shadow-sm border border-gray-200 dark:border-gray-700">
                        <div class="text-center">
                            <div class="inline-flex p-1.5 bg-green-100 dark:bg-green-900 rounded-lg mb-1">
                                <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <p class="text-xs font-medium text-gray-600 dark:text-gray-400 mb-0.5">Clients</p>
                            <p class="text-xl font-bold text-gray-900 dark:text-white">
                                {{ \App\Models\Partenaire::where('type', 'client')->count() }}</p>
                        </div>
                    </div>

                    <div
                        class="bg-white dark:bg-gray-800 rounded-lg p-3 shadow-sm border border-gray-200 dark:border-gray-700">
                        <div class="text-center">
                            <div class="inline-flex p-1.5 bg-purple-100 dark:bg-purple-900 rounded-lg mb-1">
                                <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            </div>
                            <p class="text-xs font-medium text-gray-600 dark:text-gray-400 mb-0.5">Fournisseurs</p>
                            <p class="text-xl font-bold text-gray-900 dark:text-white">
                                {{ \App\Models\Partenaire::where('type', 'fournisseur')->count() }}</p>
                        </div>
                    </div>

                    <div
                        class="bg-white dark:bg-gray-800 rounded-lg p-3 shadow-sm border border-gray-200 dark:border-gray-700">
                        <div class="text-center">
                            <div class="inline-flex p-1.5 bg-yellow-100 dark:bg-yellow-900 rounded-lg mb-1">
                                <svg class="w-4 h-4 text-yellow-600 dark:text-yellow-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                </svg>
                            </div>
                            <p class="text-xs font-medium text-gray-600 dark:text-gray-400 mb-0.5">Actifs</p>
                            <p class="text-xl font-bold text-gray-900 dark:text-white">
                                {{ \App\Models\Partenaire::where('is_active', true)->count() }}</p>
                        </div>
                    </div>
                </div>

                <!-- Filtres mobile (vertical) -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-3 mb-4">
                    <div class="space-y-3">
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Rechercher</label>
                            <div class="relative">
                                <input type="text" wire:model.live.debounce.300ms="search"
                                    placeholder="Nom, téléphone, adresse..."
                                    class="w-full pl-9 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm">
                                <svg class="absolute left-2.5 top-2.5 h-4 w-4 text-gray-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Type</label>
                            <select wire:model.live="filterType"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm">
                                <option value="all">Tous les types</option>
                                <option value="client">👥 Clients</option>
                                <option value="fournisseur">🏭 Fournisseurs</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Liste mobile (cards) -->
                <div class="space-y-3">
                    @forelse($rows as $p)
                        <div
                            class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-3">
                            <!-- En-tête de la card -->
                            <div class="flex items-start justify-between mb-2">
                                <div class="flex-1">
                                    <h3 class="text-base font-semibold text-gray-900 dark:text-white">
                                        {{ $p->nom }}</h3>
                                </div>
                                <div class="flex flex-col items-end space-y-1">
                                    <!-- Type badge -->
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                            {{ $p->type == 'client' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300' }}">
                                        @if ($p->type == 'client')
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        @else
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M3 4a1 1 0 011-1h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                            </svg>
                                        @endif
                                        {{ ucfirst($p->type) }}
                                    </span>
                                    <!-- Statut toggle -->
                                    <button wire:click="toggle({{ $p->id }})"
                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium transition-colors duration-200
                                    {{ $p->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
                                        <span
                                            class="w-1.5 h-1.5 mr-1 rounded-full {{ $p->is_active ? 'bg-green-400' : 'bg-red-400' }}"></span>
                                        {{ $p->is_active ? 'Actif' : 'Inactif' }}
                                    </button>
                                </div>
                            </div>

                            <!-- Informations de contact -->
                            @if ($p->telephone || $p->adresse)
                                <div class="mb-3 space-y-1">
                                    @if ($p->telephone)
                                        <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                            </svg>
                                            <a href="tel:{{ $p->telephone }}"
                                                class="hover:text-blue-600 dark:hover:text-blue-400">{{ $p->telephone }}</a>
                                        </div>
                                    @endif
                                    @if ($p->adresse)
                                        <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            <span>{{ Str::limit($p->adresse, 40) }}</span>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <!-- Actions mobile (grid 3 colonnes) -->
                            <div class="grid grid-cols-3 gap-1">
                                <button wire:click="show({{ $p->id }})"
                                    class="inline-flex items-center justify-center px-2 py-1.5 text-xs font-medium text-blue-600 dark:text-blue-400 bg-blue-100 dark:bg-blue-900 rounded-md hover:bg-blue-200 dark:hover:bg-blue-800 transition-colors duration-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                                @if (auth()->user()->isAdmin())
                                    <button wire:click="editModal({{ $p->id }})"
                                        class="inline-flex items-center justify-center px-2 py-1.5 text-xs font-medium text-orange-600 dark:text-orange-400 bg-orange-100 dark:bg-orange-900 rounded-md hover:bg-orange-200 dark:hover:bg-orange-800 transition-colors duration-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>

                                    <button wire:click="delete({{ $p->id }})"
                                        onclick="return confirm('Supprimer {{ $p->nom }} ?')"
                                        class="inline-flex items-center justify-center px-2 py-1.5 text-xs font-medium text-red-600 dark:text-red-400 bg-red-100 dark:bg-red-900 rounded-md hover:bg-red-200 dark:hover:bg-red-800 transition-colors duration-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <svg class="mx-auto h-10 w-10 text-gray-400 mb-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Aucun partenaire trouvé</p>
                            @if ($search || $filterType !== 'all')
                                <p class="text-xs text-gray-400 mt-1">Modifiez vos critères de recherche</p>
                            @endif
                        </div>
                    @endforelse
                </div>

                <!-- Pagination mobile -->
                <div class="mt-4">
                    {{ $rows->links() }}
                </div>
            </div>
        </div>

        <!-- ====================================== -->
        <!-- 🖥️ VERSION DESKTOP (écrans ≥ 768px)  -->
        <!-- ====================================== -->
        <div class="hidden md:block">
            <!-- Header Desktop -->
            <div class="px-3 sm:px-6 lg:px-8">
                <div class="max-w-7xl mx-auto">
                    <div
                        class="flex flex-row items-center justify-between space-x-3 sm:flex-row sm:items-center sm:justify-between sm:space-x-0">
                        <!-- Titre -->
                        <div class="flex items-center space-x-4">
                            <h1
                                class="text-base sm:text-2xl lg:text-3xl font-semibold text-gray-900 dark:text-gray-100 tracking-tight">
                                Gestion des Partenaires
                            </h1>
                        </div>
                        @if (auth()->user()->isAdmin())
                            <!-- Bouton d'action -->
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
                                    <svg class="w-4 h-4 sm:hidden" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4" />
                                    </svg>
                                    <!-- Texte + icône desktop -->
                                    <span class="hidden sm:inline-flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4v16m8-8H4" />
                                        </svg>
                                        Nouveau partenaire
                                    </span>
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="px-0 sm:px-6 lg:px-8 py-4 sm:py-6">
                <div class="max-w-7xl mx-auto">
                    <!-- Container Principal -->
                    <div class="bg-white dark:bg-gray-800 shadow-sm mx-0 sm:mx-0 sm:rounded-xl overflow-hidden">
                        <!-- Statistiques desktop (ligne unique) -->
                        <div class="p-3 sm:p-6 border-b border-gray-200 dark:border-gray-700">
                            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                                <div
                                    class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/50 dark:to-blue-800/30 rounded-lg p-4 border border-blue-200 dark:border-blue-700">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p
                                                class="text-xs font-semibold text-blue-700 dark:text-blue-300 uppercase tracking-wide">
                                                Total Partenaires</p>
                                            <p class="text-2xl font-bold text-blue-900 dark:text-blue-100 mt-1">
                                                {{ $rows->total() }}</p>
                                        </div>
                                        <div class="p-2 bg-blue-200 dark:bg-blue-700 rounded-lg">
                                            <svg class="w-6 h-6 text-blue-700 dark:text-blue-300" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                <div
                                    class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/50 dark:to-green-800/30 rounded-lg p-4 border border-green-200 dark:border-green-700">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p
                                                class="text-xs font-semibold text-green-700 dark:text-green-300 uppercase tracking-wide">
                                                Clients</p>
                                            <p class="text-2xl font-bold text-green-900 dark:text-green-100 mt-1">
                                                {{ \App\Models\Partenaire::where('type', 'client')->count() }}</p>
                                        </div>
                                        <div class="p-2 bg-green-200 dark:bg-green-700 rounded-lg">
                                            <svg class="w-6 h-6 text-green-700 dark:text-green-300" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                <div
                                    class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/50 dark:to-purple-800/30 rounded-lg p-4 border border-purple-200 dark:border-purple-700">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p
                                                class="text-xs font-semibold text-purple-700 dark:text-purple-300 uppercase tracking-wide">
                                                Fournisseurs</p>
                                            <p class="text-2xl font-bold text-purple-900 dark:text-purple-100 mt-1">
                                                {{ \App\Models\Partenaire::where('type', 'fournisseur')->count() }}</p>
                                        </div>
                                        <div class="p-2 bg-purple-200 dark:bg-purple-700 rounded-lg">
                                            <svg class="w-6 h-6 text-purple-700 dark:text-purple-300" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                <div
                                    class="bg-gradient-to-br from-yellow-50 to-yellow-100 dark:from-yellow-900/50 dark:to-yellow-800/30 rounded-lg p-4 border border-yellow-200 dark:border-yellow-700">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p
                                                class="text-xs font-semibold text-yellow-700 dark:text-yellow-300 uppercase tracking-wide">
                                                Partenaires Actifs</p>
                                            <p class="text-2xl font-bold text-yellow-900 dark:text-yellow-100 mt-1">
                                                {{ \App\Models\Partenaire::where('is_active', true)->count() }}</p>
                                        </div>
                                        <div class="p-2 bg-yellow-200 dark:bg-yellow-700 rounded-lg">
                                            <svg class="w-6 h-6 text-yellow-700 dark:text-yellow-300" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Filtres desktop (horizontal) -->
                        <div class="p-3 sm:p-6 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex flex-col xl:flex-row gap-4">
                                <div class="flex-1">
                                    <label
                                        class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Rechercher
                                        un partenaire</label>
                                    <div class="relative">
                                        <input type="text" wire:model.live.debounce.300ms="search"
                                            placeholder="Rechercher par nom, téléphone ou adresse..."
                                            class="w-full pl-10 pr-3 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 transition-all duration-200">
                                        <svg class="absolute left-3 top-3 h-5 w-5 text-gray-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </div>
                                </div>

                                <div class="xl:w-56">
                                    <label
                                        class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Filtrer
                                        par type</label>
                                    <select wire:model.live="filterType"
                                        class="w-full px-3 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                        <option value="all">🔍 Tous les types</option>
                                        <option value="client">👥 Clients</option>
                                        <option value="fournisseur">🏭 Fournisseurs</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Tableau desktop -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead
                                    class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800">
                                    <tr>

                                        <th
                                            class="px-4 py-3 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                            Partenaire
                                        </th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                            Contact
                                        </th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                            Type
                                        </th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                            Statut
                                        </th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @forelse($rows as $p)
                                        <tr
                                            class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all duration-200">

                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-8 w-8">
                                                        <div
                                                            class="h-8 w-8 rounded-lg bg-gradient-to-br {{ $p->type == 'client' ? 'from-green-400 to-green-600' : 'from-purple-400 to-purple-600' }} flex items-center justify-center">
                                                            <span
                                                                class="text-sm font-bold text-white">{{ substr($p->nom, 0, 1) }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="ml-3">
                                                        <div
                                                            class="text-sm font-semibold text-gray-900 dark:text-white">
                                                            {{ $p->nom }}</div>
                                                        @if ($p->adresse)
                                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                                {{ Str::limit($p->adresse, 30) }}</div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                @if ($p->telephone)
                                                    <a href="tel:{{ $p->telephone }}"
                                                        class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-medium transition-colors">
                                                        {{ $p->telephone }}
                                                    </a>
                                                @else
                                                    <span class="text-sm text-gray-400 italic">Non renseigné</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold 
                                                {{ $p->type == 'client' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300' }}">
                                                    @if ($p->type == 'client')
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor"
                                                            viewBox="0 0 20 20">
                                                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        Client
                                                    @else
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor"
                                                            viewBox="0 0 20 20">
                                                            <path
                                                                d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z" />
                                                        </svg>
                                                        Fournisseur
                                                    @endif
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <button wire:click="toggle({{ $p->id }})"
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold transition-all duration-200 hover:scale-105
                                                        {{ $p->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300 hover:bg-green-200 dark:hover:bg-green-800' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300 hover:bg-red-200 dark:hover:bg-red-800' }}">
                                                    <span
                                                        class="w-2 h-2 mr-1.5 rounded-full {{ $p->is_active ? 'bg-green-400' : 'bg-red-400' }}"></span>
                                                    {{ $p->is_active ? 'Actif' : 'Inactif' }}
                                                </button>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <div class="flex items-center space-x-2">
                                                    <button wire:click="show({{ $p->id }})"
                                                        class="inline-flex items-center justify-center w-8 h-8 text-blue-600 dark:text-blue-400 bg-blue-100 dark:bg-blue-900 rounded-lg hover:bg-blue-200 dark:hover:bg-blue-800 transition-all duration-200 hover:scale-105">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                    </button>
                                                    @if (auth()->user()->isAdmin())
                                                        <!-- Bouton Modifier -->
                                                        <button wire:click="editModal({{ $p->id }})"
                                                            class="inline-flex items-center justify-center w-8 h-8 text-orange-600 dark:text-orange-400 bg-orange-100 dark:bg-orange-900 rounded-lg hover:bg-orange-200 dark:hover:bg-orange-800 transition-all duration-200 hover:scale-105">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                            </svg>
                                                        </button>

                                                        <!-- Bouton Supprimer -->
                                                        <button wire:click="delete({{ $p->id }})"
                                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer {{ $p->nom }} ?')"
                                                            class="inline-flex items-center justify-center w-8 h-8 text-red-600 dark:text-red-400 bg-red-100 dark:bg-red-900 rounded-lg hover:bg-red-200 dark:hover:bg-red-800 transition-all duration-200 hover:scale-105">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-4 py-12 text-center">
                                                <div class="text-gray-500 dark:text-gray-400">
                                                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                    <p class="text-sm font-medium">Aucun partenaire trouvé</p>
                                                    @if ($search || $filterType !== 'all')
                                                        <p class="text-xs mt-1">Essayez de modifier vos critères de
                                                            recherche</p>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination desktop -->
                        <div
                            class="bg-gray-50 dark:bg-gray-900 px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                            {{ $rows->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Inclusion des modales (commun) -->
    @include('livewire.partenaire.modales.partenaires-modale')
</div>
