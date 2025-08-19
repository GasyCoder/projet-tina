<div class="min-h-screen bg-gray-100 dark:bg-gray-900 pt-20 pb-6 md:px-6">
    <!-- Header Mobile/Desktop -->
    <div class="px-3 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div
                class="flex flex-row items-center justify-between space-x-3 sm:flex-row sm:items-center sm:justify-between sm:space-x-0">
                <!-- Titre et info catégorie -->
                <div class="flex items-center space-x-4">
                    <a href="{{ route('categories.index') }}" ...>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                    <div class="flex items-center space-x-3">
                        <div
                            class="w-12 h-12 rounded-xl {{ $categorie->type == 'recette' ? 'bg-green-100 dark:bg-green-900' : 'bg-red-100 dark:bg-red-900' }} flex items-center justify-center">
                            <span
                                class="text-lg font-bold {{ $categorie->type == 'recette' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                {{ $categorie->code_comptable }}
                            </span>
                        </div>
                        <div>
                            <h1
                                class="text-base sm:text-xl lg:text-2xl font-semibold text-gray-900 dark:text-gray-100 tracking-tight">
                                {{ $categorie->nom }}
                            </h1>
                            <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 flex items-center">
                                <span
                                    class="w-2 h-2 mr-2 rounded-full {{ $categorie->is_active ? 'bg-green-400' : 'bg-red-400' }}"></span>
                                {{ ucfirst($categorie->type) }} - {{ $categorie->is_active ? 'Active' : 'Inactive' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="flex justify-end space-x-2">
                    <button wire:click="afficherDetailsRapides({{ $categorie->id }})"
                        class="w-8 h-8 sm:w-auto sm:h-auto inline-flex items-center justify-center p-0 sm:px-3 sm:py-2 bg-blue-600 dark:bg-blue-700 text-white text-xs sm:text-sm font-medium rounded-lg shadow-sm hover:bg-blue-700 dark:hover:bg-blue-600 transition-all duration-150">
                        <svg class="w-4 h-4 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span class="hidden sm:inline">Nouvelle transaction</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="px-0 sm:px-6 lg:px-8 py-4 sm:py-6">
        <div class="max-w-7xl mx-auto">
            <!-- Alertes -->
            @if (session()->has('success'))
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

            <!-- Container Principal -->
            <div class="bg-white dark:bg-gray-800 shadow-sm mx-0 sm:mx-0 sm:rounded-xl overflow-hidden">

                <!-- Statistiques de la catégorie -->
                <div class="p-3 sm:p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between mb-3">
                        <h2 class="text-sm sm:text-base font-semibold text-gray-900 dark:text-white flex items-center">
                            <svg class="w-4 h-4 mr-2 text-blue-600 dark:text-blue-400" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Statistiques
                        </h2>
                        <div class="flex items-center space-x-1">
                            <button wire:click="exportTransactions"
                                class="text-xs bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-600 dark:text-gray-300 px-2 py-1 rounded-md flex items-center transition-colors">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Export
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
                        <div
                            class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/50 dark:to-blue-800/30 p-3 rounded-lg border border-blue-200 dark:border-blue-700">
                            <div class="text-center">
                                <p
                                    class="text-xs font-semibold text-blue-700 dark:text-blue-300 uppercase tracking-wide">
                                    Total</p>
                                <p class="text-lg sm:text-xl font-bold text-blue-900 dark:text-blue-100 mt-1">
                                    {{ number_format($statistiques['montant_total'], 0, ',', ' ') }} Ar
                                </p>
                            </div>
                        </div>

                        <div
                            class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/50 dark:to-green-800/30 p-3 rounded-lg border border-green-200 dark:border-green-700">
                            <div class="text-center">
                                <p
                                    class="text-xs font-semibold text-green-700 dark:text-green-300 uppercase tracking-wide">
                                    Ce mois</p>
                                <p class="text-lg sm:text-xl font-bold text-green-900 dark:text-green-100 mt-1">
                                    {{ number_format($statistiques['montant_mois'], 0, ',', ' ') }} Ar
                                </p>
                            </div>
                        </div>

                        <div
                            class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/50 dark:to-purple-800/30 p-3 rounded-lg border border-purple-200 dark:border-purple-700">
                            <div class="text-center">
                                <p
                                    class="text-xs font-semibold text-purple-700 dark:text-purple-300 uppercase tracking-wide">
                                    Transactions</p>
                                <p class="text-lg sm:text-xl font-bold text-purple-900 dark:text-purple-100 mt-1">
                                    {{ $statistiques['total_transactions'] }}
                                </p>
                            </div>
                        </div>

                        <div
                            class="bg-gradient-to-br from-yellow-50 to-yellow-100 dark:from-yellow-900/50 dark:to-yellow-800/30 p-3 rounded-lg border border-yellow-200 dark:border-yellow-700">
                            <div class="text-center">
                                <p
                                    class="text-xs font-semibold text-yellow-700 dark:text-yellow-300 uppercase tracking-wide">
                                    Moyenne</p>
                                <p class="text-lg sm:text-xl font-bold text-yellow-900 dark:text-yellow-100 mt-1">
                                    {{ number_format($statistiques['moyenne_transaction'], 0, ',', ' ') }} Ar
                                </p>
                            </div>
                        </div>

                        <div
                            class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600 p-3 rounded-lg border border-gray-200 dark:border-gray-500 col-span-2 sm:col-span-1">
                            <div class="text-center">
                                <p
                                    class="text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wide">
                                    Dernière</p>
                                <p class="text-lg sm:text-xl font-bold text-gray-900 dark:text-gray-100 mt-1">
                                    {{ $statistiques['derniere_transaction'] ?? 'Aucune' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filtres des transactions -->
                <div class="p-3 sm:p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex flex-col sm:flex-row gap-4 flex-1">
                            <div class="flex-1">
                                <div class="relative">
                                    <input type="text" wire:model.live.debounce.300ms="search"
                                        placeholder="Rechercher une transaction..."
                                        class="w-full pl-8 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm">
                                    <svg class="absolute left-2.5 top-2.5 h-4 w-4 text-gray-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                            </div>

                            <div class="flex gap-2">
                                 <div class="flex bg-gray-100 dark:bg-gray-700 p-1 rounded-lg">
                                <button wire:click="filterTransactions('all')" class="px-2 py-1 text-xs rounded-md {{ $filter === 'all' ? 'bg-white dark:bg-gray-600 shadow-sm text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-gray-100' }}">
                                    Toutes
                                </button>
                                <button wire:click="filterTransactions('entrer')" class="px-2 py-1 text-xs rounded-md {{ $filter === 'entrer' ? 'bg-white dark:bg-gray-600 shadow-sm text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-gray-100' }}">
                                    Entrer
                                </button>
                                <button wire:click="filterTransactions('sortie')" class="px-2 py-1 text-xs rounded-md {{ $filter === 'sortie' ? 'bg-white dark:bg-gray-600 shadow-sm text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-gray-100' }}">
                                      Sortie
                                </button>
                            </div>

                                <select wire:model.live="periode"
                                    class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm">
                                    <option value="all">Toute période</option>
                                    <option value="mois_courant">Ce mois</option>
                                    <option value="annee_courante">Cette année</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Liste des transactions -->
                <div class="p-3 sm:p-6">
                    <!-- En-tête du tableau (desktop) -->
                    <div
                        class="hidden sm:grid grid-cols-12 gap-3 mb-3 px-3 py-2 bg-gray-50 dark:bg-gray-900 rounded-lg text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        <div class="col-span-2">Date</div>
                        <div class="col-span-2">Référence</div>
                        <div class="col-span-3">Description</div>
                        <div class="col-span-2">Partenaire</div>
                        <div class="col-span-2 text-right">Montant</div>
                        <div class="col-span-1 text-center">Actions</div>
                    </div>

                    <!-- Liste des transactions -->
                    <div class="space-y-2">
                        @forelse($transactions as $transaction)
                            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-3 hover:shadow-md transition-all duration-200 cursor-pointer"
                                wire:click="showTransactionDetail({{ $transaction->id }})">

                                <!-- Version mobile -->
                                <div class="sm:hidden">
                                    <div class="flex justify-between items-start mb-2">
                                        <div>
                                            <h3 class="font-medium text-gray-900 dark:text-white text-sm">
                                                {{ $transaction->description }}</h3>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $transaction->reference }} • {{ $transaction->date_formattee }}</p>
                                        </div>
                                        <span
                                            class="text-sm font-bold {{ $categorie->type == 'recette' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                            {{ $transaction->montant_formate }}
                                        </span>
                                    </div>
                                    @if ($transaction->partenaire)
                                        <p class="text-xs text-gray-600 dark:text-gray-400">
                                            {{ $transaction->partenaire->nom }}</p>
                                    @endif
                                </div>

                                <!-- Version desktop -->
                                <div class="hidden sm:grid grid-cols-12 gap-3 items-center">
                                    <div class="col-span-2 text-sm text-gray-500 dark:text-gray-400">
                                        {{ $transaction->date_formattee }}
                                    </div>
                                    <div class="col-span-2 text-sm font-mono text-gray-900 dark:text-white">
                                        {{ $transaction->reference }}
                                    </div>
                                    <div class="col-span-3 text-sm text-gray-900 dark:text-white">
                                        {{ $transaction->description }}
                                        @if ($transaction->statut === 'sortie')
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300 ml-2">
                                                En attente
                                            </span>
                                        @endif
                                    </div>
                                    <div class="col-span-2 text-sm text-gray-500 dark:text-gray-400">
                                        {{ $transaction->partenaire?->nom ?? '-' }}
                                    </div>
                                    <div
                                        class="col-span-2 text-right font-medium text-sm {{ $categorie->type == 'recette' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                        {{ $transaction->montant_formate }}
                                    </div>
                                    <div class="col-span-1 text-center">
                                        <button wire:click.stop="showTransactionDetail({{ $transaction->id }})"
                                            class="inline-flex items-center justify-center w-8 h-8 text-blue-600 dark:text-blue-400 bg-blue-100 dark:bg-blue-900 rounded-lg hover:bg-blue-200 dark:hover:bg-blue-800 transition-all duration-200">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Aucune transaction
                                    trouvée</p>
                                <p class="text-xs text-gray-400 mt-1">
                                    @if ($search || $filter !== 'all' || $periode !== 'all')
                                        Modifiez vos critères de recherche
                                    @else
                                        Créez votre première transaction pour cette catégorie
                                    @endif
                                </p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $transactions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modales -->
    @include('livewire.categorie.modales.categories-modal')
</div>
