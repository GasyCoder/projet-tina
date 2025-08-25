<!-- Table -->
<div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    {{-- Nom --}}
                    <th wire:click="sortBy('nom')" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600">
                        <div class="flex items-center gap-1">
                            <span>Nom</span>
                            @if($sortField === 'nom')
                                <svg class="w-4 h-4 {{ $sortDirection === 'asc' ? 'rotate-0' : 'rotate-180' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M3 4h14M3 10h10M3 16h8"/>
                                </svg>
                            @endif
                        </div>
                    </th>

                    {{-- Type --}}
                    <th wire:click="sortBy('type')" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600 hidden sm:table-cell">
                        <div class="flex items-center gap-1">
                            <span>Type</span>
                            @if($sortField === 'type')
                                <svg class="w-4 h-4 {{ $sortDirection === 'asc' ? 'rotate-0' : 'rotate-180' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M3 4h14M3 10h10M3 16h8"/>
                                </svg>
                            @endif
                        </div>
                    </th>

                    {{-- Région --}}
                    <th wire:click="sortBy('region')" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600 hidden md:table-cell">
                        <div class="flex items-center gap-1">
                            <span>Région</span>
                            @if($sortField === 'region')
                                <svg class="w-4 h-4 {{ $sortDirection === 'asc' ? 'rotate-0' : 'rotate-180' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M3 4h14M3 10h10M3 16h8"/>
                                </svg>
                            @endif
                        </div>
                    </th>

                    {{-- Téléphone --}}
                    <th wire:click="sortBy('telephone')" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600">
                        <div class="flex items-center gap-1">
                            <span>Téléphone</span>
                            @if($sortField === 'telephone')
                                <svg class="w-4 h-4 {{ $sortDirection === 'asc' ? 'rotate-0' : 'rotate-180' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M3 4h14M3 10h10M3 16h8"/>
                                </svg>
                            @endif
                        </div>
                    </th>

                    {{-- Adresse --}}
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider hidden lg:table-cell">
                        Adresse
                    </th>

                    {{-- Statut --}}
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Statut
                    </th>

                    {{-- Actions --}}
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>

            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($lieux as $lieu)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        {{-- Nom + pictogramme selon type --}}
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                @php
                                    // Couleurs/icônes selon type
                                    $map = [
                                        'origine' => ['bg' => 'bg-green-100 dark:bg-green-900/30', 'text' => 'text-green-600 dark:text-green-400', 'path' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                        'depot'   => ['bg' => 'bg-orange-100 dark:bg-orange-900/30', 'text' => 'text-orange-600 dark:text-orange-400', 'path' => 'M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z'],
                                        'magasin' => ['bg' => 'bg-purple-100 dark:bg-purple-900/30', 'text' => 'text-purple-600 dark:text-purple-400', 'path' => 'M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z'],
                                        'boutique'=> ['bg' => 'bg-blue-100 dark:bg-blue-900/30', 'text' => 'text-blue-600 dark:text-blue-400', 'path' => 'M3 7h18M5 7l1 10a2 2 0 002 2h8a2 2 0 002-2L19 7M9 7V5a2 2 0 012-2h2a2 2 0 012 2v2'],
                                    ];
                                    $cfg = $map[$lieu->type] ?? $map['depot'];
                                @endphp
                                <div class="p-1 rounded mr-3 {{ $cfg['bg'] }}">
                                    <svg class="w-4 h-4 {{ $cfg['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $cfg['path'] }}"/>
                                    </svg>
                                </div>

                                <div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $lieu->nom }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 sm:hidden">
                                        {{ ucfirst($lieu->type) }} • {{ $lieu->region ?: '-' }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        {{-- Type (badge) --}}
                        <td class="px-4 py-4 whitespace-nowrap hidden sm:table-cell">
                            @php
                                $badgeClass = match($lieu->type) {
                                    'origine' => 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400',
                                    'depot'   => 'bg-orange-100 dark:bg-orange-900/30 text-orange-800 dark:text-orange-400',
                                    'magasin' => 'bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-400',
                                    'boutique'=> 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400',
                                    default   => 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300',
                                };
                            @endphp
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $badgeClass }}">
                                {{ ucfirst($lieu->type) }}
                            </span>
                        </td>

                        {{-- Région --}}
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white hidden md:table-cell">
                            {{ $lieu->region ?: '-' }}
                        </td>

                        {{-- Téléphone --}}
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            <div class="max-w-xs truncate">
                                {{ $lieu->telephone ?: '-' }}
                            </div>
                        </td>

                        {{-- Adresse --}}
                        <td class="px-4 py-4 text-sm text-gray-900 dark:text-white hidden lg:table-cell">
                            <div class="max-w-xs truncate">
                                {{ $lieu->adresse ?: '-' }}
                            </div>
                        </td>

                        {{-- Statut --}}
                        <td class="px-4 py-4 whitespace-nowrap">
                            <button wire:click="toggleActif({{ $lieu->id }})"
                                    class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                    {{ $lieu->actif ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400' : 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400' }}">
                                {{ $lieu->actif ? 'Actif' : 'Inactif' }}
                            </button>
                        </td>

                        {{-- Actions --}}
                        <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end gap-2">
                                <button wire:click="edit({{ $lieu->id }})" class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300" title="Modifier">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                <button
                                    wire:click="delete({{ $lieu->id }})"
                                    wire:confirm="Êtes-vous sûr de vouloir supprimer ce lieu ?"
                                    class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300" title="Supprimer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="text-gray-500 dark:text-gray-400">
                                <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <p class="mt-4 text-lg font-medium text-gray-900 dark:text-white">Aucun lieu trouvé</p>
                                <p class="mt-2">Commencez par créer votre premier lieu</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($lieux->hasPages())
        <div class="bg-white dark:bg-gray-800 px-4 py-3 border-t border-gray-200 dark:border-gray-700 sm:px-6">
            {{ $lieux->links() }}
        </div>
    @endif
</div>
