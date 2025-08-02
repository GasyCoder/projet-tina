{{-- resources/views/livewire/finance/tabs/transactions.blade.php - VERSION TABLEAU --}}
<div class="space-y-4" x-data="{ expandedRows: {} }">
    <!-- Alpine.js requis -->
    <div x-data="{ open: false }" class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">

        <!-- Bouton toggle pour mobile -->
        <div class="md:hidden mb-4">
            <button @click="open = !open"
                class="w-full px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm text-gray-700 dark:text-gray-300 flex justify-between items-center">
                <span>🎛️ Filtres</span>
                <svg :class="{ 'rotate-180': open }" class="w-4 h-4 transition-transform" fill="none"
                    stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
        </div>

        <!-- Zone de filtres -->
        <div :class="{ 'block': open, 'hidden': !open }" class="md:grid md:grid-cols-5 gap-4" x-cloak>
            <div class="mt-4 md:mt-0">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Recherche</label>
                <input wire:model.live="searchTerm" type="text" placeholder="Référence, objet..."
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm text-sm dark:bg-gray-700 dark:text-gray-300">
            </div>
            <div class="mt-4 md:mt-0">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Type</label>
                <select wire:model.live="filterType"
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm text-sm dark:bg-gray-700 dark:text-gray-300">
                    <option value="">Tous</option>
                    <option value="achat">🛒 Achat</option>
                    <option value="vente">💰 Vente</option>
                    <option value="retrait">📤 Autres</option>
                </select>
            </div>
            <div class="mt-4 md:mt-0">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Statut</label>
                <select wire:model.live="filterStatut"
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm text-sm dark:bg-gray-700 dark:text-gray-300">
                    <option value="">Tous</option>
                    <option value="attente">⏳ En attente</option>
                    <option value="confirme">✅ Confirmé</option>
                    <option value="annule">❌ Annulé</option>
                </select>
            </div>
            <div class="mt-4 md:mt-0">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Personne</label>
                <input wire:model.live="filterPersonne" type="text" placeholder="Nom..."
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm text-sm dark:bg-gray-700 dark:text-gray-300">
            </div>
            <div class="mt-4 md:mt-0 flex items-end">
                <button
                    wire:click="$set('searchTerm', ''); $set('filterType', ''); $set('filterStatut', ''); $set('filterPersonne', '')"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    🔄 Reset
                </button>
            </div>
        </div>
    </div>

    <!-- Tableau des transactions -->
    @if ($transactions->count() > 0)
        <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Référence/Type
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Date
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Montant
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Paiement
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Statut
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($transactions as $transaction)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700" x-data="{ isExpanded: false }" x-init="$watch('expandedRows[{{ $transaction->id }}]', value => isExpanded = value)">
                                <td class="px-6 py-4 whitespace-nowrap cursor-pointer"
                                    @click="expandedRows[{{ $transaction->id }}] = !expandedRows[{{ $transaction->id }}]">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <span
                                                class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                                {{ in_array($transaction->type, ['vente', 'depot', 'transfert']) ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                                @switch($transaction->type)
                                                    @case('achat')
                                                        🛒
                                                    @break

                                                    @case('vente')
                                                        💰
                                                    @break
                                                    @case('autre')
                                                        📤
                                                    @break
                                                @endswitch
                                            </span>
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $transaction->reference }}
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400 truncate max-w-32">
                                                @if(!empty($transaction->objet))  
                                                    {{ $transaction->objet }}
                                                @else
                                                    Autre transaction
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap cursor-pointer"
                                    @click="expandedRows[{{ $transaction->id }}] = !expandedRows[{{ $transaction->id }}]">
                                    <div class="text-sm text-gray-900 dark:text-gray-100">{{ $transaction->created_at->format('d/m/Y') }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $transaction->created_at->diffForHumans() }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap cursor-pointer"
                                    @click="expandedRows[{{ $transaction->id }}] = !expandedRows[{{ $transaction->id }}]">
                                    <span
                                        class="text-lg font-semibold {{ in_array($transaction->type, ['vente', 'depot', 'transfert']) ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                        {{ in_array($transaction->type, ['vente', 'depot', 'transfert']) ? '+' : '-' }}{{ $transaction->montant_mga_formatted }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap cursor-pointer"
                                    @click="expandedRows[{{ $transaction->id }}] = !expandedRows[{{ $transaction->id }}]">
                                    <div class="text-sm text-gray-900 dark:text-gray-100">
                                        @switch($transaction->mode_paiement)
                                            @case('especes')
                                                💵 Espèces
                                            @break

                                            @case('mobile_money')
                                                📱 Mobile
                                            @break

                                            @case('banque')
                                                🏦 Banque
                                            @break

                                            @case('credit')
                                                💳 Crédit
                                            @break

                                            @default
                                                {{ $transaction->mode_paiement }}
                                            @break
                                        @endswitch
                                    </div>
                                    @if ($transaction->type_paiement)
                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $transaction->type_paiement }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap cursor-pointer"
                                    @click="expandedRows[{{ $transaction->id }}] = !expandedRows[{{ $transaction->id }}]">
                                    <span
                                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                        {{ $transaction->statut === 'confirme'
                                            ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'
                                            : ($transaction->statut === 'annule'
                                                ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
                                                : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200') }}">
                                        @switch($transaction->statut)
                                            @case('confirme')
                                                ✅ Confirmé
                                            @break

                                            @case('attente')
                                                ⏳ Attente
                                            @break

                                            @case('annule')
                                                ❌ Annulé
                                            @break
                                        @endswitch
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        @if ($transaction->statut === 'attente')
                                            <button wire:click.prevent="confirmerTransaction({{ $transaction->id }})"
                                                onclick="event.stopPropagation();"
                                                wire:confirm="Confirmer cette transaction ?"
                                                class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 p-1 rounded hover:bg-green-50 dark:hover:bg-gray-700"
                                                title="Confirmer">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </button>
                                        @endif

                                        <button wire:key="edit-transaction-{{ $transaction->id }}"
                                            wire:click.prevent="editTransaction({{ $transaction->id }})"
                                            onclick="event.stopPropagation();"
                                            class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 p-1 rounded hover:bg-blue-50 dark:hover:bg-gray-700"
                                            title="Modifier">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>

                                        <button wire:key="delete-transaction-{{ $transaction->id }}"
                                            wire:click.prevent="deleteTransaction({{ $transaction->id }})"
                                            onclick="event.stopPropagation();"
                                            wire:confirm="Supprimer cette transaction ?"
                                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 p-1 rounded hover:bg-red-50 dark:hover:bg-gray-700"
                                            title="Supprimer">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>

                                        <!-- Bouton détails -->
                                        <button
                                            @click="expandedRows[{{ $transaction->id }}] = !expandedRows[{{ $transaction->id }}]"
                                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 p-1 rounded hover:bg-gray-50 dark:hover:bg-gray-700"
                                            title="Détails">
                                            <svg :class="{ 'rotate-180': expandedRows[{{ $transaction->id }}] }"
                                                class="w-4 h-4 transition-transform" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Ligne de détails expandable -->
                            <tr x-show="expandedRows[{{ $transaction->id }}]"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 transform scale-95"
                                x-transition:enter-end="opacity-100 transform scale-100" class="bg-gray-50 dark:bg-gray-700">
                                <td colspan="10" class="px-6 py-4">
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 text-sm">

                                        <!-- 1. INFORMATIONS GÉNÉRALES -->
                                        <div class="space-y-3">
                                            <h4
                                                class="font-bold text-gray-900 dark:text-gray-100 text-base border-b border-gray-300 dark:border-gray-600 pb-2 flex items-center">
                                                <span
                                                    class="w-6 h-6 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center mr-2 text-xs">📋</span>
                                                Informations générales
                                            </h4>
                                            <div class="space-y-2 text-gray-600 dark:text-gray-300">
                                                <div class="flex justify-between">
                                                    <strong class="text-gray-700 dark:text-gray-300">Référence :</strong>
                                                    <span
                                                        class="font-medium dark:text-gray-200">{{ $transaction->reference ?? '-' }}</span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <strong class="text-gray-700 dark:text-gray-300">Type :</strong>
                                                    <span class="font-medium dark:text-gray-200">
                                                        @switch($transaction->type)
                                                            @case('achat')
                                                                🛒 Achat
                                                            @break

                                                            @case('vente')
                                                                💰 Vente
                                                            @break

                                                            @case('autre')
                                                                ✨ Autre
                                                            @break

                                                            @default
                                                                {{ ucfirst($transaction->type ?? '-') }}
                                                            @break
                                                        @endswitch
                                                    </span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <strong class="text-gray-700 dark:text-gray-300">Date transaction :</strong>
                                                    <span>{{ $transaction->date ? $transaction->date->format('d/m/Y à H:i') : ($transaction->created_at ? $transaction->created_at->format('d/m/Y à H:i') : '-') }}</span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <strong class="text-gray-700 dark:text-gray-300">Créé le :</strong>
                                                    <span>{{ $transaction->created_at ? $transaction->created_at->format('d/m/Y à H:i') : '-' }}</span>
                                                </div>
                                                @if ($transaction->updated_at && $transaction->updated_at != $transaction->created_at)
                                                    <div class="flex justify-between">
                                                        <strong class="text-gray-700 dark:text-gray-300">Modifié le :</strong>
                                                        <span>{{ $transaction->updated_at->format('d/m/Y à H:i') }}</span>
                                                    </div>
                                                @endif
                                                <div class="flex justify-between">
                                                    <strong class="text-gray-700 dark:text-gray-300">Objet :</strong>
                                                    <span class="text-right max-w-32 truncate"
                                                        title="{{ $transaction->objet ?? 'Aucun objet spécifié' }}">
                                                        {{ $transaction->objet ?? '-' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- 2. DÉTAILS PRODUIT -->
                                        <div class="space-y-3">
                                            <h4
                                                class="font-bold text-gray-900 dark:text-gray-100 text-base border-b border-gray-300 dark:border-gray-600 pb-2 flex items-center">
                                                <span
                                                    class="w-6 h-6 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center mr-2 text-xs">📦</span>
                                                Détails produit
                                            </h4>
                                            <div class="space-y-2 text-gray-600 dark:text-gray-300">
                                                <div class="flex justify-between">
                                                    <strong class="text-gray-700 dark:text-gray-300">Produit :</strong>
                                                    <span class="text-right max-w-32 truncate"
                                                        title="{{ $transaction->produit ? $transaction->produit->nom_complet ?? $transaction->produit->nom : 'Aucun produit' }}">
                                                        {{ $transaction->produit ? $transaction->produit->nom_complet ?? ($transaction->produit->nom ?? 'N/A') : '-' }}
                                                    </span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <strong class="text-gray-700 dark:text-gray-300">Unité :</strong>
                                                    <span>{{ $transaction->produit && $transaction->produit->unite ? $transaction->produit->unite : '-' }}</span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <strong class="text-gray-700 dark:text-gray-300">Quantité :</strong>
                                                    <span>
                                                        @if ($transaction->quantite)
                                                            {{ number_format($transaction->quantite, 2, ',', ' ') }}
                                                            @if ($transaction->produit && $transaction->produit->unite)
                                                                {{ $transaction->produit->unite }}
                                                            @endif
                                                        @else
                                                            -
                                                        @endif
                                                    </span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <strong class="text-gray-700 dark:text-gray-300">Prix unitaire :</strong>
                                                    <span>
                                                        {{ $transaction->prix_unitaire_mga ? number_format($transaction->prix_unitaire_mga, 0, ',', ' ') . ' MGA' : '-' }}
                                                    </span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <strong class="text-gray-700 dark:text-gray-300">Poids total :</strong>
                                                    <span>
                                                        {{ $transaction->poids ? number_format($transaction->poids, 2, ',', ' ') . ' kg' : '-' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- 3. VOYAGE ET LIVRAISON -->
                                        <div class="space-y-3">
                                            <h4
                                                class="font-bold text-gray-900 dark:text-gray-100 text-base border-b border-gray-300 dark:border-gray-600 pb-2 flex items-center">
                                                <span
                                                    class="w-6 h-6 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center mr-2 text-xs">🚛</span>
                                                Voyage & Livraison
                                            </h4>
                                            <div class="space-y-2 text-gray-600 dark:text-gray-300">
                                                <div class="flex justify-between">
                                                    <strong class="text-gray-700 dark:text-gray-300">Voyage :</strong>
                                                    <span>{{ $transaction->voyage ? $transaction->voyage->reference ?? 'N/A' : '-' }}</span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <strong class="text-gray-700 dark:text-gray-300">Date voyage :</strong>
                                                    <span>
                                                        {{ $transaction->voyage && $transaction->voyage->date ? $transaction->voyage->date->format('d/m/Y') : '-' }}
                                                    </span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <strong class="text-gray-700 dark:text-gray-300">Déchargements :</strong>
                                                    <span>
                                                        {{ $transaction->dechargements && $transaction->dechargements->count() > 0 ? $transaction->dechargements->count() . ' éléments' : '-' }}
                                                    </span>
                                                </div>
                                                <div class="flex justify-between items-start">
                                                    <strong class="text-gray-700 dark:text-gray-300">Lieux :</strong>
                                                    <span class="text-right max-w-32 truncate"
                                                        title="{{ $transaction->lieux ?? 'Aucun lieu spécifié' }}">
                                                        {{ $transaction->lieux ?? '-' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- 4. PARTICIPANTS ET COMPTES -->
                                        <div class="space-y-3">
                                            <h4
                                                class="font-bold text-gray-900 dark:text-gray-100 text-base border-b border-gray-300 dark:border-gray-600 pb-2 flex items-center">
                                                <span
                                                    class="w-6 h-6 bg-orange-100 dark:bg-orange-900 rounded-lg flex items-center justify-center mr-2 text-xs">👥</span>
                                                Participants & Comptes
                                            </h4>
                                            <div class="space-y-2 text-gray-600 dark:text-gray-300">
                                                <div class="flex justify-between items-start">
                                                    <strong class="text-gray-700 dark:text-gray-300">Émetteur :</strong>
                                                    <span class="text-right max-w-32 truncate"
                                                        title="{{ $transaction->from_nom ?? 'Aucun émetteur' }}">
                                                        {{ $transaction->from_nom ?? '-' }}
                                                    </span>
                                                </div>
                                                <div class="flex justify-between items-start">
                                                    <strong class="text-gray-700 dark:text-gray-300">Bénéficiaire :</strong>
                                                    <span class="text-right max-w-32 truncate"
                                                        title="{{ $transaction->to_nom ?? 'Aucun bénéficiaire' }}">
                                                        {{ $transaction->to_nom ?? '-' }}
                                                    </span>
                                                </div>
                                                <div class="flex justify-between items-start">
                                                    <strong class="text-gray-700 dark:text-gray-300">Compte source :</strong>
                                                    <span class="text-right max-w-32 truncate"
                                                        title="{{ $transaction->from_compte ?? 'Aucun compte source' }}">
                                                        {{ $transaction->from_compte ?? '-' }}
                                                    </span>
                                                </div>
                                                <div class="flex justify-between items-start">
                                                    <strong class="text-gray-700 dark:text-gray-300">Compte destination :</strong>
                                                    <span class="text-right max-w-32 truncate"
                                                        title="{{ $transaction->to_compte ?? 'Aucun compte destination' }}">
                                                        {{ $transaction->to_compte ?? '-' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- 5. INFORMATIONS FINANCIÈRES -->
                                        <div class="space-y-3">
                                            <h4
                                                class="font-bold text-gray-900 dark:text-gray-100 text-base border-b border-gray-300 dark:border-gray-600 pb-2 flex items-center">
                                                <span
                                                    class="w-6 h-6 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center mr-2 text-xs">💰</span>
                                                Finances
                                            </h4>
                                            <div class="space-y-2 text-gray-600 dark:text-gray-300">
                                                <div class="flex justify-between">
                                                    <strong class="text-gray-700 dark:text-gray-300">Montant :</strong>
                                                    <span
                                                        class="font-bold text-lg {{ $transaction->type === 'vente' ? 'text-green-600 dark:text-green-400' : ($transaction->type === 'achat' ? 'text-red-600 dark:text-red-400' : 'text-gray-600 dark:text-gray-400') }}">
                                                        {{ $transaction->type === 'vente' ? '+' : ($transaction->type === 'achat' ? '-' : '') }}{{ $transaction->montant_mga_formatted ?? '0 MGA' }}
                                                    </span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <strong class="text-gray-700 dark:text-gray-300">Devise :</strong>
                                                    <span>{{ $transaction->devise ?? 'MGA' }}</span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <strong class="text-gray-700 dark:text-gray-300">Taux de change :</strong>
                                                    <span>{{ $transaction->taux_change && $transaction->taux_change != 1 ? $transaction->taux_change : '-' }}</span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <strong class="text-gray-700 dark:text-gray-300">Frais :</strong>
                                                    <span>{{ $transaction->frais ? number_format($transaction->frais, 0, ',', ' ') . ' MGA' : '-' }}</span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <strong class="text-gray-700 dark:text-gray-300">Reste à payer :</strong>
                                                    <span
                                                        class="{{ $transaction->reste_a_payer ? 'text-orange-600 dark:text-orange-400 font-medium' : '' }}">
                                                        {{ $transaction->reste_a_payer ? number_format($transaction->reste_a_payer, 0, ',', ' ') . ' MGA' : '-' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- 6. MODALITÉS DE PAIEMENT -->
                                        <div class="space-y-3">
                                            <h4
                                                class="font-bold text-gray-900 dark:text-gray-100 text-base border-b border-gray-300 dark:border-gray-600 pb-2 flex items-center">
                                                <span
                                                    class="w-6 h-6 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center mr-2 text-xs">💳</span>
                                                Paiement
                                            </h4>
                                            <div class="space-y-2 text-gray-600 dark:text-gray-300">
                                                <div class="flex justify-between">
                                                    <strong class="text-gray-700 dark:text-gray-300">Mode :</strong>
                                                    <span>
                                                        @if ($transaction->mode_paiement)
                                                            @switch($transaction->mode_paiement)
                                                                @case('especes')
                                                                    💵 Espèces
                                                                @break

                                                                @case('AirtelMoney')
                                                                    📱 AirtelMoney
                                                                @break

                                                                @case('MVola')
                                                                    📱 MVola
                                                                @break

                                                                @case('OrangeMoney')
                                                                    📱 OrangeMoney
                                                                @break

                                                                @case('banque')
                                                                    � Banque
                                                                @break

                                                                @default
                                                                    {{ $transaction->mode_paiement }}
                                                                @break
                                                            @endswitch
                                                        @else
                                                            -
                                                        @endif
                                                    </span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <strong class="text-gray-700 dark:text-gray-300">Type paiement :</strong>
                                                    <span>{{ $transaction->type_paiement ?? '-' }}</span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <strong class="text-gray-700 dark:text-gray-300">Statut :</strong>
                                                    <span
                                                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                                        {{ $transaction->statut === 'confirme'
                                                            ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'
                                                            : ($transaction->statut === 'annule'
                                                                ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
                                                                : ($transaction->statut === 'partiellement_payee'
                                                                    ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200'
                                                                    : 'bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-300')) }}">
                                                        @switch($transaction->statut)
                                                            @case('confirme')
                                                                ✅ Confirmé
                                                            @break

                                                            @case('attente')
                                                                ⏳ En attente
                                                            @break

                                                            @case('partiellement_payee')
                                                                ⚠️ Paiement partiel
                                                            @break

                                                            @case('annule')
                                                                ❌ Annulé
                                                            @break

                                                            @default
                                                                {{ ucfirst($transaction->statut ?? 'Inconnu') }}
                                                            @break
                                                        @endswitch
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- 8. OBSERVATIONS (COLONNE COMPLÈTE) -->
                                        @if ($transaction->observation)
                                            <div class="col-span-full space-y-3 mt-4 pt-4 border-t border-gray-300 dark:border-gray-600">
                                                <h4 class="font-bold text-gray-900 dark:text-gray-100 text-base flex items-center">
                                                    <span
                                                        class="w-6 h-6 bg-yellow-100 dark:bg-yellow-900 rounded-lg flex items-center justify-center mr-2 text-xs">💬</span>
                                                    Observations & Notes
                                                </h4>
                                                <div
                                                    class="bg-white dark:bg-gray-800 p-4 rounded-lg border-l-4 border-blue-500 shadow-sm">
                                                    <div class="text-gray-700 dark:text-gray-300 leading-relaxed">
                                                        {{ $transaction->observation }}
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $transactions->links() }}
        </div>
    @else
        <div class="text-center py-12 bg-white dark:bg-gray-800 rounded-lg shadow">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Aucune transaction</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Commencez par ajouter une nouvelle transaction.</p>
        </div>
    @endif
</div>