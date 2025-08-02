{{-- resources/views/livewire/finance/tabs/depenses.blade.php --}}
<div x-data="{ darkMode: $persist(false) }" :class="{ 'dark': darkMode }">
    <!-- Statistiques spécifiques aux dépenses -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
        <!-- Total Dépenses -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-3 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center">
                <div class="p-2 rounded-full bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 mr-2">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-600 dark:text-gray-400">Total Dépenses</p>
                    <p class="text-sm font-semibold text-red-900 dark:text-red-400">{{ number_format($this->totalDepenses, 0, ',', ' ') }} MGA</p>
                </div>
            </div>
        </div>
        
        <!-- Dépense Moyenne -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-3 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center">
                <div class="p-2 rounded-full bg-orange-100 dark:bg-orange-900/30 text-orange-600 dark:text-orange-400 mr-2">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-600 dark:text-gray-400">Dépense Moyenne</p>
                    <p class="text-sm font-semibold text-orange-900 dark:text-orange-400">{{ number_format($this->depenseMoyenne, 0, ',', ' ') }} MGA</p>
                </div>
            </div>
        </div>
        
        <!-- En Attente -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-3 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center">
                <div class="p-2 rounded-full bg-yellow-100 dark:bg-yellow-900/30 text-yellow-600 dark:text-yellow-400 mr-2">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-600 dark:text-gray-400">En Attente</p>
                    <p class="text-sm font-semibold text-yellow-900 dark:text-yellow-400">{{ number_format($this->depensesEnAttente, 0, ',', ' ') }} MGA</p>
                </div>
            </div>
        </div>
        
        <!-- Nb d'Opérations -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-3 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center">
                <div class="p-2 rounded-full bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 mr-2">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-600 dark:text-gray-400">Nb d'Opérations</p>
                    <p class="text-sm font-semibold text-purple-900 dark:text-purple-400">{{ $this->nombreDepenses }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres des dépenses -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 mb-4 border border-gray-200 dark:border-gray-700">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-200 flex items-center">
                <svg class="w-4 h-4 mr-1 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                Filtres - Dépenses
            </h3>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <div>
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-400 mb-1">Catégorie</label>
                <select 
                    wire:model.live="categorieDepense" 
                    class="w-full border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-300 rounded-md px-2 py-1 text-xs focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-150"
                >
                    <option value="">Toutes les catégories</option>
                    <option value="achat">Achats</option>
                    <option value="frais">Frais</option>
                    <option value="avance">Avances</option>
                    <option value="paiement">Paiements</option>
                    <option value="retrait">Retraits</option>
                    <option value="transfert">Transferts</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-400 mb-1">Période</label>
                <select 
                    wire:model.live="periodeDepenses" 
                    class="w-full border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-300 rounded-md px-2 py-1 text-xs focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-150"
                >
                    <option value="semaine">Cette semaine</option>
                    <option value="mois_courant">Ce mois</option>
                    <option value="trimestre">Ce trimestre</option>
                    <option value="annee">Cette année</option>
                    <option value="personnalise">Personnalisé</option>
                </select>
            </div>
            @if($periodeDepenses === 'personnalise')
                <div>
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-400 mb-1">Date Début</label>
                    <input 
                        wire:model.live="dateDebutDepenses" 
                        type="date" 
                        class="w-full border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-300 rounded-md px-2 py-1 text-xs focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-150"
                    >
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-400 mb-1">Date Fin</label>
                    <input 
                        wire:model.live="dateFinDepenses" 
                        type="date" 
                        class="w-full border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-300 rounded-md px-2 py-1 text-xs focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-150"
                    >
                </div>
            @endif
            <div>
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-400 mb-1">Statut</label>
                <select 
                    wire:model.live="statutDepense" 
                    class="w-full border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-300 rounded-md px-2 py-1 text-xs focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-150"
                >
                    <option value="">Tous les statuts</option>
                    <option value="payee">Payée</option>
                    <option value="partiellement_payee">Partielle</option>
                    <option value="attente">En attente</option>
                    <option value="confirme">Confirmé</option>
                    <option value="annule">Annulée</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Répartition par catégorie -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 mb-4 border border-gray-200 dark:border-gray-700">
        <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-200 mb-3 flex items-center">
            <svg class="w-4 h-4 mr-1 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2M9 19" />
            </svg>
            Répartition par Catégorie
        </h4>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
            @forelse($this->repartitionDepenses as $categorie => $data)
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-md p-3 border border-gray-200 dark:border-gray-700 hover:shadow-sm transition-shadow duration-150">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-medium text-gray-700 dark:text-gray-300">
                            @switch($categorie)
                                @case('achat') Achats @break
                                @case('frais') Frais @break
                                @case('avance') Avances @break
                                @case('paiement') Paiements @break
                                @case('retrait') Retraits @break
                                @case('transfert') Transferts @break
                                @default {{ ucfirst($categorie) }}
                            @endswitch
                        </span>
                        <span class="text-xs font-semibold text-gray-900 dark:text-gray-200 bg-red-100 dark:bg-red-900/30 px-2 py-0.5 rounded-full">{{ $data['count'] }}</span>
                    </div>
                    <div class="text-sm font-semibold text-red-900 dark:text-red-400">
                        {{ number_format($data['total'], 0, ',', ' ') }} MGA
                    </div>
                    <div class="mt-1 bg-gray-200 dark:bg-gray-700 rounded-full h-1.5">
                        <div 
                            class="bg-red-600 h-1.5 rounded-full transition-all duration-300" 
                            style="width: {{ $this->totalDepenses > 0 ? ($data['total'] / $this->totalDepenses * 100) : 0 }}%"
                        ></div>
                    </div>
                    <div class="text-xs text-red-600 dark:text-red-400 mt-1">
                        {{ $this->totalDepenses > 0 ? number_format(($data['total'] / $this->totalDepenses * 100), 1) : 0 }}%
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-6 text-gray-500 dark:text-gray-400">
                    <svg class="mx-auto h-8 w-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2M9 19" />
                    </svg>
                    <p class="mt-1 text-xs font-medium">Aucune donnée de dépenses disponible</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Tableau des dépenses -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden border border-gray-200 dark:border-gray-700">
        <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 bg-red-50 dark:bg-gray-900">
            <h3 class="text-sm font-semibold text-red-900 dark:text-red-400 flex items-center">
                <svg class="w-4 h-4 mr-1 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Liste des Dépenses
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-red-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-red-800 dark:text-red-400 uppercase tracking-wider">Date</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-red-800 dark:text-red-400 uppercase tracking-wider">Référence</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-red-800 dark:text-red-400 uppercase tracking-wider">Catégorie</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-red-800 dark:text-red-400 uppercase tracking-wider">Description</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-red-800 dark:text-red-400 uppercase tracking-wider">Montant</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-red-800 dark:text-red-400 uppercase tracking-wider">Voyage</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-red-800 dark:text-red-400 uppercase tracking-wider">Statut</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($depenses as $depense)
                        <tr class="hover:bg-red-50 dark:hover:bg-gray-700/50 transition-all duration-150">
                            <td class="px-4 py-2 whitespace-nowrap text-xs text-gray-600 dark:text-gray-400">
                                {{ \Carbon\Carbon::parse($depense->date)->format('d/m/Y') }}
                            </td>
                            <td class="px-4 py-2 whitespace-nowrap text-xs font-medium text-gray-900 dark:text-gray-200">
                                {{ $depense->reference }}
                            </td>
                            <td class="px-4 py-2 whitespace-nowrap text-xs">
                                <span class="inline-flex px-2 py-0.5 text-xs font-semibold rounded-full 
                                    @switch($depense->type)
                                        @case('achat') bg-orange-100 dark:bg-orange-900/30 text-orange-800 dark:text-orange-400 @break
                                        @case('frais') bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400 @break
                                        @case('avance') bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-400 @break
                                        @case('paiement') bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400 @break
                                        @case('retrait') bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-400 @break
                                        @case('transfert') bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300 @break
                                        @default bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300
                                    @endswitch
                                ">
                                    {{ ucfirst($depense->type) }}
                                </span>
                            </td>
                            <td class="px-4 py-2 text-xs text-gray-600 dark:text-gray-400 max-w-xs truncate" title="{{ $depense->objet ?? $depense->description }}">
                                {{ $depense->objet ?? $depense->description ?? 'N/A' }}
                            </td>
                            <td class="px-4 py-2 whitespace-nowrap text-xs">
                                <div class="font-semibold text-red-600 dark:text-red-400">
                                    -{{ number_format($depense->montant_mga ?? $depense->montant, 0, ',', ' ') }} MGA
                                </div>
                                @if($depense->statut === 'partiellement_payee' && isset($depense->reste_a_payer))
                                    <div class="text-xs text-yellow-600 dark:text-yellow-400 mt-1">
                                        Reste: {{ number_format($depense->reste_a_payer, 0, ',', ' ') }} MGA
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-2 whitespace-nowrap text-xs">
                                @if($depense->voyage_id)
                                    <a 
                                        href="{{ route('voyages.show', $depense->voyage_id) }}" 
                                        class="inline-flex px-2 py-0.5 text-xs font-semibold rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400 hover:bg-blue-200 dark:hover:bg-blue-800/50 transition-all duration-150"
                                    >
                                        {{ $depense->voyage->reference ?? 'V-' . $depense->voyage_id }}
                                    </a>
                                @else
                                    <span class="text-gray-400 dark:text-gray-500 text-xs">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 whitespace-nowrap text-xs">
                                <span class="inline-flex px-2 py-0.5 text-xs font-semibold rounded-full 
                                    @switch($depense->statut)
                                        @case('payee') bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400 @break
                                        @case('partiellement_payee') bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-400 @break
                                        @case('attente') bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300 @break
                                        @case('confirme') bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400 @break
                                        @case('annule') bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400 @break
                                    @endswitch
                                ">
                                    @switch($depense->statut)
                                        @case('payee') Payée @break
                                        @case('partiellement_payee') Partielle @break
                                        @case('attente') En attente @break
                                        @case('confirme') Confirmé @break
                                        @case('annule') Annulée @break
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
                                <p class="mt-1 text-sm font-medium">Aucune dépense trouvée</p>
                                <p class="mt-0.5 text-xs">Ajustez vos filtres ou créez une nouvelle dépense</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($depenses->hasPages())
            <div class="px-4 py-3 bg-red-50 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700">
                {{ $depenses->links() }}
            </div>
        @endif
    </div>
</div>

