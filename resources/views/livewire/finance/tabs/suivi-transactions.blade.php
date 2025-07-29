<!-- /home/kananavy/Documents/DevGGasy/projet-tina/resources/views/livewire/finance/tabs/suivi-transactions.blade.php -->
<div class="min-h-screen bg-gray-50 p-6">
    <div class="max-w-7xl mx-auto">
        <!-- En-tête avec onglets -->
        <div class="mb-8">
            
                <nav class="-mb-px flex space-x-8">
                    <button 
                        wire:click="setActiveTab('suivi')"
                        class="{{ $activeTab === 'suivi' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                    >
                        📊 Suivi Global
                    </button>
                    <button 
                        wire:click="setActiveTab('revenus')"
                        class="{{ $activeTab === 'revenus' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                    >
                        💰 Liste des Revenus
                    </button>
                    <button 
                        wire:click="setActiveTab('depenses')"
                        class="{{ $activeTab === 'depenses' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                    >
                        💸 Liste des Dépenses
                    </button>
                </nav>
          
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

        <!-- Filtres selon l'onglet actif -->
        @if($activeTab === 'suivi')
            <div class="bg-white rounded-lg shadow p-4 mb-6">
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
        @elseif($activeTab === 'revenus')
            <div class="bg-white rounded-lg shadow p-4 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Période</label>
                        <select wire:model.live="periodeRevenus" class="w-full border rounded-md px-3 py-2 text-sm">
                            <option value="semaine">Cette semaine</option>
                            <option value="mois">Ce mois</option>
                            <option value="trimestre">Ce trimestre</option>
                            <option value="annee">Cette année</option>
                            <option value="personnalise">Personnalisé</option>
                        </select>
                    </div>
                    @if($this->periodeRevenus === 'personnalise')
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date début</label>
                            <input wire:model.live="dateDebutRevenus" type="date" class="w-full border rounded-md px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date fin</label>
                            <input wire:model.live="dateFinRevenus" type="date" class="w-full border rounded-md px-3 py-2 text-sm">
                        </div>
                    @endif
                </div>
            </div>
        @elseif($activeTab === 'depenses')
            <div class="bg-white rounded-lg shadow p-4 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Catégorie</label>
                        <select wire:model.live="categorieDepense" class="w-full border rounded-md px-3 py-2 text-sm">
                            <option value="">Toutes les catégories</option>
                            <option value="achat">🛒 Achats</option>
                            <option value="frais">🧾 Frais</option>
                            <option value="avance">💸 Avances</option>
                            <option value="paiement">💳 Paiements</option>
                            <option value="retrait">📤 Retraits</option>
                            <option value="transfert">🔄 Transferts</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Période</label>
                        <select wire:model.live="periodeDepenses" class="w-full border rounded-md px-3 py-2 text-sm">
                            <option value="semaine">Cette semaine</option>
                            <option value="mois">Ce mois</option>
                            <option value="trimestre">Ce trimestre</option>
                            <option value="annee">Cette année</option>
                            <option value="personnalise">Personnalisé</option>
                        </select>
                    </div>
                    @if($this->periodeDepenses === 'personnalise')
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date début</label>
                            <input wire:model.live="dateDebutDepenses" type="date" class="w-full border rounded-md px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date fin</label>
                            <input wire:model.live="dateFinDepenses" type="date" class="w-full border rounded-md px-3 py-2 text-sm">
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Contenu spécifique à chaque onglet -->
        @if($activeTab === 'suivi')
            <!-- Onglet Suivi Global -->
            <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
                <div class="px-4 py-5 border-b border-gray-200 sm:px-6">
                    <h3 class="text-lg font-medium text-gray-900">📊 Suivi Global des Transactions</h3>
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

        @elseif($activeTab === 'revenus')
            <!-- Onglet Revenus -->
            <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
                <div class="px-4 py-5 border-b border-gray-200 sm:px-6">
                    <h3 class="text-lg font-medium text-gray-900">💰 Liste des Revenus</h3>
                </div>
                
                <!-- Statistiques des revenus -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 p-4">
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <span class="text-2xl mr-3">💰</span>
                            <div>
                                <p class="text-sm font-medium text-green-800">Total Revenus</p>
                                <p class="text-lg font-semibold text-green-900">{{ number_format($this->totalRevenus, 0, ',', ' ') }} MGA</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <span class="text-2xl mr-3">📈</span>
                            <div>
                                <p class="text-sm font-medium text-blue-800">Revenus Moyens</p>
                                <p class="text-lg font-semibold text-blue-900">{{ number_format($this->revenuMoyen, 0, ',', ' ') }} MGA</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <span class="text-2xl mr-3">🔢</span>
                            <div>
                                <p class="text-sm font-medium text-purple-800">Nombre d'Opérations</p>
                                <p class="text-lg font-semibold text-purple-900">{{ $this->nombreRevenus }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tableau des revenus -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-green-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-green-800 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-green-800 uppercase tracking-wider">Référence</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-green-800 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-green-800 uppercase tracking-wider">Description</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-green-800 uppercase tracking-wider">De</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-green-800 uppercase tracking-wider">Montant</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-green-800 uppercase tracking-wider">Voyage</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-green-800 uppercase tracking-wider">Statut</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($this->revenus as $revenu)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ \Carbon\Carbon::parse($revenu->date)->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $revenu->reference }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @switch($revenu->type)
                                            @case('vente')
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">💰 Vente</span>
                                                @break
                                            @case('depot')
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">📥 Dépôt</span>
                                                @break
                                            @case('commission')
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">💼 Commission</span>
                                                @break
                                            @case('transfert')
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">🔄 Transfert</span>
                                                @break
                                            @default
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">{{ ucfirst($revenu->type) }}</span>
                                        @endswitch
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 max-w-xs">
                                        <div class="truncate" title="{{ $revenu->objet }}">
                                            {{ $revenu->objet }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $revenu->from_nom ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600">
                                        +{{ number_format($revenu->montant_mga, 0, ',', ' ') }} MGA
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if($revenu->voyage_id)
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                                🚗 {{ $revenu->voyage->reference ?? 'N/A' }}
                                            </span>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @switch($revenu->statut)
                                            @case('payee')
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">✅ Encaissé</span>
                                                @break
                                            @case('partiellement_payee')
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">⚠️ Partiel</span>
                                                @break
                                            @case('attente')
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">⏳ En attente</span>
                                                @break
                                            @case('confirme')
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">✅ Confirmé</span>
                                                @break
                                        @endswitch
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">
                                        💰 Aucun revenu trouvé pour cette période
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($this->revenus->hasPages())
                <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 sm:px-6">
                    {{ $this->revenus->links() }}
                </div>
                @endif
            </div>

        @elseif($activeTab === 'depenses')
            <!-- Onglet Dépenses -->
            <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
                <div class="px-4 py-5 border-b border-gray-200 sm:px-6">
                    <h3 class="text-lg font-medium text-gray-900">💸 Liste des Dépenses</h3>
                </div>
                
                <!-- Statistiques des dépenses -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 p-4">
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <span class="text-2xl mr-3">💸</span>
                            <div>
                                <p class="text-sm font-medium text-red-800">Total Dépenses</p>
                                <p class="text-lg font-semibold text-red-900">{{ number_format($this->totalDepenses, 0, ',', ' ') }} MGA</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <span class="text-2xl mr-3">📊</span>
                            <div>
                                <p class="text-sm font-medium text-orange-800">Dépense Moyenne</p>
                                <p class="text-lg font-semibold text-orange-900">{{ number_format($this->depenseMoyenne, 0, ',', ' ') }} MGA</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <span class="text-2xl mr-3">⏳</span>
                            <div>
                                <p class="text-sm font-medium text-yellow-800">En Attente</p>
                                <p class="text-lg font-semibold text-yellow-900">{{ number_format($this->depensesEnAttente, 0, ',', ' ') }} MGA</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <span class="text-2xl mr-3">🔢</span>
                            <div>
                                <p class="text-sm font-medium text-purple-800">Nombre d'Opérations</p>
                                <p class="text-lg font-semibold text-purple-900">{{ $this->nombreDepenses }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Répartition par catégorie -->
                <div class="bg-gray-50 rounded-lg p-4 mx-4 mb-4">
                    <h4 class="font-medium text-gray-900 mb-3">📈 Répartition par Catégorie</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @forelse($this->repartitionDepenses as $categorie => $data)
                            <div class="bg-white rounded-lg p-3 shadow-sm">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-700">
                                        @switch($categorie)
                                            @case('achat') 🛒 Achats @break
                                            @case('frais') 🧾 Frais @break
                                            @case('avance') 💸 Avances @break
                                            @case('paiement') 💳 Paiements @break
                                            @case('retrait') 📤 Retraits @break
                                            @case('transfert') 🔄 Transferts @break
                                            @default {{ ucfirst($categorie) }}
                                        @endswitch
                                    </span>
                                    <div class="text-right">
                                        <div class="text-sm font-semibold text-gray-900">{{ $data['count'] }} opérations</div>
                                        <div class="text-xs text-red-600">{{ number_format($data['total'], 0, ',', ' ') }} MGA</div>
                                    </div>
                                </div>
                                <div class="mt-2 bg-gray-200 rounded-full h-2">
                                    <div class="bg-red-500 h-2 rounded-full" style="width: {{ $this->totalDepenses > 0 ? ($data['total'] / $this->totalDepenses * 100) : 0 }}%"></div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-3 text-center text-gray-500">Aucune donnée disponible</div>
                        @endforelse
                    </div>
                </div>

                <!-- Tableau des dépenses -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-red-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-red-800 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-red-800 uppercase tracking-wider">Référence</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-red-800 uppercase tracking-wider">Catégorie</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-red-800 uppercase tracking-wider">Description</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-red-800 uppercase tracking-wider">Vers</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-red-800 uppercase tracking-wider">Montant</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-red-800 uppercase tracking-wider">Voyage</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-red-800 uppercase tracking-wider">Statut</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-red-800 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($this->depenses as $depense)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ \Carbon\Carbon::parse($depense->date)->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $depense->reference }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @switch($depense->type)
                                            @case('achat')
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">🛒 Achat</span>
                                                @break
                                            @case('frais')
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">🧾 Frais</span>
                                                @break
                                            @case('avance')
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">💸 Avance</span>
                                                @break
                                            @case('paiement')
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">💳 Paiement</span>
                                                @break
                                            @case('retrait')
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">📤 Retrait</span>
                                                @break
                                            @case('transfert')
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">🔄 Transfert</span>
                                                @break
                                            @default
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">{{ ucfirst($depense->type) }}</span>
                                        @endswitch
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 max-w-xs">
                                        <div class="truncate" title="{{ $depense->objet }}">
                                            {{ $depense->objet }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $depense->to_nom ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-red-600">
                                        -{{ number_format($depense->montant_mga, 0, ',', ' ') }} MGA
                                        @if($depense->statut === 'partiellement_payee' && $depense->reste_a_payer)
                                            <div class="text-xs text-yellow-600">
                                                Reste: {{ number_format($depense->reste_a_payer, 0, ',', ' ') }} MGA
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if($depense->voyage_id)
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                                🚗 {{ $depense->voyage->reference ?? 'N/A' }}
                                            </span>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @switch($depense->statut)
                                            @case('payee')
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">✅ Payée</span>
                                                @break
                                            @case('partiellement_payee')
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">⚠️ Partielle</span>
                                                @break
                                            @case('attente')
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">⏳ En attente</span>
                                                @break
                                            @case('confirme')
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">✅ Confirmé</span>
                                                @break
                                            @case('annule')
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">❌ Annulée</span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <button wire:click="editTransaction({{ $depense->id }})" class="text-blue-600 hover:text-blue-900 text-xs">
                                                ✏️ Modifier
                                            </button>
                                            @if($depense->statut === 'attente')
                                                <button wire:click="marquerPayee({{ $depense->id }})" class="text-green-600 hover:text-green-900 text-xs">
                                                    ✅ Payer
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="px-6 py-4 text-center text-sm text-gray-500">
                                        💸 Aucune dépense trouvée pour cette période
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($this->depenses->hasPages())
                <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 sm:px-6">
                    {{ $this->depenses->links() }}
                </div>
                @endif
            </div>
        @endif

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