{{-- resources/views/livewire/finance/tabs/revenus.blade.php --}}
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-xl font-semibold text-gray-900">💰 Liste des Revenus</h2>
        <div class="flex items-center space-x-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Période</label>
                <select wire:model.live="periodeRevenus" class="border border-gray-300 rounded-md px-3 py-2 text-sm">
                    <option value="semaine">Cette semaine</option>
                    <option value="mois">Ce mois</option>
                    <option value="trimestre">Ce trimestre</option>
                    <option value="annee">Cette année</option>
                    <option value="personnalise">Personnalisé</option>
                </select>
            </div>
            @if($periodeRevenus === 'personnalise')
                <div class="flex space-x-2">
                    <input wire:model.live="dateDebutRevenus" type="date" class="border border-gray-300 rounded-md px-3 py-2 text-sm">
                    <input wire:model.live="dateFinRevenus" type="date" class="border border-gray-300 rounded-md px-3 py-2 text-sm">
                </div>
            @endif
        </div>
    </div>

    <!-- Statistiques des revenus -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex items-center">
                <span class="text-2xl mr-3">💰</span>
                <div>
                    <p class="text-sm font-medium text-green-800">Total Revenus</p>
                    <p class="text-lg font-semibold text-green-900">{{ number_format($totalRevenus, 0, ',', ' ') }} MGA</p>
                </div>
            </div>
        </div>

        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-center">
                <span class="text-2xl mr-3">📈</span>
                <div>
                    <p class="text-sm font-medium text-blue-800">Revenus Moyens</p>
                    <p class="text-lg font-semibold text-blue-900">{{ number_format($revenuMoyen, 0, ',', ' ') }} MGA</p>
                </div>
            </div>
        </div>

        <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
            <div class="flex items-center">
                <span class="text-2xl mr-3">🔢</span>
                <div>
                    <p class="text-sm font-medium text-purple-800">Nombre d'Opérations</p>
                    <p class="text-lg font-semibold text-purple-900">{{ $nombreRevenus }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau des revenus -->
    <div class="bg-white rounded-lg border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-green-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-green-800 uppercase tracking-wider">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-green-800 uppercase tracking-wider">Référence</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-green-800 uppercase tracking-wider">Type</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-green-800 uppercase tracking-wider">Description</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-green-800 uppercase tracking-wider">De</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-green-800 uppercase tracking-wider">Montant</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-green-800 uppercase tracking-wider">Voyage</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-green-800 uppercase tracking-wider">Statut</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($revenus as $revenu)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm text-gray-900">
                                {{ \Carbon\Carbon::parse($revenu->date)->format('d/m/Y') }}
                            </td>
                            <td class="px-4 py-3 text-sm font-medium text-gray-900">
                                {{ $revenu->reference }}
                            </td>
                            <td class="px-4 py-3 text-sm">
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
                                    @default
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">{{ ucfirst($revenu->type) }}</span>
                                @endswitch
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600 max-w-xs">
                                <div class="truncate" title="{{ $revenu->objet }}">
                                    {{ $revenu->objet }}
                                </div>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">
                                {{ $revenu->from_nom ?? 'N/A' }}
                            </td>
                            <td class="px-4 py-3 text-sm font-semibold text-green-600">
                                +{{ number_format($revenu->montant_mga, 0, ',', ' ') }} MGA
                            </td>
                            <td class="px-4 py-3 text-sm">
                                @if($revenu->voyage_id)
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                        🚗 {{ $revenu->voyage->reference ?? 'N/A' }}
                                    </span>
                                @else
                                    <span class="text-gray-400 text-xs">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm">
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
                                @endswitch
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-8 text-center text-gray-500">
                                💰 Aucun revenu trouvé pour cette période
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($revenus->hasPages())
        <div class="mt-4">
            {{ $revenus->links() }}
        </div>
    @endif
</div>