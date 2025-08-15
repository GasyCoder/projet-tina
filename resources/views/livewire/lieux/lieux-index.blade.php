<div class="min-h-screen bg-gray-50 dark:bg-gray-900 pt-20 pb-6 px-4 md:px-6">
    <div class="space-y-6 max-w-7xl mx-auto">
        <!-- En-tête -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Lieux</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400">Gérez les origines, dépôts, magasins et boutiques</p>
            </div>
            <div class="flex items-center gap-4">
                <!-- Toggle Dark/Light Mode -->
                <button id="theme-toggle" type="button" class="text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg text-sm p-2">
                    <svg id="theme-toggle-dark-icon" class="w-5 h-5 hidden" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                    <svg id="theme-toggle-light-icon" class="w-5 h-5 hidden" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" clip-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zM6.464 15.95l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414z"/></svg>
                </button>

                <button wire:click="create" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    Nouveau lieu
                </button>
            </div>
        </div>

        <!-- Alertes -->
        @if (session()->has('success'))
            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 rounded-md p-4">
                <div class="flex">
                    <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/></svg>
                    <p class="ml-3 text-sm text-green-800 dark:text-green-200">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <!-- Stats rapides -->
        <div class="grid grid-cols-2 md:grid-cols-5 gap-3 md:gap-4">
            <!-- Total -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 md:p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs md:text-sm font-medium text-gray-600 dark:text-gray-400">Total</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $stats['total'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <!-- Origines -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 md:p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 dark:bg-green-900/30 rounded-lg">
                        <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs md:text-sm font-medium text-gray-600 dark:text-gray-400">Origines</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $stats['origines'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <!-- Magasins -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 md:p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-purple-100 dark:bg-purple-900/30 rounded-lg">
                        <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs md:text-sm font-medium text-gray-600 dark:text-gray-400">Magasins</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $stats['magasins'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <!-- Boutiques -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 md:p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M5 7l1 10a2 2 0 002 2h8a2 2 0 002-2L19 7M9 7V5a2 2 0 012-2h2a2 2 0 012 2v2"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs md:text-sm font-medium text-gray-600 dark:text-gray-400">Boutiques</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $stats['boutiques'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <!-- Dépôts -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 md:p-4 col-span-2 md:col-span-1">
                <div class="flex items-center">
                    <div class="p-2 bg-orange-100 dark:bg-orange-900/30 rounded-lg">
                        <svg class="w-5 h-5 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs md:text-sm font-medium text-gray-600 dark:text-gray-400">Dépôts</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $stats['depots'] ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recherche & filtres -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="p-3 md:p-6">
                <div class="grid grid-cols-2 gap-2 sm:flex sm:items-center sm:justify-between sm:gap-4">
                    <!-- Recherche -->
                    <div class="col-span-1 sm:flex-1 sm:max-w-lg">
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input
                                wire:model.live.debounce.300ms="search"
                                type="text"
                                placeholder="Rechercher..."
                                class="pl-10 pr-3 py-2 w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-white text-sm placeholder-gray-500 dark:placeholder-gray-400"
                            >
                        </div>
                    </div>

                    <!-- Filtres -->
                    <div class="col-span-1 flex items-center gap-2 sm:gap-3">
                        <select wire:model.live="filterType" class="w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg px-2 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-white text-sm">
                            <option value="">Tous les types</option>
                            <option value="origine">Origines</option>
                            <option value="depot">Dépôts</option>
                            <option value="magasin">Magasins</option>
                            <option value="boutique">Boutiques</option>
                        </select>
                        <div class="text-sm text-gray-500 dark:text-gray-400 whitespace-nowrap hidden xs:block">
                            {{ $lieux->total() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ✅ tes includes sont bien ici --}}
        @include('livewire.lieux.table-lieux')
        @include('livewire.lieux.modal-lieux')

    </div>
</div>
