<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4">
    <!-- Total chargé -->
    <div class="bg-white rounded-lg shadow p-3 md:p-4">
        <div class="flex items-center">
            <div class="p-1.5 md:p-2 bg-blue-100 rounded-lg flex-shrink-0">
                <svg class="w-4 h-4 md:w-5 md:h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                </svg>
            </div>
            <div class="ml-2 md:ml-3 min-w-0">
                <p class="text-xs md:text-sm font-medium text-gray-600 truncate">Total chargé</p>
                <p class="text-base md:text-lg font-bold text-gray-900">{{ number_format($totalPoidsCharge, 0) }} kg</p>
            </div>
        </div>
    </div>

    <!-- Total déchargé -->
    <div class="bg-white rounded-lg shadow p-3 md:p-4">
        <div class="flex items-center">
            <div class="p-1.5 md:p-2 bg-green-100 rounded-lg flex-shrink-0">
                <svg class="w-4 h-4 md:w-5 md:h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 12l2 2 4-4"/>
                </svg>
            </div>
            <div class="ml-2 md:ml-3 min-w-0">
                <p class="text-xs md:text-sm font-medium text-gray-600 truncate">Total déchargé</p>
                <p class="text-base md:text-lg font-bold text-gray-900">{{ number_format($totalPoidsDecharge, 0) }} kg</p>
            </div>
        </div>
    </div>

    <!-- Écart -->
    <div class="bg-white rounded-lg shadow p-3 md:p-4">
        <div class="flex items-center">
            <div class="p-1.5 md:p-2 {{ $ecartPoids != 0 ? 'bg-red-100' : 'bg-gray-100' }} rounded-lg flex-shrink-0">
                <svg class="w-4 h-4 md:w-5 md:h-5 {{ $ecartPoids != 0 ? 'text-red-600' : 'text-gray-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="ml-2 md:ml-3 min-w-0">
                <p class="text-xs md:text-sm font-medium text-gray-600 truncate">Écart</p>
                <p class="text-base md:text-lg font-bold {{ $ecartPoids != 0 ? 'text-red-600' : 'text-gray-900' }}">
                    {{ $ecartPoids > 0 ? '+' : '' }}{{ number_format($ecartPoids, 0) }} kg
                </p>
            </div>
        </div>
    </div>

    <!-- CA Ventes -->
    <div class="bg-white rounded-lg shadow p-3 md:p-4">
        <div class="flex items-center">
            <div class="p-1.5 md:p-2 bg-yellow-100 rounded-lg flex-shrink-0">
                <svg class="w-4 h-4 md:w-5 md:h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                </svg>
            </div>
            <div class="ml-2 md:ml-3 min-w-0">
                <p class="text-xs md:text-sm font-medium text-gray-600 truncate">CA Ventes</p>
                <p class="text-base md:text-lg font-bold text-gray-900">{{ number_format($totalVentes, 0) }} MGA</p>
            </div>
        </div>
    </div>
</div>