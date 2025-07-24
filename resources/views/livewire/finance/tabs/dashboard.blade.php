{{-- resources/views/livewire/finance/tabs/dashboard.blade.php - CORRIGÉ --}}
<div class="space-y-6">
    <!-- Période sélectionnée -->
    <div class="flex items-center justify-between">
        <h3 class="text-lg font-medium text-gray-900">Vue d'ensemble financière</h3>
        <div class="flex items-center space-x-4">
            <div class="flex items-center space-x-2">
                <label class="text-sm text-gray-700">Du:</label>
                <input wire:model.live="dateDebut" type="date" class="rounded-md border-gray-300 shadow-sm text-sm">
            </div>
            <div class="flex items-center space-x-2">
                <label class="text-sm text-gray-700">Au:</label>
                <input wire:model.live="dateFin" type="date" class="rounded-md border-gray-300 shadow-sm text-sm">
            </div>
        </div>
    </div>

    <!-- Statistiques principales avec VOS vrais types -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Total Entrées (vente, depot, transfert reçu) -->
        <div class="bg-green-50 overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                            <span class="text-white text-sm">💰</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Entrées</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ number_format($totalEntrees, 0) }} MGA</dd>
                            <dd class="text-xs text-gray-500">Ventes • Dépôts • Transferts reçus</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Sorties (achat, frais, commission, etc.) -->
        <div class="bg-red-50 overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-red-500 rounded-md flex items-center justify-center">
                            <span class="text-white text-sm">💸</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Sorties</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ number_format($totalSorties, 0) }} MGA</dd>
                            <dd class="text-xs text-gray-500">Achats • Frais • Commissions • Avances</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bénéfice Net -->
        <div class="bg-{{ $beneficeNet >= 0 ? 'blue' : 'yellow' }}-50 overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-{{ $beneficeNet >= 0 ? 'blue' : 'yellow' }}-500 rounded-md flex items-center justify-center">
                            <span class="text-white text-sm">{{ $beneficeNet >= 0 ? '📈' : '📉' }}</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Résultat Net</dt>
                            <dd class="text-lg font-medium text-{{ $beneficeNet >= 0 ? 'green' : 'red' }}-600">
                                {{ number_format($beneficeNet, 0) }} MGA
                            </dd>
                            <dd class="text-xs text-gray-500">
                                {{ $beneficeNet >= 0 ? 'Bénéfice' : 'Perte' }} sur la période
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transactions en attente avec VOS vrais statuts -->
        <div class="bg-yellow-50 overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                            <span class="text-white text-sm">⏳</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">En Attente</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $transactionsEnAttente }}</dd>
                            <dd class="text-xs text-gray-500">transaction(s) à confirmer</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Détail par type de transaction -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Détail des entrées -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-green-600 mb-4">💰 Détail des Entrées</h3>
                @php
                    $ventes = \App\Models\Transaction::confirme()->where('type', 'vente')->periode($dateDebut, $dateFin)->sum('montant_mga');
                    $depots = \App\Models\Transaction::confirme()->where('type', 'depot')->periode($dateDebut, $dateFin)->sum('montant_mga');
                    $transfertsRecus = \App\Models\Transaction::confirme()->where('type', 'transfert')->periode($dateDebut, $dateFin)->sum('montant_mga');
                @endphp
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">💰 Ventes de produits</span>
                        <span class="font-medium text-green-600">{{ number_format($ventes, 0) }} MGA</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">📥 Dépôts d'argent</span>
                        <span class="font-medium text-green-600">{{ number_format($depots, 0) }} MGA</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">🔄 Transferts reçus</span>
                        <span class="font-medium text-green-600">{{ number_format($transfertsRecus, 0) }} MGA</span>
                    </div>
                    <div class="border-t pt-2 flex justify-between items-center font-semibold">
                        <span>Total Entrées</span>
                        <span class="text-green-600">{{ number_format($totalEntrees, 0) }} MGA</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Détail des sorties -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-red-600 mb-4">💸 Détail des Sorties</h3>
                @php
                    $achats = \App\Models\Transaction::confirme()->where('type', 'achat')->periode($dateDebut, $dateFin)->sum('montant_mga');
                    $frais = \App\Models\Transaction::confirme()->where('type', 'frais')->periode($dateDebut, $dateFin)->sum('montant_mga');
                    $commissions = \App\Models\Transaction::confirme()->where('type', 'commission')->periode($dateDebut, $dateFin)->sum('montant_mga');
                    $paiements = \App\Models\Transaction::confirme()->where('type', 'paiement')->periode($dateDebut, $dateFin)->sum('montant_mga');
                    $avances = \App\Models\Transaction::confirme()->where('type', 'avance')->periode($dateDebut, $dateFin)->sum('montant_mga');
                    $retraits = \App\Models\Transaction::confirme()->where('type', 'retrait')->periode($dateDebut, $dateFin)->sum('montant_mga');
                @endphp
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">🛒 Achats de produits</span>
                        <span class="font-medium text-red-600">{{ number_format($achats, 0) }} MGA</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">🧾 Frais (transport, péage...)</span>
                        <span class="font-medium text-red-600">{{ number_format($frais, 0) }} MGA</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">💼 Commissions</span>
                        <span class="font-medium text-red-600">{{ number_format($commissions, 0) }} MGA</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">💳 Paiements</span>
                        <span class="font-medium text-red-600">{{ number_format($paiements, 0) }} MGA</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">💸 Avances</span>
                        <span class="font-medium text-red-600">{{ number_format($avances, 0) }} MGA</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">📤 Retraits</span>
                        <span class="font-medium text-red-600">{{ number_format($retraits, 0) }} MGA</span>
                    </div>
                    <div class="border-t pt-2 flex justify-between items-center font-semibold">
                        <span>Total Sorties</span>
                        <span class="text-red-600">{{ number_format($totalSorties, 0) }} MGA</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Soldes des comptes par type -->
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">💳 État des Comptes</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach($comptes->groupBy('type_compte') as $type => $comptesType)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="text-center">
                            <h4 class="font-medium text-gray-900">
                                @switch($type)
                                    @case('principal') 💰 Principal @break
                                    @case('banque') 🏦 Banque @break
                                    @case('mobile_money') 📱 Mobile Money @break
                                    @case('credit') 💳 Crédit @break
                                @endswitch
                            </h4>
                            <p class="text-2xl font-bold text-{{ $comptesType->sum('solde_actuel_mga') >= 0 ? 'green' : 'red' }}-600 mt-2">
                                {{ number_format($comptesType->sum('solde_actuel_mga'), 0) }} MGA
                            </p>
                            <p class="text-xs text-gray-500 mt-1">{{ $comptesType->count() }} compte(s)</p>
                        </div>
                        
                        <!-- Liste des comptes de ce type -->
                        <div class="mt-3 space-y-1">
                            @foreach($comptesType->take(3) as $compte)
                                <div class="flex justify-between text-xs">
                                    <span class="text-gray-600 truncate">{{ $compte->nom_compte }}</span>
                                    <span class="text-{{ $compte->solde_actuel_mga >= 0 ? 'green' : 'red' }}-600 font-medium">
                                        {{ number_format($compte->solde_actuel_mga, 0) }}
                                    </span>
                                </div>
                            @endforeach
                            @if($comptesType->count() > 3)
                                <div class="text-xs text-gray-500 text-center">
                                    +{{ $comptesType->count() - 3 }} autre(s)
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Transactions récentes importantes -->
    @php
        $transactionsRecentes = \App\Models\Transaction::with(['voyage'])
            ->where('montant_mga', '>', 100000) // Plus de 100k MGA
            ->orderBy('date', 'desc')
            ->limit(5)
            ->get();
    @endphp
    
    @if($transactionsRecentes->count() > 0)
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">🔥 Transactions Importantes Récentes</h3>
                <div class="space-y-3">
                    @foreach($transactionsRecentes as $transaction)
                        <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-b-0">
                            <div class="flex items-center space-x-3">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                    {{ in_array($transaction->type, ['vente', 'depot', 'transfert']) ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    @switch($transaction->type)
                                        @case('achat') 🛒 @break
                                        @case('vente') 💰 @break
                                        @case('transfert') 🔄 @break
                                        @case('frais') 🧾 @break
                                        @case('commission') 💼 @break
                                        @case('paiement') 💳 @break
                                        @case('avance') 💸 @break
                                        @case('depot') 📥 @break
                                        @case('retrait') 📤 @break
                                    @endswitch
                                    {{ ucfirst($transaction->type) }}
                                </span>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $transaction->reference }}</p>
                                    <p class="text-xs text-gray-500">
                                        {{ $transaction->date->format('d/m/Y') }} • {{ Str::limit($transaction->objet, 30) }}
                                        @if($transaction->voyage)
                                            • 🚛 {{ $transaction->voyage->reference }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-{{ in_array($transaction->type, ['vente', 'depot', 'transfert']) ? 'green' : 'red' }}-600">
                                    {{ in_array($transaction->type, ['vente', 'depot', 'transfert']) ? '+' : '-' }}{{ number_format($transaction->montant_mga, 0) }} MGA
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>