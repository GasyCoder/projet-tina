<div class="min-h-screen bg-gray-50 dark:bg-gray-900 pt-20 pb-6 px-4 md:px-6">
    <div class="space-y-6 max-w-7xl mx-auto">
        <!-- En-tête -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Utilisateurs</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400">Gérez les chauffeurs, propriétaires, clients et équipe</p>
            </div>
            <button wire:click="create" class="inline-flex items-center px-4 py-2 bg-blue-600 dark:bg-blue-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 dark:hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Nouvel utilisateur
            </button>
        </div>

        <!-- Alertes -->
        @if (session()->has('success'))
            <div class="bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 rounded-md p-4">
                <div class="flex">
                    <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                    </svg>
                    <p class="ml-3 text-sm text-green-800 dark:text-green-200">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-md p-4">
                <div class="flex">
                    <svg class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"/>
                    </svg>
                    <p class="ml-3 text-sm text-red-800 dark:text-red-200">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <!-- Stats rapides -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-7 gap-3 md:gap-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 md:p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-xs md:text-sm font-medium text-gray-600 dark:text-gray-400">Total</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $stats['total'] }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 md:p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 dark:bg-green-900/30 rounded-lg">🚛</div>
                    <div class="ml-3">
                        <p class="text-xs md:text-sm font-medium text-gray-600 dark:text-gray-400">Chauffeurs</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $stats['chauffeurs'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 md:p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-purple-100 dark:bg-purple-900/30 rounded-lg">👑</div>
                    <div class="ml-3">
                        <p class="text-xs md:text-sm font-medium text-gray-600 dark:text-gray-400">Propriétaires</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $stats['proprietaires'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 md:p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg">👉</div>
                    <div class="ml-3">
                        <p class="text-xs md:text-sm font-medium text-gray-600 dark:text-gray-400">Pointeurs</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $stats['pointeurs'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 md:p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-orange-100 dark:bg-orange-900/30 rounded-lg">🏪</div>
                    <div class="ml-3">
                        <p class="text-xs md:text-sm font-medium text-gray-600 dark:text-gray-400">Clients</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $stats['clients'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 md:p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg">📦</div>
                    <div class="ml-3">
                        <p class="text-xs md:text-sm font-medium text-gray-600 dark:text-gray-400">Chargeurs</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $stats['chargeurs'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 md:p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-red-100 dark:bg-red-900/30 rounded-lg">⚡</div>
                    <div class="ml-3">
                        <p class="text-xs md:text-sm font-medium text-gray-600 dark:text-gray-400">Admins</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $stats['admins'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recherche et filtres -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700">
            <div class="p-4 md:p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
                    <div class="flex-1 max-w-lg">
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input 
                                wire:model.live.debounce.300ms="search"
                                type="text"
                                placeholder="Rechercher par nom, email, code..."
                                class="pl-10 pr-4 py-2 w-full border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white"
                            >
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3">
                        <select wire:model.live="filterType" class="border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white">
                            <option value="">Tous types</option>
                            <option value="chauffeur">Chauffeurs</option>
                            <option value="proprietaire">Propriétaires</option>
                            <option value="pointeur">Pointeurs</option>
                            <option value="client">Clients</option>
                            <option value="chargeur">Chargeurs</option>
                            <option value="admin">Admins</option>
                        </select>

                        <select wire:model.live="filterActif" class="border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white">
                            <option value="">Tous statuts</option>
                            <option value="1">Actifs</option>
                            <option value="0">Inactifs</option>
                        </select>
                        
                        <div class="text-sm text-gray-500 dark:text-gray-400 whitespace-nowrap">
                            {{ $users->total() }} utilisateur(s)
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden border border-gray-200 dark:border-gray-700">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900">
                        <tr>
                            <th wire:click="sortBy('name')" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800">
                                <div class="flex items-center gap-1">
                                    <span>Nom</span>
                                    @if($sortField === 'name')
                                        <svg class="w-4 h-4 {{ $sortDirection === 'asc' ? 'rotate-0' : 'rotate-180' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h6a1 1 0 110 2H4a1 1 0 01-1-1zM3 16a1 1 0 011-1h4a1 1 0 110 2H4a1 1 0 01-1-1z"/>
                                        </svg>
                                    @endif
                                </div>
                            </th>
                            <th wire:click="sortBy('type')" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800">
                                <div class="flex items-center gap-1">
                                    <span>Type</span>
                                    @if($sortField === 'type')
                                        <svg class="w-4 h-4 {{ $sortDirection === 'asc' ? 'rotate-0' : 'rotate-180' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h6a1 1 0 110 2H4a1 1 0 01-1-1zM3 16a1 1 0 011-1h4a1 1 0 110 2H4a1 1 0 01-1-1z"/>
                                        </svg>
                                    @endif
                                </div>
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden md:table-cell">Email</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden sm:table-cell">Code</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden lg:table-cell">Contact</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Statut</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($users as $user)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full flex items-center justify-center text-sm font-medium text-white
                                                {{ $user->type === 'admin' ? 'bg-red-500' : 
                                                   ($user->type === 'chauffeur' ? 'bg-green-500' : 
                                                   ($user->type === 'proprietaire' ? 'bg-purple-500' : 
                                                   ($user->type === 'pointeur' ? 'bg-yellow-500' : 
                                                   ($user->type === 'client' ? 'bg-orange-500' : 'bg-indigo-500')))) }}">
                                                {{ strtoupper(substr($user->name, 0, 2)) }}
                                            </div>
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->name }}</div>
                                            @if($user->adresse)
                                                <div class="text-xs text-gray-500 dark:text-gray-400 sm:hidden">{{ Str::limit($user->adresse, 20) }}</div>
                                            @endif
                                            <div class="text-xs text-gray-500 dark:text-gray-400 sm:hidden">
                                                @if($user->code)
                                                    <span class="font-medium">{{ $user->code }}</span>
                                                @endif
                                                @if($user->contact)
                                                    <span class="ml-2">{{ $user->contact }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                        {{ $user->type === 'admin' ? 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-200' : 
                                           ($user->type === 'chauffeur' ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-200' : 
                                           ($user->type === 'proprietaire' ? 'bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-200' : 
                                           ($user->type === 'pointeur' ? 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-200' : 
                                           ($user->type === 'client' ? 'bg-orange-100 dark:bg-orange-900/30 text-orange-800 dark:text-orange-200' : 'bg-indigo-100 dark:bg-indigo-900/30 text-indigo-800 dark:text-indigo-200')))) }}">
                                        @switch($user->type)
                                            @case('admin') ⚡ Admin @break
                                            @case('chauffeur') 🚛 Chauffeur @break
                                            @case('proprietaire') 👑 Propriétaire @break
                                            @case('pointeur') 👉 Pointeur @break
                                            @case('client') 🏪 Client @break
                                            @case('chargeur') 📦 Chargeur @break
                                        @endswitch
                                    </span>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200 hidden md:table-cell">
                                    {{ $user->email ?: '-' }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200 hidden sm:table-cell">
                                    @if($user->code)
                                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                                            {{ $user->code }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200 hidden lg:table-cell">
                                    {{ $user->contact ?: '-' }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <button wire:click="toggleActif({{ $user->id }})" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                        {{ $user->actif ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-200' : 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-200' }}">
                                        {{ $user->actif ? 'Actif' : 'Inactif' }}
                                    </button>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        <button wire:click="edit({{ $user->id }})" class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300" title="Modifier">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>
                                        @if($user->id !== auth()->id())
                                            <button 
                                                wire:click="delete({{ $user->id }})"
                                                wire:confirm="Êtes-vous sûr de vouloir supprimer cet utilisateur ?"
                                                class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300" title="Supprimer">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="text-gray-500 dark:text-gray-400">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                        <p class="mt-4 text-lg font-medium dark:text-white">Aucun utilisateur trouvé</p>
                                        <p class="mt-2">Commencez par créer votre première équipe</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($users->hasPages())
                <div class="bg-white dark:bg-gray-800 px-4 py-3 border-t border-gray-200 dark:border-gray-700 sm:px-6">
                    {{ $users->links() }}
                </div>
            @endif
        </div>

        <!-- Modal -->
        @if($showModal)
            <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 bg-gray-500 dark:bg-gray-900 bg-opacity-75 dark:bg-opacity-75 transition-opacity" wire:click="closeModal"></div>

                    <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                        <form wire:submit="save">
                            <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                <div class="flex items-center mb-4">
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                                        {{ $editingUser ? 'Modifier' : 'Créer' }} un utilisateur
                                    </h3>
                                </div>

                                <div class="space-y-4">
                                    <!-- Nom -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nom complet *</label>
                                        <input 
                                            wire:model="name"
                                            type="text"
                                            class="mt-1 block w-full border border-gray-300 dark:border-gray-700 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white"
                                            placeholder="Thonny, HEVER, Alphonse..."
                                        >
                                        @error('name') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <!-- Type -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Type *</label>
                                            <select wire:model="type" class="mt-1 block w-full border border-gray-300 dark:border-gray-700 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white">
                                                <option value="chauffeur">🚛 Chauffeur</option>
                                                <option value="proprietaire">👑 Propriétaire</option>
                                                <option value="pointeur">👉 Pointeur</option>
                                                <option value="client">🏪 Client</option>
                                                <option value="chargeur">📦 Chargeur</option>
                                                <option value="admin">⚡ Admin</option>
                                            </select>
                                            @error('type') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                                        </div>

                                        <!-- Code -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Code <span class="text-gray-400">(chauffeurs)</span></label>
                                            <input 
                                                wire:model="code"
                                                type="text"
                                                class="mt-1 block w-full border border-gray-300 dark:border-gray-700 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white"
                                                placeholder="034, 037..."
                                            >
                                            @error('code') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                                        </div>
                                    </div>

                                    <!-- Email -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email <span class="text-gray-400">(optionnel)</span></label>
                                        <input 
                                            wire:model="email"
                                            type="email"
                                            class="mt-1 block w-full border border-gray-300 dark:border-gray-700 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white"
                                            placeholder="user@exemple.com"
                                        >
                                        @error('email') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                                    </div>

                                    <!-- Mot de passe -->
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                Mot de passe {{ $editingUser ? '(laisser vide si inchangé)' : '*' }}
                                            </label>
                                            <input 
                                                wire:model="password"
                                                type="password"
                                                class="mt-1 block w-full border border-gray-300 dark:border-gray-700 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white"
                                            >
                                            @error('password') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Confirmer mot de passe</label>
                                            <input 
                                                wire:model="password_confirmation"
                                                type="password"
                                                class="mt-1 block w-full border border-gray-300 dark:border-gray-700 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white"
                                            >
                                            @error('password_confirmation') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                                        </div>
                                    </div>

                                    <!-- Contact -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Contact / Téléphone</label>
                                        <input 
                                            wire:model="contact"
                                            type="text"
                                            class="mt-1 block w-full border border-gray-300 dark:border-gray-700 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white"
                                            placeholder="+261 34 12 345 67"
                                        >
                                        @error('contact') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                                    </div>

                                    <!-- Adresse -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Adresse</label>
                                        <textarea 
                                            wire:model="adresse"
                                            rows="2"
                                            class="mt-1 block w-full border border-gray-300 dark:border-gray-700 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white"
                                            placeholder="Adresse complète..."
                                        ></textarea>
                                        @error('adresse') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                                    </div>

                                    <!-- Actif -->
                                    <div class="flex items-center">
                                        <input 
                                            wire:model="actif"
                                            type="checkbox"
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-700 rounded bg-white dark:bg-gray-900"
                                        >
                                        <label class="ml-2 block text-sm text-gray-900 dark:text-gray-300">Utilisateur actif</label>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gray-50 dark:bg-gray-800 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-200 dark:border-gray-700">
                                <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                                    {{ $editingUser ? 'Modifier' : 'Créer' }}
                                </button>
                                <button type="button" wire:click="closeModal" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                    Annuler
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>