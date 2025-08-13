

{{-- Vue 4: Transfert Enhanced --}}
{{-- resources/views/livewire/stocks/transfert-enhanced.blade.php --}}
<div x-data="{ darkMode: $persist(false) }" :class="{ 'dark': darkMode }">
    <!-- Statistiques des transferts -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-6">
        <!-- Transferts planifiés -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg border-l-4 border-blue-500">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v6a2 2 0 002 2h6a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Planifiés</dt>
                            <dd class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['transferts_planifies'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- En cours -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg border-l-4 border-yellow-500">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-yellow-500 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">En cours</dt>
                            <dd class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['transferts_en_cours'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Taux de réussite -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg border-l-4 border-green-500">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Taux réussite</dt>
                            <dd class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['taux_reussite'] }}%</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Coût transport -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg border-l-4 border-purple-500">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Coût transport</dt>
                            <dd class="text-2xl font-semibold text-gray-900 dark:text-white">
                                {{ number_format($stats['cout_transport_mois'], 0, ',', ' ') }} Ar
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alertes urgentes -->
    @if($alertesUrgentes->count() > 0)
        <div class="mb-6 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-lg p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-800 dark:text-red-200">
                        <strong>{{ $alertesUrgentes->count() }} transfert(s) urgent(s) en retard :</strong>
                        @foreach($alertesUrgentes as $alerte)
                            {{ $alerte->numero_transfert }}{{ !$loop->last ? ', ' : '' }}
                        @endforeach
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- Transferts en cours -->
    @if($transfertsEnCours->count() > 0)
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg mb-6">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">🚛 Transferts en cours</h3>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    @foreach($transfertsEnCours as $transfert)
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $transfert->statut_badge['class'] }}">
                                    {{ $transfert->statut_badge['text'] }}
                                </span>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $transfert->priorite_badge['class'] }}">
                                    {{ $transfert->priorite_badge['text'] }}
                                </span>
                                <div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $transfert->numero_transfert }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $transfert->depotOrigine->nom }} → {{ $transfert->depotDestination->nom }}
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $transfert->progression_pourcent }}%</div>
                                <div class="w-24 bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $transfert->progression_pourcent }}%"></div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Barre d'outils -->
    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg mb-6">
        <div class="px-6 py-5">
            <div class="sm:flex sm:items-center sm:justify-between">
                <div class="flex-1 min-w-0">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-4">
                        <!-- Recherche -->
                        <div class="flex-1">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                                <input wire:model.live.debounce.300ms="search" type="text"
                                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-200"
                                    placeholder="Rechercher par numéro, dépôt, chauffeur...">
                            </div>
                        </div>

                        <!-- Filtres -->
                        <div class="mt-3 sm:mt-0 flex space-x-3">
                            <select wire:model.live="filterStatut" class="rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200">
                                <option value="">Tous les statuts</option>
                                <option value="planifie">Planifié</option>
                                <option value="en_preparation">En préparation</option>
                                <option value="en_transit">En transit</option>
                                <option value="livre">Livré</option>
                                <option value="receptionne">Réceptionné</option>
                                <option value="annule">Annulé</option>
                            </select>

                            <select wire:model.live="filterPriorite" class="rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200">
                                <option value="">Toutes priorités</option>
                                <option value="urgente">Urgente</option>
                                <option value="haute">Haute</option>
                                <option value="normale">Normale</option>
                                <option value="basse">Basse</option>
                            </select>

                            <select wire:model.live="filterDepotOrigine" class="rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200">
                                <option value="">Tous les dépôts origine</option>
                                @foreach($depots as $depot)
                                    <option value="{{ $depot->id }}">{{ $depot->nom }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="mt-3 sm:mt-0 sm:ml-4 flex space-x-3">
                    <button wire:click="openPlanificationModal()" 
                            class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                        </svg>
                        Planification Auto
                    </button>

                    <button wire:click="openTransfertModal()" 
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Nouveau Transfert
                    </button>
                </div>
            </div>
        </div>
    </div>