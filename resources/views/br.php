        <!-- Contenu spécifique à chaque onglet -->
        {{-- @if($activeTab === 'suivi')
            <!-- Onglet Suivi Global -->


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
        @endif --}}





{{/*  revenues  */}}

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
        @include('livewire.finance.tabs.suivi-globale', [
            'dateDebut' => $dateDebut,
            'dateFin' => $dateFin,
            'filterType' => $filterType,
            'filterStatut' => $filterStatut
        ])
        @elseif($activeTab === 'revenus')
            @include('livewire.finance.tabs.revenus', [
                'periodeRevenus' => $periodeRevenus,
                'dateDebutRevenus' => $dateDebutRevenus,
                'dateFinRevenus' => $dateFinRevenus
            ])
        @elseif($activeTab === 'depenses')
          @include('livewire.finance.tabs.depenses', [
              'periodeDepenses' => $periodeDepenses,
              'dateDebutDepenses' => $dateDebutDepenses,
              'dateFinDepenses' => $dateFinDepenses
          ])
        @endif
    </div>

    <!-- Modals -->
    @include('livewire.finance.modals.transaction-modal', [
        'voyages' => $voyages,
        'comptes' => $comptes
    ])
    
    @include('livewire.finance.modals.compte-modal')
</div>