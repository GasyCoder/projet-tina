{{-- resources/views/livewire/finance/tabs/transactions.blade.php - CORRIGÉ --}}
<div class="space-y-4">
    <!-- Filtres avec VOS vrais types -->
    <div class="bg-gray-50 p-4 rounded-lg">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Recherche</label>
                <input wire:model.live="searchTerm" type="text" placeholder="Référence, objet..." class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Type</label>
                <select wire:model.live="filterType" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm">
                    <option value="">Tous</option>
                    <option value="achat">🛒 Achat</option>
                    <option value="vente">💰 Vente</option>
                    <option value="transfert">🔄 Transfert</option>
                    <option value="frais">🧾 Frais</option>
                    <option value="commission">💼 Commission</option>
                    <option value="paiement">💳 Paiement</option>
                    <option value="avance">💸 Avance</option>
                    <option value="depot">📥 Dépôt</option>
                    <option value="retrait">📤 Retrait</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Statut</label>
                <select wire:model.live="filterStatut" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm">
                    <option value="">Tous</option>
                    <option value="attente">⏳ En attente</option>
                    <option value="confirme">✅ Confirmé</option>
                    <option value="annule">❌ Annulé</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Personne</label>
                <input wire:model.live="filterPersonne" type="text" placeholder="Nom..." class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm">
            </div>
            <div class="flex items-end">
                <button wire:click="$set('searchTerm', ''); $set('filterType', ''); $set('filterStatut', ''); $set('filterPersonne', '')" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm text-gray-700 bg-white hover:bg-gray-50">
                    🔄 Reset
                </button>
            </div>
        </div>
    </div>

    <!-- Liste des transactions -->
    @if($transactions->count() > 0)
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <ul class="divide-y divide-gray-200">
                @foreach($transactions as $transaction)
                    <li class="px-6 py-4 hover:bg-gray-50">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <!-- Type badge avec VOS vrais types -->
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                    {{ in_array($transaction->type, ['vente', 'depot', 'transfert']) ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    @switch($transaction->type)
                                        @case('achat') 🛒 Achat @break
                                        @case('vente') 💰 Vente @break
                                        @case('transfert') 🔄 Transfert @break
                                        @case('frais') 🧾 Frais @break
                                        @case('commission') 💼 Commission @break
                                        @case('paiement') 💳 Paiement @break
                                        @case('avance') 💸 Avance @break
                                        @case('depot') 📥 Dépôt @break
                                        @case('retrait') 📤 Retrait @break
                                        @default {{ ucfirst($transaction->type) }}
                                    @endswitch
                                </span>

                                <!-- Infos principales -->
                                <div>
                                    <div class="flex items-center space-x-2">
                                        <p class="text-sm font-medium text-gray-900">{{ $transaction->reference }}</p>
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                            {{ $transaction->statut === 'confirme' ? 'bg-green-100 text-green-800' : 
                                               ($transaction->statut === 'annule' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                            @switch($transaction->statut)
                                                @case('confirme') ✅ Confirmé @break
                                                @case('attente') ⏳ Attente @break
                                                @case('annule') ❌ Annulé @break
                                            @endswitch
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600">{{ $transaction->objet }}</p>
                                    <div class="flex items-center space-x-4 mt-1 text-xs text-gray-500">
                                        <span>📅 {{ $transaction->date->format('d/m/Y') }}</span>
                                        @if($transaction->from_nom)
                                            <span>👤 {{ $transaction->from_nom }}</span>
                                        @endif
                                        @if($transaction->to_nom)
                                            <span>→ {{ $transaction->to_nom }}</span>
                                        @endif
                                        <span>
                                            @switch($transaction->mode_paiement)
                                                @case('especes') 💵 Espèces @break
                                                @case('mobile_money') 📱 Mobile Money @break
                                                @case('banque') 🏦 Banque @break
                                                @case('credit') 💳 Crédit @break
                                            @endswitch
                                        </span>
                                        @if($transaction->voyage)
                                            <span>🚛 {{ $transaction->voyage->reference }}</span>
                                        @endif
                                        @if($transaction->from_compte)
                                            <span>📊 {{ $transaction->from_compte }}</span>
                                        @endif
                                        @if($transaction->to_compte && $transaction->to_compte !== $transaction->from_compte)
                                            <span>→ {{ $transaction->to_compte }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Montant et actions -->
                            <div class="flex items-center space-x-4">
                                <div class="text-right">
                                    <p class="text-lg font-semibold text-{{ in_array($transaction->type, ['vente', 'depot', 'transfert']) ? 'green' : 'red' }}-600">
                                        {{ in_array($transaction->type, ['vente', 'depot', 'transfert']) ? '+' : '-' }}{{ $transaction->montant_mga_formatted }}
                                    </p>
                                </div>
                                
                                <div class="flex items-center space-x-2">
                                    @if($transaction->statut === 'attente')
                                        <button wire:click="confirmerTransaction({{ $transaction->id }})" 
                                                wire:confirm="Confirmer cette transaction ?"
                                                class="text-green-600 hover:text-green-900">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </button>
                                    @endif

                                    <button wire:key='edit-transaction-{{ $transaction->id }}' wire:click="editTransaction({{ $transaction->id }})" 
                                            class="text-blue-600 hover:text-blue-900">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>

                                    <button wire:key='delete-transaction-{{ $transaction->id }}' wire:click="deleteTransaction({{ $transaction->id }})" 
                                            wire:confirm="Supprimer cette transaction ?"
                                            class="text-red-600 hover:text-red-900">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        @if($transaction->observation)
                            <div class="mt-2 text-sm text-gray-600 bg-gray-50 p-2 rounded">
                                💬 {{ $transaction->observation }}
                            </div>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $transactions->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune transaction</h3>
            <p class="mt-1 text-sm text-gray-500">Commencez par ajouter une nouvelle transaction.</p>
        </div>
    @endif
</div>