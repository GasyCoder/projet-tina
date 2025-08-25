{{-- Statistiques financières --}}
<div class="p-4 sm:p-6 border-b border-gray-200 dark:border-gray-700">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between mb-4">
        <h2 class="text-base font-semibold text-gray-900 dark:text-white flex items-center">
            <svg class="w-5 h-5 mr-2 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/><path d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9z"/>
            </svg>
            Résumé Financier
        </h2>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        {{-- 1. Budget Initial / Fond Entré --}}
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/40 dark:to-blue-800/30 p-4 rounded-lg border border-blue-200 dark:border-blue-700">
            <p class="text-[11px] font-bold text-blue-700 dark:text-blue-300 uppercase tracking-wider">Budget Initial</p>
            <p class="text-2xl font-extrabold text-blue-900 dark:text-blue-100 mt-1">
                {{ number_format((float)$statistiques['budget_initial'], 0, ',', ' ') }} Ar
            </p>
            <p class="text-xs text-blue-600 dark:text-blue-400 mt-1">
                Total des entrées
            </p>
        </div>

        {{-- 2. Fond Utilisé / Sorties --}}
        <div class="bg-gradient-to-br from-orange-50 to-orange-100 dark:from-orange-900/40 dark:to-orange-800/30 p-4 rounded-lg border border-orange-200 dark:border-orange-700">
            <p class="text-[11px] font-bold text-orange-700 dark:text-orange-300 uppercase tracking-wider">Fond Utilisé</p>
            <p class="text-2xl font-extrabold text-orange-900 dark:text-orange-100 mt-1">
                {{ number_format((float)$statistiques['fond_utilise'], 0, ',', ' ') }} Ar
            </p>
            <p class="text-xs text-orange-600 dark:text-orange-400 mt-1">
                Sorties effectuées
            </p>
        </div>

        {{-- 3. Solde Disponible --}}
        <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 dark:from-emerald-900/40 dark:to-emerald-800/30 p-4 rounded-lg border border-emerald-200 dark:border-emerald-700">
            <p class="text-[11px] font-bold text-emerald-700 dark:text-emerald-300 uppercase tracking-wider">Solde Disponible</p>
            <p class="text-2xl font-extrabold {{ (float)$statistiques['solde_disponible'] >= 0 ? 'text-emerald-900 dark:text-emerald-100' : 'text-red-600 dark:text-red-400' }} mt-1">
                {{ number_format((float)$statistiques['solde_disponible'], 0, ',', ' ') }} Ar
            </p>
            <p class="text-xs text-emerald-600 dark:text-emerald-400 mt-1">
                Reste à utiliser
            </p>
        </div>
    </div>
</div>