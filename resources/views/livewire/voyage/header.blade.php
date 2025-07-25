<div class="bg-white rounded-lg shadow">
    <!-- En-tête -->
    <div class="px-4 py-3 md:px-6 md:py-4 border-b border-gray-200">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div class="flex-1 min-w-0">
                <h1 class="text-xl md:text-2xl font-bold text-gray-900 truncate">{{ $voyage->reference }}</h1>
                <p class="text-xs md:text-sm text-gray-600 truncate">
                    {{ $voyage->date ? $voyage->date->format('d/m/Y') : 'Date non définie' }} - {{ $voyage->origine->nom ?? 'N/A' }}
                </p>
            </div>
            <div class="flex items-center space-x-2 sm:space-x-4">
                <span class="inline-flex px-2 py-0.5 sm:px-3 sm:py-1 text-xs sm:text-sm font-semibold rounded-full 
                    {{ $voyage->statut === 'en_cours' ? 'bg-yellow-100 text-yellow-800' : 
                       ($voyage->statut === 'termine' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
                    {{ ucfirst($voyage->statut) }}
                </span>
                <a href="{{ route('voyages.index') }}" class="text-gray-600 hover:text-gray-900 p-1 rounded-full hover:bg-gray-100">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Infos voyage -->
    <div class="px-4 py-3 md:px-6 md:py-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
            <!-- Véhicule -->
            <div class="flex items-start">
                <div class="p-1.5 sm:p-2 bg-blue-100 rounded-lg mr-2 sm:mr-3 flex-shrink-0">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0M15 17a2 2 0 104 0"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-xs sm:text-sm font-medium text-gray-600">Véhicule</p>
                    <p class="text-base sm:text-lg font-bold text-gray-900 truncate">{{ $voyage->vehicule->immatriculation ?? 'N/A' }}</p>
                    @if($voyage->vehicule)
                        <p class="text-xs sm:text-sm text-gray-500 truncate">{{ $voyage->vehicule->marque }} {{ $voyage->vehicule->modele }}</p>
                    @endif
                </div>
            </div>

            <!-- Chauffeur -->
            <div class="flex items-start">
                <div class="p-1.5 sm:p-2 bg-green-100 rounded-lg mr-2 sm:mr-3 flex-shrink-0">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-xs sm:text-sm font-medium text-gray-600">Chauffeur</p>
                    @if($voyage->vehicule && $voyage->vehicule->chauffeur)
                        <p class="text-base sm:text-lg font-bold text-gray-900 truncate">{{ $voyage->vehicule->chauffeur }}</p>
                    @else
                        <p class="text-base sm:text-lg font-bold text-red-500">Non renseigné</p>
                    @endif
                </div>
            </div>

            <!-- Chargements -->
            <div class="flex items-start">
                <div class="p-1.5 sm:p-2 bg-purple-100 rounded-lg mr-2 sm:mr-3 flex-shrink-0">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-xs sm:text-sm font-medium text-gray-600">Chargements</p>
                    <p class="text-base sm:text-lg font-bold text-gray-900">{{ $voyage->chargements->count() }}</p>
                    <p class="text-xs sm:text-sm text-gray-500">{{ number_format($voyage->chargements->sum('poids_depart_kg'), 0) }} kg</p>
                </div>
            </div>
        </div>
    </div>
</div>