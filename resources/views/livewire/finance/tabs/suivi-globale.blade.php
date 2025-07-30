<!-- livewire.finance.tabs.suivi-globale -->
<div class="space-y-4">
    <!-- Filtres -->
    <div class="bg-white rounded-lg shadow-sm p-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Date Début</label>
                <input 
                    type="date" 
                    wire:model.live="dateDebut" 
                    class="w-full border border-gray-300 rounded-md px-2 py-1 text-xs focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-150"
                >
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Date Fin</label>
                <input 
                    type="date" 
                    wire:model.live="dateFin" 
                    class="w-full border border-gray-300 rounded-md px-2 py-1 text-xs focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-150"
                >
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Type</label>
                <select 
                    wire:model.live="filterType" 
                    class="w-full border border-gray-300 rounded-md px-2 py-1 text-xs focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-150"
                >
                    <option value="">Tous les types</option>
                    <option value="vente">Vente</option>
                    <option value="achat">Achat</option>
                    <option value="depot">Dépôt</option>
                    <option value="retrait">Retrait</option>
                    <option value="transfert">Transfert</option>
                    <option value="frais">Frais</option>
                    <option value="commission">Commission</option>
                    <option value="avance">Avance</option>
                    <option value="paiement">Paiement</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Statut</label>
                <select 
                    wire:model.live="filterStatut" 
                    class="w-full border border-gray-300 rounded-md px-2 py-1 text-xs focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-150"
                >
                    <option value="">Tous les statuts</option>
                    <option value="payee">Payée</option>
                    <option value="partiellement_payee">Partielle</option>
                    <option value="attente">En attente</option>
                    <option value="confirme">Confirmée</option>
                    <option value="annule">Annulée</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Répartitions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <!-- Répartition par type -->
        <div class="bg-white rounded-lg shadow-sm p-4">
            <h4 class="text-sm font-semibold text-gray-900 mb-3 flex items-center">
                <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2M9 19" />
                </svg>
                Répartition par Type
            </h4>
            <div class="space-y-2">
                @forelse($this->repartitionParType as $type => $data)
                    <div class="flex items-center justify-between p-2 bg-gray-50 rounded-md hover:bg-gray-100 transition-all duration-150 text-xs">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                @switch($type)
                                    @case('achat')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h18v18H3V3z" />
                                        @break
                                    @case('vente')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1" />
                                        @break
                                    @case('transfert')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m-12 4H4m0 0l4-4m-4 4l4 4" />
                                        @break
                                    @case('frais')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        @break
                                    @case('commission')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                        @break
                                    @case('paiement')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                        @break
                                    @case('avance')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1" />
                                        @break
                                    @case('depot')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        @break
                                    @case('retrait')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        @break
                                    @default
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                @endswitch
                            </svg>
                            <span class="font-medium text-gray-700">{{ ucfirst($type) }}</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="font-semibold text-gray-900">{{ $data['count'] }}</span>
                            <span class="font-semibold {{ in_array($type, ['vente','depot','commission']) ? 'text-green-600' : 'text-red-600' }}">
                                {{ in_array($type, ['vente','depot','commission']) ? '+' : '-' }}{{ number_format($data['total'], 0, ',', ' ') }} MGA
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-6 text-gray-500">
                        <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2M9 19" />
                        </svg>
                        <p class="mt-1 text-xs font-medium">Aucune donnée</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Répartition par statut -->
        <div class="bg-white rounded-lg shadow-sm p-4">
            <h4 class="text-sm font-semibold text-gray-900 mb-3 flex items-center">
                <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h10m0 0v10m0-10l-4 4m4-4l4 4" />
                </svg>
                Répartition par Statut
            </h4>
            <div class="space-y-2">
                @forelse($this->repartitionParStatut as $statut => $count)
                    <div class="flex items-center justify-between p-2 bg-gray-50 rounded-md hover:bg-gray-100 transition-all duration-150 text-xs">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                @switch($statut)
                                    @case('payee')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        @break
                                    @case('partiellement_payee')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        @break
                                    @case('attente')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        @break
                                    @case('confirme')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        @break
                                    @case('annule')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        @break
                                    @default
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h10m0 0v10m0-10l-4 4m4-4l4 4" />
                                @endswitch
                            </svg>
                            <span class="font-medium text-gray-700">
                                @switch($statut)
                                    @case('payee') Payées @break
                                    @case('partiellement_payee') Partielles @break
                                    @case('attente') En attente @break
                                    @case('confirme') Confirmées @break
                                    @case('annule') Annulées @break
                                    @default {{ ucfirst($statut) }}
                                @endswitch
                            </span>
                        </div>
                        <span class="font-semibold text-gray-900 bg-indigo-100 px-2 py-0.5 rounded-full">{{ $count }}</span>
                    </div>
                @empty
                    <div class="text-center py-6 text-gray-500">
                        <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h10m0 0v10m0-10l-4 4m4-4l4 4" />
                        </svg>
                        <p class="mt-1 text-xs font-medium">Aucune donnée</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Tableau des transactions -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="px-4 py-3 border-b border-gray-200 bg-indigo-50">
            <h3 class="text-sm font-semibold text-indigo-900 flex items-center">
                <svg class="w-4 h-4 mr-1 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                </svg>
                Toutes les Transactions
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Référence</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Voyage</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($transactions as $transaction)
                        <tr class="hover:bg-gray-50 transition-all duration-150">
                            <td class="px-4 py-2 whitespace-nowrap text-xs text-gray-600">
                                {{ \Carbon\Carbon::parse($transaction->date)->format('d/m/Y') }}
                            </td>
                            <td class="px-4 py-2 whitespace-nowrap text-xs font-medium text-gray-900">
                                {{ $transaction->reference }}
                            </td>
                            <td class="px-4 py-2 whitespace-nowrap text-xs">
                                <span class="inline-flex px-2 py-0.5 text-xs font-semibold rounded-full 
                                    @switch($transaction->type)
                                        @case('vente') bg-green-100 text-green-800 @break
                                        @case('achat') bg-red-100 text-red-800 @break
                                        @case('depot') bg-blue-100 text-blue-800 @break
                                        @case('retrait') bg-purple-100 text-purple-800 @break
                                        @case('transfert') bg-indigo-100 text-indigo-800 @break
                                        @case('frais') bg-red-100 text-red-800 @break
                                        @case('commission') bg-green-100 text-green-800 @break
                                        @case('avance') bg-yellow-100 text-yellow-800 @break
                                        @case('paiement') bg-blue-100 text-blue-800 @break
                                        @default bg-gray-100 text-gray-800
                                    @endswitch
                                ">
                                    {{ ucfirst($transaction->type) }}
                                </span>
                            </td>
                            <td class="px-4 py-2 text-xs text-gray-600 max-w-xs truncate" title="{{ $transaction->objet ?? $transaction->description }}">
                                {{ $transaction->objet ?? $transaction->description ?? 'N/A' }}
                            </td>
                            <td class="px-4 py-2 whitespace-nowrap text-xs">
                                @if($transaction->voyage_id)
                                    <a href="{{ route('voyages.show', $transaction->voyage_id) }}" class="inline-flex px-2 py-0.5 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 hover:bg-blue-200 transition-all duration-150">
                                        {{ $transaction->voyage->reference ?? 'V-' . $transaction->voyage_id }}
                                    </a>
                                @else
                                    <span class="text-gray-400 text-xs">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 whitespace-nowrap text-xs font-medium {{ in_array($transaction->type, ['vente','depot','commission']) ? 'text-green-600' : 'text-red-600' }}">
                                {{ in_array($transaction->type, ['vente','depot','commission']) ? '+' : '-' }}{{ number_format($transaction->montant_mga ?? $transaction->montant, 0, ',', ' ') }} MGA
                            </td>
                            <td class="px-4 py-2 whitespace-nowrap text-xs">
                                <span class="px-2 py-0.5 inline-flex text-xs font-semibold rounded-full 
                                    @switch($transaction->statut)
                                        @case('payee') bg-green-100 text-green-800 @break
                                        @case('partiellement_payee') bg-yellow-100 text-yellow-800 @break
                                        @case('attente') bg-gray-100 text-gray-800 @break
                                        @case('confirme') bg-blue-100 text-blue-800 @break
                                        @case('annule') bg-red-100 text-red-800 @break
                                    @endswitch
                                ">
                                    @switch($transaction->statut)
                                        @case('payee') Payée @break
                                        @case('partiellement_payee') Partielle @break
                                        @case('attente') En attente @break
                                        @case('confirme') Confirmée @break
                                        @case('annule') Annulée @break
                                    @endswitch
                                </span>
                            </td>
                            <td class="px-4 py-2 whitespace-nowrap text-xs font-medium">
                                <div class="flex space-x-2">
                                    <button wire:click="editTransaction({{ $transaction->id }})" class="text-indigo-600 hover:text-indigo-800 transition-all duration-150">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </button>
                                    @if($transaction->statut === 'attente')
                                        <button wire:click="marquerPayee({{ $transaction->id }})" class="text-green-600 hover:text-green-800 transition-all duration-150">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-6 text-center text-gray-500">
                                <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                </svg>
                                <p class="mt-1 text-sm font-medium">Aucune transaction trouvée</p>
                                <p class="mt-0.5 text-xs">Ajustez vos filtres ou créez une nouvelle transaction</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($transactions->hasPages())
            <div class="px-4 py-3 bg-gray-50 border-t border-gray-200">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>
</div>