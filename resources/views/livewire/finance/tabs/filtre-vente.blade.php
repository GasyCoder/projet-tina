<!-- Filtres (version allégée) -->
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4 sm:p-6">
  <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

    <!-- Recherche -->
    <div class="md:col-span-1">
      <div class="relative">
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
        <input
          wire:model.live="searchTerm"
          type="text"
          placeholder="Rechercher (référence, objet, vendeur)"
          class="pl-10 w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent"
        >
      </div>
    </div>

    <!-- Période -->
    <div>
      <select
        wire:model.live="filterDate"
        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent"
      >
        <option value="">Toutes les dates</option>
        <option value="today">Aujourd'hui</option>
        <option value="week">Cette semaine</option>
        <option value="month">Ce mois</option>
        <option value="year">Cette année</option>
      </select>
    </div>

    <!-- Type de paiement -->
    <div>
      <div class="flex gap-2">
        <select
          wire:model.live="filterTypePaiement"
          class="flex-1 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent"
        >
          <option value="">Tous les types</option>
          <option value="Principal">💵 Principal</option>
          <option value="MobileMoney">📱 Mobile Money</option>
          <option value="Banque">🏦 Banque</option>
        </select>

        <!-- Reset -->
        <button
          type="button"
          wire:click="clearFilters"
          class="inline-flex items-center px-3 rounded-lg border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700"
          title="Effacer les filtres"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
          </svg>
        </button>
      </div>
    </div>

  </div>
</div>
