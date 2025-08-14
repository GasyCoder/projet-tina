{{-- resources/views/livewire/finance/tabs/transactions.blade.php --}}
<div class="space-y-6"
     x-data="{
       open: null,
       toggle(id) { this.open = (this.open === id) ? null : id }
     }">

    <!-- Filtres optimisés -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
        <div class="flex flex-col lg:flex-row lg:items-center gap-4">
            <div class="flex-1">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input wire:model.live="searchTerm" type="text"
                           placeholder="Rechercher par référence, objet..."
                           class="pl-10 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>

            <div class="flex gap-3">
                <select wire:model.live="filterDate"
                        class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Toutes les dates</option>
                    <option value="today">Aujourd'hui</option>
                    <option value="week">Cette semaine</option>
                    <option value="month">Ce mois</option>
                    <option value="year">Cette année</option>
                </select>

                <button wire:click="$refresh" class="px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 transition-colors" title="Rafraîchir">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    @if ($transactions->count() > 0)
        <!-- Liste des transactions -->
        <div class="space-y-4">
            @foreach ($transactions as $transaction)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden transition-all duration-200 hover:shadow-md">
                    <!-- Ligne principale -->
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4 cursor-pointer flex-1" 
                                 @click="toggle({{ $transaction->id }})" 
                                 :aria-expanded="(open === {{ $transaction->id }})">
                                <!-- Badge type -->
                                <div class="flex-shrink-0">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                        {{ in_array($transaction->type, ['vente', 'depot']) ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                        @if($transaction->type === 'vente') 💰 Vente
                                        @elseif($transaction->type === 'achat') 🛒 Achat
                                        @else ✨ {{ ucfirst($transaction->type) }}
                                        @endif
                                    </span>
                                </div>

                                <!-- Infos principales -->
                                <div>
                                    <div class="font-semibold text-gray-900 dark:text-gray-100">
                                        {{ $transaction->reference }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $transaction->objet ?: 'Transaction' }} • {{ $transaction->created_at->format('d/m/Y') }}
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center space-x-4">
                                <!-- Montant -->
                                <div class="text-right cursor-pointer" @click="toggle({{ $transaction->id }})">
                                    <div class="text-xl font-bold
                                        {{ in_array($transaction->type, ['vente', 'depot']) ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">
                                        {{ in_array($transaction->type, ['vente', 'depot']) ? '+' : '-' }}{{ $transaction->montant_mga_formatted }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $transaction->mode_paiement_formatted ?? $transaction->mode_paiement }}
                                    </div>
                                </div>

                                <!-- Statut -->
                                <div class="flex-shrink-0 cursor-pointer" @click="toggle({{ $transaction->id }})">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $transaction->statut ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' }}">
                                        {{ $transaction->statut ? '✅ Confirmé' : '⏳ En attente' }}
                                    </span>
                                </div>

                                <!-- Actions -->
                                <div class="flex items-center space-x-2">
                                    @if (!$transaction->statut)
                                        <button
                                            type="button"
                                            wire:click="confirmerTransaction({{ $transaction->id }})"
                                            class="p-2 text-emerald-600 hover:text-emerald-800 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 rounded-lg transition-colors"
                                            title="Confirmer">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </button>
                                    @endif

                                    <button
                                        type="button"
                                        wire:click="editTransaction({{ $transaction->id }})"
                                        class="p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors"
                                        title="Modifier">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>

                                    <button
                                        type="button"
                                        wire:click="deleteTransaction({{ $transaction->id }})"
                                        wire:confirm="Êtes-vous sûr de vouloir supprimer cette transaction ?"
                                        class="p-2 text-red-600 hover:text-red-800 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors"
                                        title="Supprimer">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>

                                    <button
                                        type="button"
                                        @click="toggle({{ $transaction->id }})"
                                        class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded-lg transition-all"
                                        title="Détails">
                                        <svg :class="{ 'rotate-180': open === {{ $transaction->id }} }"
                                             class="w-5 h-5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Détails expandables -->
                    <div x-show="open === {{ $transaction->id }}"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 max-h-0"
                         x-transition:enter-end="opacity-100 max-h-screen"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 max-h-screen"
                         x-transition:leave-end="opacity-0 max-h-0"
                         class="border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50 overflow-hidden">

                        <div class="p-6 space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

                                <!-- Informations générales -->
                                <div class="space-y-3">
                                    <h4 class="flex items-center font-semibold text-gray-900 dark:text-gray-100">
                                        <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center mr-3">
                                            <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        </div>
                                        Informations
                                    </h4>
                                    <div class="space-y-2 text-sm">
                                        @php
                                            $generalInfo = [
                                                'Référence' => $transaction->reference,
                                                'Date' => $transaction->created_at?->format('d/m/Y à H:i'),
                                                'Objet' => $transaction->objet ?: 'Non spécifié'
                                            ];
                                        @endphp
                                        @foreach($generalInfo as $label => $value)
                                            <div class="flex justify-between">
                                                <span class="text-gray-600 dark:text-gray-400">{{ $label }} :</span>
                                                <span class="font-medium text-gray-900 dark:text-gray-100">{{ $value ?: '-' }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Participants -->
                                <div class="space-y-3">
                                    <h4 class="flex items-center font-semibold text-gray-900 dark:text-gray-100">
                                        <div class="w-8 h-8 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center mr-3">
                                            <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                            </svg>
                                        </div>
                                        Participants
                                    </h4>
                                    <div class="space-y-2 text-sm">
                                        @php
                                            $participants = [
                                                'Émetteur' => $transaction->from_nom,
                                                'Bénéficiaire' => $transaction->to_nom,
                                                'Compte source' => $transaction->from_compte,
                                                'Compte destination' => $transaction->to_compte
                                            ];
                                        @endphp
                                        @foreach($participants as $label => $value)
                                            @if($value)
                                                <div class="flex justify-between">
                                                    <span class="text-gray-600 dark:text-gray-400">{{ $label }} :</span>
                                                    <span class="font-medium text-gray-900 dark:text-gray-100 text-right max-w-32 truncate" title="{{ $value }}">{{ $value }}</span>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Finances -->
                                <div class="space-y-3">
                                    <h4 class="flex items-center font-semibold text-gray-900 dark:text-gray-100">
                                        <div class="w-8 h-8 bg-emerald-100 dark:bg-emerald-900 rounded-lg flex items-center justify-center mr-3">
                                            <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                            </svg>
                                        </div>
                                        Finances
                                    </h4>
                                    <div class="space-y-2 text-sm">
                                        @php
                                            $finances = [
                                                'Montant' => $transaction->montant_mga_formatted,
                                                'Mode paiement' => $transaction->mode_paiement_formatted ?? $transaction->mode_paiement,
                                                'Frais' => $transaction->frais ? number_format($transaction->frais, 0, ',', ' ') . ' MGA' : null
                                            ];
                                        @endphp
                                        @foreach($finances as $label => $value)
                                            @if($value)
                                                <div class="flex justify-between">
                                                    <span class="text-gray-600 dark:text-gray-400">{{ $label }} :</span>
                                                    <span class="font-medium 
                                                        {{ $label === 'Montant' ? (in_array($transaction->type, ['vente', 'depot']) ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400') : 'text-gray-900 dark:text-gray-100' }}">
                                                        @if($label === 'Montant')
                                                            {{ in_array($transaction->type, ['vente', 'depot']) ? '+' : '-' }}{{ $value }}
                                                        @else
                                                            {{ $value }}
                                                        @endif
                                                    </span>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- Observations -->
                            @if($transaction->observation)
                                <div class="pt-4 border-t border-gray-200 dark:border-gray-600">
                                    <h4 class="flex items-center font-semibold text-gray-900 dark:text-gray-100 mb-3">
                                        <div class="w-8 h-8 bg-yellow-100 dark:bg-yellow-900 rounded-lg flex items-center justify-center mr-3">
                                            <svg class="w-4 h-4 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                            </svg>
                                        </div>
                                        Observations
                                    </h4>
                                    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border-l-4 border-blue-500 text-gray-700 dark:text-gray-300">
                                        {{ $transaction->observation }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $transactions->links() }}
        </div>
    @else
        <!-- État vide -->
        <div class="text-center py-12 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="mx-auto h-12 w-12 text-gray-400 mb-4">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-1">Aucune transaction</h3>
            <p class="text-gray-500 dark:text-gray-400">Commencez par ajouter une nouvelle transaction.</p>
        </div>
    @endif
</div>