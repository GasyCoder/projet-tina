<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4">
    <!-- Total chargé -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow dark:shadow-gray-900/20 p-3 md:p-4">
        <div class="flex items-center">
            <div class="p-1.5 md:p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex-shrink-0">
                <svg class="w-4 h-4 md:w-5 md:h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                </svg>
            </div>
            <div class="ml-2 md:ml-3 min-w-0">
                <p class="text-xs md:text-sm font-medium text-gray-600 dark:text-gray-400 truncate">Total chargé</p>
                <p class="text-base md:text-lg font-bold text-gray-900 dark:text-white">{{ number_format($totalPoidsCharge, 0) }} kg</p>
                <p class="text-xs text-gray-500 dark:text-gray-400">{{ number_format($totalSacsCharges, 1) }} sacs</p>
            </div>
        </div>
    </div>

    <!-- Total déchargé -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow dark:shadow-gray-900/20 p-3 md:p-4">
        <div class="flex items-center">
            <div class="p-1.5 md:p-2 bg-green-100 dark:bg-green-900/30 rounded-lg flex-shrink-0">
                <svg class="w-4 h-4 md:w-5 md:h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 12l2 2 4-4"/>
                </svg>
            </div>
            <div class="ml-2 md:ml-3 min-w-0">
                <p class="text-xs md:text-sm font-medium text-gray-600 dark:text-gray-400 truncate">Total déchargé</p>
                <p class="text-base md:text-lg font-bold text-gray-900 dark:text-white">{{ number_format($totalPoidsDecharge, 0) }} kg</p>
                <p class="text-xs text-gray-500 dark:text-gray-400">{{ number_format($totalSacsDecharges, 1) }} sacs</p>
            </div>
        </div>
    </div>

    <!-- Écart -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow dark:shadow-gray-900/20 p-3 md:p-4">
        <div class="flex items-center">
            <div class="p-1.5 md:p-2 {{ ($ecartPoids != 0 || $ecartSacs != 0) ? 'bg-red-100 dark:bg-red-900/30' : 'bg-gray-100 dark:bg-gray-700' }} rounded-lg flex-shrink-0">
                <svg class="w-4 h-4 md:w-5 md:h-5 {{ ($ecartPoids != 0 || $ecartSacs != 0) ? 'text-red-600 dark:text-red-400' : 'text-gray-600 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="ml-2 md:ml-3 min-w-0">
                <p class="text-xs md:text-sm font-medium text-gray-600 dark:text-gray-400 truncate">Écart</p>
                <p class="text-base md:text-lg font-bold {{ ($ecartPoids != 0 || $ecartSacs != 0) ? 'text-red-600 dark:text-red-400' : 'text-gray-900 dark:text-white' }}">
                    {{ $ecartPoids > 0 ? '+' : '' }}{{ number_format($ecartPoids, 0) }} kg
                </p>
                <p class="text-xs {{ ($ecartPoids != 0 || $ecartSacs != 0) ? 'text-red-500 dark:text-red-300' : 'text-gray-500 dark:text-gray-400' }}">
                    {{ $ecartSacs > 0 ? '+' : '' }}{{ number_format($ecartSacs, 1) }} sacs
                </p>
            </div>
        </div>
    </div>

    <!-- Progression / Opérations -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow dark:shadow-gray-900/20 p-3 md:p-4">
        <div class="flex items-center">
            <div class="p-1.5 md:p-2 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex-shrink-0">
                <svg class="w-4 h-4 md:w-5 md:h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="ml-2 md:ml-3 min-w-0">
                <p class="text-xs md:text-sm font-medium text-gray-600 dark:text-gray-400 truncate">Progression</p>
                <p class="text-base md:text-lg font-bold text-gray-900 dark:text-white">{{ number_format($pourcentageCompletion, 1) }}%</p>
                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $nombreDechargements }}/{{ $nombreChargements }} opérations</p>
            </div>
        </div>
    </div>
</div>

<!-- Barre de progression visuelle (optionnelle) -->
@if($totalPoidsCharge > 0)
<div class="mt-4 bg-white dark:bg-gray-800 rounded-lg shadow dark:shadow-gray-900/20 p-3 md:p-4">
    <div class="flex items-center justify-between mb-2">
        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Progression du déchargement</span>
        <span class="text-sm text-gray-500 dark:text-gray-400">{{ number_format($pourcentageCompletion, 1) }}%</span>
    </div>
    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
        <div class="bg-blue-600 dark:bg-blue-500 h-2 rounded-full transition-all duration-300" 
             style="width: {{ min(100, $pourcentageCompletion) }}%"></div>
    </div>
    <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400 mt-1">
        <span>{{ number_format($totalPoidsDecharge, 0) }} kg déchargés</span>
        <span>{{ number_format($totalPoidsCharge, 0) }} kg total</span>
    </div>
</div>
@endif