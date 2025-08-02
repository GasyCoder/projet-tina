{{-- resources/views/livewire/finance/tabs/revenus.blade.php --}}
<!-- Statistiques spécifiques aux revenus -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-3 border border-gray-200 dark:border-gray-700">
        <div class="flex items-center">
            <div class="p-2 rounded-full bg-green-100 dark:bg-green-900 dark:bg-opacity-30 text-green-600 dark:text-green-400 mr-2">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-600 dark:text-gray-400">Total Revenus</p>
                <p class="text-sm font-semibold text-green-900 dark:text-green-300">{{ number_format($this->totalRevenus, 0, ',', ' ') }} MGA</p>
            </div>
        </div>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-3 border border-gray-200 dark:border-gray-700">
        <div class="flex items-center">
            <div class="p-2 rounded-full bg-blue-100 dark:bg-blue-900 dark:bg-opacity-30 text-blue-600 dark:text-blue-400 mr-2">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-600 dark:text-gray-400">Revenus Moyens</p>
                <p class="text-sm font-semibold text-blue-900 dark:text-blue-300">{{ number_format($this->revenuMoyen, 0, ',', ' ') }} MGA</p>
            </div>
        </div>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-3 border border-gray-200 dark:border-gray-700">
        <div class="flex items-center">
            <div class="p-2 rounded-full bg-yellow-100 dark:bg-yellow-900 dark:bg-opacity-30 text-yellow-600 dark:text-yellow-400 mr-2">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-600 dark:text-gray-400">En Attente</p>
                <p class="text-sm font-semibold text-yellow-900 dark:text-yellow-300">{{ number_format($this->revenusEnAttente, 0, ',', ' ') }} MGA</p>
            </div>
        </div>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-3 border border-gray-200 dark:border-gray-700">
        <div class="flex items-center">
            <div class="p-2 rounded-full bg-purple-100 dark:bg-purple-900 dark:bg-opacity-30 text-purple-600 dark:text-purple-400 mr-2">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-600 dark:text-gray-400">Nb d'Opérations</p>
                <p class="text-sm font-semibold text-purple-900 dark:text-purple-300">{{ $this->nombreRevenus }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Filtres des revenus -->
<div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 mb-4 border border-gray-200 dark:border-gray-700">
    <div class="flex items-center justify-between mb-3">
        <h3 class="text-sm font-semibold text-gray-900 dark:text-white flex items-center">
            <svg class="w-4 h-4 mr-1 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            Filtres - Revenus
        </h3>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <div>
            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Période</label>
            <select 
                wire:model.live="periodeRevenus" 
                class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-2 py-1 text-xs focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-150 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
            >
                <option value="semaine">Cette semaine</option>
                <option value="mois_courant">Ce mois</option>
                <option value="trimestre">Ce trimestre</option>
                <option value="annee">Cette année</option>
                <option value="personnalise">Personnalisé</option>
            </select>
        </div>
        @if($periodeRevenus === 'personnalise')
            <div>
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Date Début</label>
                <input 
                    wire:model.live="dateDebutRevenus" 
                    type="date" 
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-2 py-1 text-xs focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-150 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                >
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Date Fin</label>
                <input 
                    wire:model.live="dateFinRevenus" 
                    type="date" 
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-2 py-1 text-xs focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-150 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                >
            </div>
        @endif
        <div>
            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Type de Revenu</label>
            <select 
                wire:model.live="typeRevenu" 
                class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-2 py-1 text-xs focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-150 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
            >
                <option value="">Tous les types</option>
                <option value="vente">Vente</option>
                <option value="depot">Dépôt</option>
                <option value="commission">Commission</option>
                <option value="transfert">Transfert</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Statut</label>
            <select 
                wire:model.live="statutRevenu" 
                class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-2 py-1 text-xs focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-150 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
            >
                <option value="">Tous les statuts</option>
                <option value="payee">Encaissé</option>
                <option value="partiellement_payee">Partiel</option>
                <option value="attente">En attente</option>
                <option value="confirme">Confirmé</option>
            </select>
        </div>
    </div>
</div>

<!-- Répartition par type de revenu -->
<div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 mb-4 border border-gray-200 dark:border-gray-700">
    <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
        <svg class="w-4 h-4 mr-1 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2M9 19" />
        </svg>
        Répartition par Type
    </h4>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        @forelse($this->repartitionRevenus as $type => $data)
            <div class="bg-gray-50 dark:bg-gray-700 rounded-md p-3 border border-gray-200 dark:border-gray-600 hover:shadow-sm transition-shadow duration-150">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-medium text-gray-700 dark:text-gray-300">
                        @switch($type)
                            @case('vente') Ventes @break
                            @case('depot') Dépôts @break
                            @case('commission') Commissions @break
                            @case('transfert') Transferts @break
                            @default {{ ucfirst($type) }}
                        @endswitch
                    </span>
                    <span class="text-xs font-semibold text-gray-900 dark:text-gray-200 bg-green-100 dark:bg-green-900 dark:bg-opacity-30 px-2 py-0.5 rounded-full">{{ $data['count'] }}</span>
                </div>
                <div class="text-sm font-semibold text-green-900 dark:text-green-300">
                    {{ number_format($data['total'], 0, ',', ' ') }} MGA
                </div>
                <div class="mt-1 bg-gray-200 dark:bg-gray-600 rounded-full h-1.5">
                    <div 
                        class="bg-green-600 h-1.5 rounded-full transition-all duration-300" 
                        style="width: {{ $this->totalRevenus > 0 ? ($data['total'] / $this->totalRevenus * 100) : 0 }}%"
                    ></div>
                </div>
                <div class="text-xs text-green-600 dark:text-green-400 mt-1">
                    {{ $this->totalRevenus > 0 ? number_format(($data['total'] / $this->totalRevenus * 100), 1) : 0 }}%
                </div>
            </div>
        @empty
            <div class="col-span-4 text-center py-6 text-gray-500 dark:text-gray-400">
                <svg class="mx-auto h-8 w-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2M9 19" />
                </svg>
                <p class="mt-1 text-xs font-medium">Aucune donnée de revenus disponible</p>
            </div>
        @endforelse
    </div>
</div>

<!-- Tableau des revenus -->
<div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden border border-gray-200 dark:border-gray-700">
    <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 bg-green-50 dark:bg-green-900 dark:bg-opacity-20">
        <h3 class="text-sm font-semibold text-green-900 dark:text-green-300 flex items-center">
            <svg class="w-4 h-4 mr-1 text-green-500 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Liste des Revenus
        </h3>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-green-50 dark:bg-green-900 dark:bg-opacity-20">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-green-800 dark:text-green-300 uppercase tracking-wider">Date</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-green-800 dark:text-green-300 uppercase tracking-wider">Référence</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-green-800 dark:text-green-300 uppercase tracking-wider">Type</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-green-800 dark:text-green-300 uppercase tracking-wider">Description</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-green-800 dark:text-green-300 uppercase tracking-wider">Montant</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-green-800 dark:text-green-300 uppercase tracking-wider">Voyage</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-green-800 dark:text-green-300 uppercase tracking-wider">Statut</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($revenus as $revenu)
                    <tr class="hover:bg-green-50 dark:hover:bg-gray-700 transition-all duration-150">
                        <td class="px-4 py-2 whitespace-nowrap text-xs text-gray-600 dark:text-gray-400">
                            {{ \Carbon\Carbon::parse($revenu->date)->format('d/m/Y') }}
                        </td>
                        <td class="px-4 py-2 whitespace-nowrap text-xs font-medium text-gray-900 dark:text-white">
                            {{ $revenu->reference }}
                        </td>
                        <td class="px-4 py-2 whitespace-nowrap text-xs">
                            <span class="inline-flex px-2 py-0.5 text-xs font-semibold rounded-full 
                                @switch($revenu->type)
                                    @case('vente') bg-green-100 dark:bg-green-900 dark:bg-opacity-30 text-green-800 dark:text-green-300 @break
                                    @case('depot') bg-blue-100 dark:bg-blue-900 dark:bg-opacity-30 text-blue-800 dark:text-blue-300 @break
                                    @case('commission') bg-purple-100 dark:bg-purple-900 dark:bg-opacity-30 text-purple-800 dark:text-purple-300 @break
                                    @case('transfert') bg-indigo-100 dark:bg-indigo-900 dark:bg-opacity-30 text-indigo-800 dark:text-indigo-300 @break
                                    @default bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300
                                @endswitch
                            ">
                                {{ ucfirst($revenu->type) }}
                            </span>
                        </td>
                        <td class="px-4 py-2 text-xs text-gray-600 dark:text-gray-400 max-w-xs truncate" title="{{ $revenu->objet ?? $revenu->description }}">
                            {{ $revenu->objet ?? $revenu->description ?? 'N/A' }}
                        </td>
                        <td class="px-4 py-2 whitespace-nowrap text-xs font-semibold text-green-600 dark:text-green-400">
                            +{{ number_format($revenu->montant_mga ?? $revenu->montant, 0, ',', ' ') }} MGA
                        </td>
                        <td class="px-4 py-2 whitespace-nowrap text-xs">
                            @if($revenu->voyage_id)
                                <a 
                                    href="{{ route('voyages.show', $revenu->voyage_id) }}" 
                                    class="inline-flex px-2 py-0.5 text-xs font-semibold rounded-full bg-blue-100 dark:bg-blue-900 dark:bg-opacity-30 text-blue-800 dark:text-blue-300 hover:bg-blue-200 dark:hover:bg-blue-800 transition-all duration-150"
                                >
                                    {{ $revenu->voyage->reference ?? 'V-' . $revenu->voyage_id }}
                                </a>
                            @else
                                <span class="text-gray-400 dark:text-gray-500 text-xs">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-2 whitespace-nowrap text-xs">
                            <span class="inline-flex px-2 py-0.5 text-xs font-semibold rounded-full 
                                @switch($revenu->statut)
                                    @case('payee') bg-green-100 dark:bg-green-900 dark:bg-opacity-30 text-green-800 dark:text-green-300 @break
                                    @case('partiellement_payee') bg-yellow-100 dark:bg-yellow-900 dark:bg-opacity-30 text-yellow-800 dark:text-yellow-300 @break
                                    @case('attente') bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300 @break
                                    @case('confirme') bg-blue-100 dark:bg-blue-900 dark:bg-opacity-30 text-blue-800 dark:text-blue-300 @break
                                @endswitch
                            ">
                                @switch($revenu->statut)
                                    @case('payee') Encaissé @break
                                    @case('partiellement_payee') Partiel @break
                                    @case('attente') En attente @break
                                    @case('confirme') Confirmé @break
                                @endswitch
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">
                            <svg class="mx-auto h-8 w-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="mt-1 text-sm font-medium">Aucun revenu trouvé</p>
                            <p class="mt-0.5 text-xs">Ajustez vos filtres ou créez un nouveau revenu</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($revenus->hasPages())
        <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600">
            {{ $revenus->links() }}
        </div>
    @endif
</div>