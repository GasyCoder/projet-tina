<div class="min-h-screen bg-gray-50 pt-20 pb-6 px-4 md:px-6">
    <div class="space-y-6 max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h1 class="text-2xl font-bold text-gray-900">Situation financière</h1>
            <button wire:click="openSituationModal" 
                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                ➕ Ajouter Situation
            </button>
        </div>

        <!-- Filtres -->
        <div class="bg-white shadow rounded-lg p-4 md:p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">📍 Lieu</label>
                    <select wire:model.live="lieuSelectionne" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">Tous les lieux</option>
                        <option value="mahajanga">🏢 Mahajanga</option>
                        <option value="antananarivo">🏢 Antananarivo</option>
                        <option value="autre">🏢 Autres lieux</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">📅 Date début</label>
                    <input wire:model.live="dateDebut" type="date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">📅 Date fin</label>
                    <input wire:model.live="dateFin" type="date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div class="flex items-end">
                    <button wire:click="resetFiltres" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm text-gray-700 bg-white hover:bg-gray-50">
                        🔄 Reset
                    </button>
                </div>
            </div>
        </div>

        <!-- Résultats -->
        @if($situations->count() > 0)
            @foreach($situationsGroupees as $date => $situationsJour)
                <div class="bg-white shadow rounded-lg overflow-hidden">
                    <!-- En-tête de la date -->
                    <div class="bg-gray-50 px-4 py-3 md:px-6 md:py-4 border-b">
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center">
                            <h3 class="text-lg font-semibold text-gray-900">
                                📅 {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}
                            </h3>
                            <div class="text-sm text-gray-600 mt-1 sm:mt-0">
                                {{ count($situationsJour) }} entrée(s)
                            </div>
                        </div>
                    </div>

                    <!-- Tableau responsive -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Description
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">
                                        Lieu
                                    </th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Montant
                                    </th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($situationsJour as $situation)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-4">
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $situation->description }}
                                                </div>
                                                <div class="text-xs text-gray-500 sm:hidden mt-1">
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                                        {{ $situation->lieu === 'mahajanga' ? 'bg-blue-100 text-blue-800' : 
                                                           ($situation->lieu === 'antananarivo' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800') }}">
                                                        @switch($situation->lieu)
                                                            @case('mahajanga') 🏢 Mahajanga @break
                                                            @case('antananarivo') 🏢 Antananarivo @break
                                                            @default 🏢 {{ ucfirst($situation->lieu) }}
                                                        @endswitch
                                                    </span>
                                                </div>
                                                @if($situation->commentaire)
                                                    <div class="text-xs text-gray-500 mt-1">
                                                        💬 {{ $situation->commentaire }}
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap hidden sm:table-cell">
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                                {{ $situation->lieu === 'mahajanga' ? 'bg-blue-100 text-blue-800' : 
                                                   ($situation->lieu === 'antananarivo' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800') }}">
                                                @switch($situation->lieu)
                                                    @case('mahajanga') 🏢 Mahajanga @break
                                                    @case('antananarivo') 🏢 Antananarivo @break
                                                    @default 🏢 {{ ucfirst($situation->lieu) }}
                                                @endswitch
                                            </span>
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-right">
                                            <div class="text-sm text-gray-900">
                                                <div class="font-medium">Initial: {{ number_format($situation->montant_initial, 0, ',', ' ') }} MGA</div>
                                                <div class="font-medium {{ $situation->montant_final >= $situation->montant_initial ? 'text-green-600' : 'text-red-600' }}">
                                                    Final: {{ number_format($situation->montant_final, 0, ',', ' ') }} MGA
                                                </div>
                                                @php $ecart = $situation->montant_final - $situation->montant_initial @endphp
                                                <div class="text-xs {{ $ecart >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                                    Écart: {{ $ecart >= 0 ? '+' : '' }}{{ number_format($ecart, 0, ',', ' ') }} MGA
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-center hidden sm:table-cell">
                                            <div class="flex justify-center space-x-2">
                                                <button wire:click="editSituation({{ $situation->id }})" 
                                                        class="text-blue-600 hover:text-blue-900">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                </button>
                                                <button wire:click="deleteSituation({{ $situation->id }})" 
                                                        wire:confirm="Supprimer cette situation ?"
                                                        class="text-red-600 hover:text-red-900">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Résumé du jour -->
                    @php
                        $totalInitial = collect($situationsJour)->sum('montant_initial');
                        $totalFinal = collect($situationsJour)->sum('montant_final');
                        $ecartTotal = $totalFinal - $totalInitial;
                    @endphp
                    <div class="bg-gray-50 px-4 py-3 border-t">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
                            <div class="text-center">
                                <div class="text-gray-600">Total Initial</div>
                                <div class="font-semibold text-gray-900">{{ number_format($totalInitial, 0, ',', ' ') }} MGA</div>
                            </div>
                            <div class="text-center">
                                <div class="text-gray-600">Total Final</div>
                                <div class="font-semibold text-gray-900">{{ number_format($totalFinal, 0, ',', ' ') }} MGA</div>
                            </div>
                            <div class="text-center">
                                <div class="text-gray-600">Écart Total</div>
                                <div class="font-bold {{ $ecartTotal >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $ecartTotal >= 0 ? '+' : '' }}{{ number_format($ecartTotal, 0, ',', ' ') }} MGA
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            <!-- Pagination -->
            <div class="bg-white px-4 py-4 rounded-lg shadow">
                {{ $situations->links() }}
            </div>
        @else
            <div class="bg-white shadow rounded-lg p-8 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune situation trouvée</h3>
                <p class="mt-1 text-sm text-gray-500">Commencez par ajouter une nouvelle situation financière.</p>
            </div>
        @endif

        <!-- Modal Situation -->
        @if($showSituationModal)
            <div class="fixed inset-0 z-50 overflow-y-auto">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeSituationModal"></div>
                    <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                        <form wire:submit="saveSituation">
                            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">
                                    {{ $editingSituation ? '✏️ Modifier' : '➕ Ajouter' }} une situation financière
                                </h3>

                                <div class="grid grid-cols-1 gap-4">
                                    <!-- Informations de base -->
                                    <div>
                                        <h4 class="text-md font-medium text-gray-900 mb-3 border-b pb-2">📋 Informations générales</h4>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">📅 Date *</label>
                                            <input wire:model="dateSituation" type="date" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500">
                                            @error('dateSituation') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">📍 Lieu *</label>
                                            <select wire:model="lieu" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500">
                                                <option value="">Sélectionner un lieu</option>
                                                <option value="mahajanga">🏢 Mahajanga</option>
                                                <option value="antananarivo">🏢 Antananarivo</option>
                                                <option value="autre">🏢 Autres lieux</option>
                                            </select>
                                            @error('lieu') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">📝 Description *</label>
                                        <input wire:model="description" type="text" placeholder="Ex: loyer, vadiny herve, caisse, mvola..." class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500">
                                        @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                    </div>

                                    <!-- Montants -->
                                    <div>
                                        <h4 class="text-md font-medium text-gray-900 mb-3 border-b pb-2 mt-4">💰 Montants</h4>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">💵 Montant Initial (MGA) *</label>
                                            <input wire:model.live="montantInitial" type="number" step="0.01" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500">
                                            @error('montantInitial') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">💵 Montant Final (MGA) *</label>
                                            <input wire:model.live="montantFinal" type="number" step="0.01" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500">
                                            @error('montantFinal') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                        </div>
                                    </div>

                                    <!-- Écart calculé automatiquement -->
                                    @if($montantInitial && $montantFinal)
                                        <div>
                                            <div class="bg-gray-50 p-3 rounded-md">
                                                <label class="block text-sm font-medium text-gray-700">📊 Écart calculé</label>
                                                @php $ecart = $montantFinal - $montantInitial @endphp
                                                <div class="text-lg font-semibold {{ $ecart >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                                    {{ $ecart >= 0 ? '+' : '' }}{{ number_format($ecart, 0, ',', ' ') }} MGA
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">💬 Commentaire</label>
                                        <textarea wire:model="commentaire" rows="3" placeholder="Commentaires ou observations..." class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 sm:ml-3 sm:w-auto sm:text-sm">
                                    {{ $editingSituation ? '✏️ Modifier' : '➕ Ajouter' }}
                                </button>
                                <button type="button" wire:click="closeSituationModal" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                    Annuler
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>