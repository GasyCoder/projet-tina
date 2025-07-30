<!-- /home/kananavy/Documents/DevGGasy/projet-tina/resources/views/livewire/finance/tabs/suivi-transactions.blade.php -->
<div class="min-h-screen bg-gray-50 p-6">
    <div class="max-w-7xl mx-auto">
        <!-- En-tête -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold text-gray-900">📊 Suivi Global des Transactions</h1>
                <nav class="flex space-x-4">
                    <span class="text-blue-600 border-b-2 border-blue-500 px-3 py-2 text-sm font-medium">
                        📊 Suivi Global
                    </span>
                    <a href="{{ route('finance.revenus') }}" class="text-gray-600 hover:text-blue-600 px-3 py-2 text-sm font-medium">
                        💰 Revenus
                    </a>
                    <a href="{{ route('finance.depenses') }}" class="text-gray-600 hover:text-blue-600 px-3 py-2 text-sm font-medium">
                        💸 Dépenses
                    </a>
                </nav>
            </div>
        </div>

        <!-- Statistiques communes -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow p-4 border-t-4 border-green-500">
                <div class="flex items-center">
                    <div class="p-2 rounded-full bg-green-50 text-green-600 mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Entrées</p>
                        <p class="text-lg font-semibold">{{ number_format($this->totalEntrees, 0, ',', ' ') }} MGA</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-4 border-t-4 border-red-500">
                <div class="flex items-center">
                    <div class="p-2 rounded-full bg-red-50 text-red-600 mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Sorties</p>
                        <p class="text-lg font-semibold">{{ number_format($this->totalSorties, 0, ',', ' ') }} MGA</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-4 border-t-4 border-blue-500">
                <div class="flex items-center">
                    <div class="p-2 rounded-full bg-blue-50 text-blue-600 mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Bénéfice</p>
                        <p class="text-lg font-semibold {{ $this->beneficeNet >= 0 ? 'text-green-500' : 'text-red-500' }}">
                            {{ number_format($this->beneficeNet, 0, ',', ' ') }} MGA
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-4 border-t-4 border-yellow-500">
                <div class="flex items-center">
                    <div class="p-2 rounded-full bg-yellow-50 text-yellow-600 mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">En attente</p>
                        <p class="text-lg font-semibold">{{ $this->transactionsEnAttente }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtres du suivi global -->
        <div class="bg-white rounded-lg shadow p-4 mb-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">🔍 Filtres</h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date début</label>
                    <input type="date" wire:model.live="dateDebut" class="w-full border rounded-md px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date fin</label>
                    <input type="date" wire:model.live="dateFin" class="w-full border rounded-md px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                    <select wire:model.live="filterType" class="w-full border rounded-md px-3 py-2 text-sm">
                        <option value="">Tous les types</option>
                        <option value="vente">💰 Vente</option>
                        <option value="achat">🛒 Achat</option>
                        <option value="depot">📥 Dépôt</option>
                        <option value="retrait">📤 Retrait</option>
                        <option value="transfert">🔄 Transfert</option>
                        <option value="frais">🧾 Frais</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                    <select wire:model.live="filterStatut" class="w-full border rounded-md px-3 py-2 text-sm">
                        <option value="">Tous les statuts</option>
                        <option value="payee">✅ Payée</option>
                        <option value="partiellement_payee">⚠️ Partielle</option>
                        <option value="attente">⏳ En attente</option>
                        <option value="confirme">✅ Confirmé</option>
                        <option value="annule">❌ Annulé</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Contenu du suivi global -->
        <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
            <div class="px-4 py-5 border-b border-gray-200 sm:px-6">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium text-gray-900">📊 Suivi Global des Transactions</h3>
                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        ➕ Nouvelle Transaction
                    </button>
                </div>
            </div>
            
            <!-- Répartition par type et statut -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 p-4">
                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="font-medium text-gray-900 mb-3">Répartition par Type</h4>
                    <div class="space-y-2">
                        @forelse($this->repartitionParType as $type => $data)
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">
                                    @switch($type)
                                        @case('achat') 🛒 Achat @break
                                        @case('vente') 💰 Vente @break
                                        @case('transfert') 🔄 Transfert @break
                                        @case('frais') 🧾 Frais @break
                                        @case('commission') 💼 Commission @break
                                        @case('paiement') 💳 Paiement @break
                                        @case('avance') 💸 Avance @break
                                        @case('depot') 📥 Dépôt @break
                                        @case('retrait') 📤 Retrait @break
                                        @default ✨ {{ ucfirst($type) }}
                                    @endswitch
                                </span>
                                <div class="flex items-center space-x-2">
                                    <span class="text-sm font-medium">{{ $data['count'] }}</span>
                                    <span class="text-sm text-gray-500">({{ number_format($data['total'], 0, ',', ' ') }} MGA)</span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-gray-500 text-sm">Aucune donnée disponible</div>
                        @endforelse
                    </div>
                </div>

                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="font-medium text-gray-900 mb-3">Répartition par Statut</h4>
                    <div class="space-y-2">
                        @forelse($this->repartitionParStatut as $statut => $count)
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">
                                    @switch($statut)
                                        @case('payee') ✅ Payées @break
                                        @case('partiellement_payee') ⚠️ Partielles @break
                                        @case('attente') ⏳ En attente @break
                                        @case('confirme') ✅ Confirmées @break
                                        @case('annule') ❌ Annulées @break
                                        @default {{ ucfirst($statut) }}
                                    @endswitch
                                </span>
                                <span class="text-sm font-medium">{{ $count }}</span>
                            </div>
                        @empty
                            <div class="text-center text-gray-500 text-sm">Aucune donnée disponible</div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Tableau des transactions -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Référence</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Voyage</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($transactions as $transaction)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($transaction->date)->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $transaction->reference }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div class="flex items-center">
                                    @switch($transaction->type)
                                        @case('vente')
                                            <span class="mr-2 text-green-500">💰</span>
                                            <span>Vente</span>
                                            @break
                                        @case('achat')
                                            <span class="mr-2 text-red-500">🛒</span>
                                            <span>Achat</span>
                                            @break
                                        @case('depot')
                                            <span class="mr-2 text-blue-500">📥</span>
                                            <span>Dépôt</span>
                                            @break
                                        @case('retrait')
                                            <span class="mr-2 text-purple-500">📤</span>
                                            <span>Retrait</span>
                                            @break
                                        @case('transfert')
                                            <span class="mr-2 text-blue-500">🔄</span>
                                            <span>Transfert</span>
                                            @break
                                        @case('frais')
                                            <span class="mr-2 text-red-500">🧾</span>
                                            <span>Frais</span>
                                            @break
                                        @default
                                            <span class="mr-2">✨</span>
                                            <span>{{ ucfirst($transaction->type) }}</span>
                                    @endswitch
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($transaction->voyage_id)
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                        🚗 {{ $transaction->voyage->reference ?? 'N/A' }}
                                    </span>
                                @else
                                    <span class="text-gray-400 text-xs">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium {{ in_array($transaction->type, ['vente','depot','commission']) ? 'text-green-600' : 'text-red-600' }}">
                                {{ in_array($transaction->type, ['vente','depot','commission']) ? '+' : '-' }}{{ number_format($transaction->montant_mga, 0, ',', ' ') }} MGA
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @switch($transaction->statut)
                                    @case('payee')
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Payée
                                        </span>
                                        @break
                                    @case('partiellement_payee')
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Partielle
                                        </span>
                                        @break
                                    @case('attente')
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                            En attente
                                        </span>
                                        @break
                                    @case('confirme')
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            Confirmée
                                        </span>
                                        @break
                                    @case('annule')
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Annulée
                                        </span>
                                        @break
                                @endswitch
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button wire:click="editTransaction({{ $transaction->id }})" class="text-blue-600 hover:text-blue-900 mr-3">
                                    Modifier
                                </button>
                                @if($transaction->statut === 'attente')
                                    <button wire:click="marquerPayee({{ $transaction->id }})" class="text-green-600 hover:text-green-900">
                                        Payer
                                    </button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                                Aucune transaction trouvée pour cette période
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($transactions->hasPages())
            <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 sm:px-6">
                {{ $transactions->links() }}
            </div>
            @endif
        </div>

        <!-- Alertes -->
        @if (session()->has('success'))
            <div class="fixed bottom-4 right-4 bg-green-50 border border-green-200 rounded-md p-4 z-50">
                <div class="flex">
                    <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                    </svg>
                    <p class="ml-3 text-sm text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="fixed bottom-4 right-4 bg-red-50 border border-red-200 rounded-md p-4 z-50">
                <div class="flex">
                    <svg class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"/>
                    </svg>
                    <p class="ml-3 text-sm text-red-800">{{ session('error') }}</p>
                </div>
            </div>
        @endif
    </div>

    <!-- Modals -->
    @include('livewire.finance.modals.transaction-modal', [
        'voyages' => $voyages,
        'comptes' => $comptes
    ])
    
    @include('livewire.finance.modals.compte-modal')
</div>