<div class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $voyage->reference }}</h1>
                <p class="text-sm text-gray-600">
                    {{ $voyage->date ? $voyage->date->format('d/m/Y') : 'Date non définie' }} - {{ $voyage->origine->nom ?? 'N/A' }}
                </p>
            </div>
            <div class="flex items-center space-x-4">
                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full 
                    {{ $voyage->statut === 'en_cours' ? 'bg-yellow-100 text-yellow-800' : 
                       ($voyage->statut === 'termine' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
                    {{ ucfirst($voyage->statut) }}
                </span>
                <a href="{{ route('voyages.index') }}" class="text-gray-600 hover:text-gray-900">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Infos voyage -->
    <div class="px-6 py-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg mr-3">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0M15 17a2 2 0 104 0"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Véhicule</p>
                    <p class="text-lg font-bold text-gray-900">{{ $voyage->vehicule->immatriculation ?? 'N/A' }}</p>
                    @if($voyage->vehicule)
                        <p class="text-sm text-gray-500">{{ $voyage->vehicule->marque }} {{ $voyage->vehicule->modele }}</p>
                    @endif
                </div>
            </div>

            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg mr-3">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Chauffeur</p>
                    @if($voyage->chauffeur)
                        <p class="text-lg font-bold text-gray-900">{{ $voyage->chauffeur->name }}</p>
                        @if($voyage->chauffeur->code)
                            <p class="text-sm text-gray-500">Code: {{ $voyage->chauffeur->code }}</p>
                        @endif
                    @else
                        <p class="text-lg font-bold text-red-500">Non renseigné</p>
                    @endif
                </div>
            </div>

            <div class="flex items-center">
                <div class="p-2 bg-purple-100 rounded-lg mr-3">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Chargements</p>
                    <p class="text-lg font-bold text-gray-900">{{ $voyage->chargements->count() }}</p>
                    <p class="text-sm text-gray-500">{{ number_format($voyage->chargements->sum('poids_depart_kg'), 0) }} kg</p>
                </div>
            </div>
        </div>
    </div>
</div>