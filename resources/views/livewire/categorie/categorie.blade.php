<div class="min-h-screen bg-gray-100 dark:bg-gray-900 pt-20 pb-6 md:px-6">
    {{-- Header --}}
    <div class="px-3 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="flex items-center justify-between">
                <h1 class="text-base sm:text-2xl lg:text-3xl font-semibold text-gray-900 dark:text-gray-100 tracking-tight">
                    Catégories Comptables
                </h1>

                @if (auth()->user()->isAdmin())
                <button wire:click="openModal"
                        class="inline-flex items-center px-4 py-2.5 bg-indigo-600 dark:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Nouvelle catégorie
                </button>
                @endif
            </div>
        </div>
    </div>

    <div class="px-0 sm:px-6 lg:px-8 py-4 sm:py-6">
        <div class="max-w-7xl mx-auto">

            {{-- Alerts --}}
            @if (session()->has('success'))
                <div class="mb-4 mx-3 sm:mx-0 bg-green-50 dark:bg-green-900/20 border-l-4 border-green-400 dark:border-green-600 p-4 rounded-r-lg">
                    <p class="text-green-800 dark:text-green-200 text-sm font-medium">{{ session('success') }}</p>
                </div>
            @endif
            @if (session()->has('error'))
                <div class="mb-4 mx-3 sm:mx-0 bg-red-50 dark:bg-red-900/20 border-l-4 border-red-400 dark:border-red-600 p-4 rounded-r-lg">
                    <p class="text-red-800 dark:text-red-200 text-sm font-medium">{{ session('error') }}</p>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-xl overflow-hidden">

                {{-- Filtres (recherche uniquement) --}}
                <div class="p-3 sm:p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex flex-col sm:flex-row gap-4">
                        <div class="flex-1">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Rechercher une catégorie</label>
                            <div class="relative">
                                <input type="text"
                                       wire:model.live.debounce.300ms="search"
                                       placeholder="Code, nom ou description..."
                                       class="w-full pl-10 pr-3 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400">
                                <svg class="absolute left-3 top-3 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Mobile --}}
                <div class="md:hidden">
                    <div class="p-3">
                        <div class="grid grid-cols-1 gap-3">
                            @forelse($rows as $categorie)
                                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 hover:shadow-md transition">
                                    <div class="flex items-center justify-between mb-3">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-12 h-12 rounded-lg bg-gray-100 dark:bg-gray-900 flex items-center justify-center">
                                                <span class="text-lg font-bold text-gray-700 dark:text-gray-300">
                                                    {{ $categorie->code_comptable }}
                                                </span>
                                            </div>
                                            <div>
                                                <h3 class="font-semibold text-gray-900 dark:text-white">{{ $categorie->nom }}</h3>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $categorie->transactions_count }} transaction(s)</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-lg font-bold text-gray-900 dark:text-gray-100">
                                                {{ number_format($categorie->budget, 0, ',', ' ') }} Ar
                                            </p>
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $categorie->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
                                                {{ $categorie->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between">
                                        <button wire:click="show({{ $categorie->id }})"
                                                class="flex-1 mr-2 px-3 py-2 text-sm bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400 rounded-lg hover:bg-blue-200 dark:hover:bg-blue-800">
                                            Voir détails
                                        </button>
                                        @if (auth()->user()->isAdmin())
                                            <button wire:click="editModal({{ $categorie->id }})"
                                                    class="px-3 py-2 text-sm bg-orange-100 dark:bg-orange-900 text-orange-600 dark:text-orange-400 rounded-lg hover:bg-orange-200 dark:hover:bg-orange-800">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8">
                                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Aucune catégorie trouvée</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Desktop --}}
                <div class="hidden md:block">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase">Code</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase">Catégorie</th>
                                    <th class="px-4 py-3 text-right text-xs font-bold text-gray-700 dark:text-gray-300 uppercase">Budget Max.</th>
                                    <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 dark:text-gray-300 uppercase">Statut</th>
                                    <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 dark:text-gray-300 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($rows as $categorie)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors cursor-pointer"
                                        wire:click="show({{ $categorie->id }})">
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <div class="w-10 h-10 rounded-lg bg-gray-100 dark:bg-gray-900 flex items-center justify-center">
                                                <span class="text-sm font-bold text-gray-700 dark:text-gray-300">{{ $categorie->code_comptable }}</span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <div>
                                                <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ $categorie->nom }}</div>
                                                @if($categorie->description)
                                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                                        {{ \Illuminate\Support\Str::limit($categorie->description, 50) }}
                                                    </div>
                                                @endif
                                                <div class="text-xs text-gray-400">{{ $categorie->transactions_count }} transaction(s)</div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-right">
                                            <div class="text-sm font-bold text-gray-900 dark:text-gray-100">
                                                {{ number_format($categorie->budget, 0, ',', ' ') }} Ar
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-center">
                                            <button wire:click.stop="toggle({{ $categorie->id }})"
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold transition-all duration-200 hover:scale-105 {{ $categorie->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300 hover:bg-green-200 dark:hover:bg-green-800' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300 hover:bg-red-200 dark:hover:bg-red-800' }}">
                                                <span class="w-2 h-2 mr-1.5 rounded-full {{ $categorie->is_active ? 'bg-green-400' : 'bg-red-400' }}"></span>
                                                {{ $categorie->is_active ? 'Active' : 'Inactive' }}
                                            </button>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-center">
                                            <div class="flex items-center justify-center space-x-2">
                                                <button wire:click.stop="show({{ $categorie->id }})"
                                                        class="inline-flex items-center justify-center w-8 h-8 text-blue-600 dark:text-blue-400 bg-blue-100 dark:bg-blue-900 rounded-lg hover:bg-blue-200 dark:hover:bg-blue-800">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </button>
                                                @if (auth()->user()->isAdmin())
                                                    <button wire:click.stop="editModal({{ $categorie->id }})"
                                                            class="inline-flex items-center justify-center w-8 h-8 text-orange-600 dark:text-orange-400 bg-orange-100 dark:bg-orange-900 rounded-lg hover:bg-orange-200 dark:hover:bg-orange-800">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                    </button>
                                                    <button wire:click.stop="delete({{ $categorie->id }})"
                                                            onclick="return confirm('Supprimer cette catégorie ?')"
                                                            class="inline-flex items-center justify-center w-8 h-8 text-red-600 dark:text-red-400 bg-red-100 dark:bg-red-900 rounded-lg hover:bg-red-200 dark:hover:bg-red-800">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-12 text-center">
                                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                            </svg>
                                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Aucune catégorie trouvée</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-900 px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                        {{ $rows->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modales --}}
    @include('livewire.categorie.modales.categories-modal')
</div>
