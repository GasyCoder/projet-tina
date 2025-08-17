    {{-- Section infos partenaire --}}
    <div class="p-4 sm:p-6 border-b border-gray-200 dark:border-gray-700">
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-4">
        {{-- Tél --}}
        <div class="bg-gray-50/80 dark:bg-gray-900/60 p-3 rounded-lg border border-gray-200 dark:border-gray-700">
            <div class="flex items-center space-x-2">
                <div class="p-1.5 rounded-lg bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.95.68l1.5 4.5a1 1 0 01-.5 1.21l-2.26 1.13a11.04 11.04 0 005.52 5.52l1.13-2.26a1 1 0 011.21-.5l4.5 1.5a1 1 0 01.68.95V19a2 2 0 01-2 2H18C9.72 21 3 14.28 3 6V5z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-[11px] font-semibold text-gray-500 dark:text-gray-400 uppercase">Téléphone</p>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                        {{ $partenaire->telephone ?? 'Non renseigné' }}
                    </p>
                </div>
            </div>
        </div>
        {{-- Adresse --}}
        <div class="bg-gray-50/80 dark:bg-gray-900/60 p-3 rounded-lg border border-gray-200 dark:border-gray-700">
            <div class="flex items-center space-x-2">
                <div class="p-1.5 rounded-lg bg-purple-100 dark:bg-purple-900 text-purple-600 dark:text-purple-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z"/>
                        <circle cx="12" cy="10" r="3" />
                    </svg>
                </div>
                <div>
                    <p class="text-[11px] font-semibold text-gray-500 dark:text-gray-400 uppercase">Adresse</p>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                        {{ \Illuminate\Support\Str::limit($partenaire->adresse ?? 'Non renseigné', 48) }}
                    </p>
                </div>
            </div>
        </div>
        {{-- Créé le --}}
        <div class="bg-gray-50/80 dark:bg-gray-900/60 p-3 rounded-lg border border-gray-200 dark:border-gray-700">
            <div class="flex items-center space-x-2">
                <div class="p-1.5 rounded-lg bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-[11px] font-semibold text-gray-500 dark:text-gray-400 uppercase">Créé le</p>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                        {{ $partenaire->created_at->format('d/m/Y') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    @if($partenaire->has_unpaid_debt)
        <div class="bg-amber-50 dark:bg-amber-900/20 border-l-4 border-amber-400 dark:border-amber-600 p-3 rounded-r-lg">
            <div class="flex items-center">
                <svg class="w-4 h-4 text-amber-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M8.257 3.099c.766-1.36 2.72-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zM10 6a1 1 0 00-1 1v3a1 1 0 002 0V7a1 1 0 00-1-1z"/>
                </svg>
                <p class="text-xs text-amber-800 dark:text-amber-200">Ce partenaire a un solde impayé &gt; 30 jours.</p>
            </div>
        </div>
    @endif
    </div>