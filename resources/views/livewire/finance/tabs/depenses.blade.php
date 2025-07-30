<!-- /home/kananavy/Documents/DevGGasy/projet-tina/resources/views/livewire/finance/tabs/depenses.blade.php -->
<div class="min-h-screen bg-gray-50 p-6">
    <div class="max-w-7xl mx-auto">
        <!-- En-tête -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold text-gray-900">💸 Gestion des Dépenses</h1>
                <nav class="flex space-x-4">
                    <a href="{{ route('finance.suivi-transactions') }}" class="text-gray-600 hover:text-blue-600 px-3 py-2 text-sm font-medium">
                        📊 Suivi Global
                    </a>
                    <a href="{{ route('finance.revenus') }}" class="text-gray-600 hover:text-blue-600 px-3 py-2 text-sm font-medium">
                        💰 Revenus
                    </a>
                    <span class="text-blue-600 border-b-2 border-blue-500 px-3 py-2 text-sm font-medium">
                        💸 Dépenses
                    </span>
                </nav>
            </div>
        </div>

        <!-- Statistiques des dépenses -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 shadow">
                <div class="flex items-center">
                    <span class="text-2xl mr-3">💸</span>
                    <div>
                        <p class="text-sm font-medium text-red-800">Total Dépenses</p>
                        <p class="text-lg font-semibold text-red-900">{{ number_format($this->totalDepenses, 0, ',', ' ') }} MGA</p>
                    </div>
                </div>
            </div>

            <div class="bg-orange-50 border border-orange-200 rounded-lg p-4 shadow">
                <div class="flex items-center">
                    <span class="text-2xl mr-3">📊</span>
                    <div>
                        <p class="text-sm font-medium text-orange-800">Dépense Moyenne</p>
                        <p class="text-lg font-semibold text-orange-900">{{ number_format($this->depenseMoyenne, 0, ',', ' ') }} MGA</p>
                    </div>
                </div>
            </div>

            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 shadow">
                <div class="flex items-center">
                    <span class="text-2xl mr-3">⏳</span>
                    <div>
                        <p class="text-sm font-medium text-yellow-800">En Attente</p>
                        <p class="text-lg font-semibold text-yellow-900">{{ number_format($this->depensesEnAttente, 0, ',', ' ') }} MGA</p>
                    </div>
                </div>
            </div>

            <div class="bg-purple-50 border border-purple-200 rounded-lg p-4 shadow">
                <div class="flex items-center">
                    <span class="text-2xl mr-3">🔢</span>
                    <div>
                        <p class="text-sm font-medium text-purple-800">Nombre d'Opérations</p>
                        <p class="text-lg font-semibold text-purple-900">{{ $this->nombreDepenses }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtres des dépenses -->
        <div class="bg-white rounded-lg shadow p-4 mb-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">🔍 Filtres</h3>
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
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                    <select wire:model.live="statutDepense" class="w-full border rounded-md px-3 py-2 text-sm">
                        <option value="">Tous les statuts</option>
                        <option value="payee">✅ Payée</option>
                        <option value="partiellement_payee">⚠️ Partielle</option>
                        <option value="attente">⏳ En attente</option>
                        <option value="confirme">✅ Confirmé</option>
                        <option value="annule">❌ Annulée</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Répartition par catégorie -->
        <div class="bg-white rounded-lg shadow p-4 mb-6">
            <h4 class="font-medium text-gray-900 mb-4">📈 Répartition par Catégorie</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($this->repartitionDepenses as $categorie => $data)
                    <div class="bg-gray-50 rounded-lg p-3 border">
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
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-4 py-5 border-b border-gray-200 sm:px-6 bg-red-50">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium text-red-900">💸 Liste des Dépenses</h3>
                    <button class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        ➕ Nouvelle Dépense
                    </button>
                </div>
            </div>
            
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