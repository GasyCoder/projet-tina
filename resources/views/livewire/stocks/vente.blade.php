{{-- /home/kananavy/Documents/DevGGasy/projet-tina/resources/views/livewire/stocks/vente.blade.php - VERSION AMÉLIORÉE
--}}
<div>
    <!-- Statistiques en temps réel avec alertes -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-6">
        <!-- Ventes du jour -->
        <div class="bg-white overflow-hidden shadow-lg rounded-lg border-l-4 border-blue-500">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Ventes du jour</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900">{{ $ventesJour ?? '12' }}</div>
                                <div class="ml-2 flex items-baseline text-sm font-semibold text-green-600">
                                    <svg class="self-center flex-shrink-0 h-5 w-5 text-green-500" fill="currentColor"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span class="sr-only">Augmenté de</span>
                                    +8%
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chiffre d'affaires journalier -->
        <div class="bg-white overflow-hidden shadow-lg rounded-lg border-l-4 border-green-500">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">CA Journalier</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900">
                                    {{ number_format($caJournalier ?? 2453280, 0, ',', ' ') }}</div>
                                <div class="ml-1 text-sm font-medium text-gray-500">Ar</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

      

        <!-- CA Mensuel -->
        <div class="bg-white overflow-hidden shadow-lg rounded-lg border-l-4 border-purple-500">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">CA Mensuel</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900">
                                    {{ number_format($caMensuel ?? 56245680, 0, ',', ' ') }}</div>
                                <div class="ml-1 text-sm font-medium text-gray-500">Ar</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alertes et notifications -->
    @if(session()->has('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-md p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Barre d'outils améliorée -->
    <div class="bg-white shadow-lg rounded-lg mb-6">
        <div class="px-6 py-5">
            <div class="sm:flex sm:items-center sm:justify-between">
                <div class="flex-1 min-w-0">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-4">
                        <!-- Recherche avancée -->
                        <div class="flex-1">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <input wire:model.live.debounce.300ms="search" type="text"
                                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Rechercher par client, produit, référence...">
                            </div>
                        </div>

                        <!-- Filtres multiples -->
                        <div class="mt-3 sm:mt-0 flex space-x-3">
                            <select wire:model.live="filterPeriod"
                                class="block w-full pl-3 pr-10 py-3 text-base border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-lg">
                                <option value="">Toutes les périodes</option>
                                <option value="today">Aujourd'hui</option>
                                <option value="week">Cette semaine</option>
                                <option value="month">Ce mois</option>
                                <option value="custom">Période personnalisée</option>
                            </select>
                        </div>
                    </div>
                </div>

            
            </div>
        </div>
    </div>

<!-- Tableau des ventes amélioré -->
<div class="bg-white shadow-lg overflow-hidden rounded-lg">
    <div class="px-6 py-5 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900">Liste des ventes</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    {{ $totalVentes ?? 25 }} ventes trouvées
                    @if($search)
                        pour "{{ $search }}"
                    @endif
                </p>
            </div>
            <div class="flex items-center space-x-2">
                <span class="text-sm text-gray-500">Afficher:</span>
                <select wire:model.live="perPage" class="border border-gray-300 rounded-md text-sm py-1 px-2">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th wire:click="sortBy('reference')"
                        class="cursor-pointer px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hover:bg-gray-100 transition-colors duration-150">
                        <div class="flex items-center space-x-1">
                            <span>Référence</span>
                            @if(($sortField ?? '') == 'reference')
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="{{ ($sortDirection ?? 'asc') == 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}" />
                                </svg>
                            @endif
                        </div>
                    </th>
                    <th wire:click="sortBy('date')"
                        class="cursor-pointer px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hover:bg-gray-100 transition-colors duration-150">
                        <div class="flex items-center space-x-1">
                            <span>Date</span>
                            @if(($sortField ?? '') == 'date')
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="{{ ($sortDirection ?? 'asc') == 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}" />
                                </svg>
                            @endif
                        </div>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Produit
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Lieu de livraison
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Client
                    </th>
                    <th wire:click="sortBy('poids')"
                        class="cursor-pointer px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hover:bg-gray-100 transition-colors duration-150">
                        <div class="flex items-center space-x-1">
                            <span>Poids (kg)</span>
                            @if(($sortField ?? '') == 'poids')
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="{{ ($sortDirection ?? 'asc') == 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}" />
                                </svg>
                            @endif
                        </div>
                    </th>
                    <th wire:click="sortBy('montant')"
                        class="cursor-pointer px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hover:bg-gray-100 transition-colors duration-150">
                        <div class="flex items-center space-x-1">
                            <span>Montant (Ar)</span>
                            @if(($sortField ?? '') == 'montant')
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="{{ ($sortDirection ?? 'asc') == 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}" />
                                </svg>
                            @endif
                        </div>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Paiement
                    </th>
            
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($ventes ?? App\Models\Dechargement::with(['lieuLivraison', 'produit'])->ventes()->get() as $vente)
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-600">
                            {{ $vente['reference'] ?? $vente->reference }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ \Carbon\Carbon::parse($vente['date'] ?? $vente->date)->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $vente->produit['nom'] ?? $vente->produit->nom ?? 'N/A' }}
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ number_format($vente['prix_unitaire'] ?? $vente->prix_unitaire_mga ?? 0, 0) }} Ar/kg
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $vente->lieuLivraison->nom ?? ($vente['lieu_livraison'] ?? 'Non spécifié') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $vente['client'] ?? $vente->interlocuteur_nom ?? 'N/A' }}
                            </div>
                            @if(isset($vente['client_contact']) || isset($vente->interlocuteur_contact))
                                <div class="text-sm text-gray-500">
                                    {{ $vente['client_contact'] ?? $vente->interlocuteur_contact }}
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                            {{ number_format($vente['poids'] ?? $vente->poids_arrivee_kg ?? 0, 0, ',', ' ') }} kg
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                {{ number_format($vente['montant'] ?? $vente->montant_total_mga ?? 0, 0, ',', ' ') }} Ar
                            </div>
                            @if(($vente['reste'] ?? $vente->reste_mga ?? 0) > 0)
                                <div class="text-sm text-red-600">Reste:
                                    {{ number_format($vente['reste'] ?? $vente->reste_mga, 0, ',', ' ') }} Ar
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $paiement = $vente['paiement'] ?? $vente->paiement_mga ?? 0;
                                $montant = $vente['montant'] ?? $vente->montant_total_mga ?? 0;
                                $pourcentage = $montant > 0 ? ($paiement / $montant) * 100 : 0;
                            @endphp
                            <div class="flex flex-col">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ number_format($paiement, 0, ',', ' ') }} Ar
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                    <div class="h-2 rounded-full {{ $pourcentage >= 100 ? 'bg-green-600' : ($pourcentage >= 50 ? 'bg-yellow-500' : 'bg-red-500') }}"
                                        style="width: {{ min($pourcentage, 100) }}%"></div>
                                </div>
                                <div class="text-xs text-gray-500 mt-1">{{ number_format($pourcentage, 0) }}%</div>
                            </div>
                        </td>
                    
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                </svg>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune vente trouvée</h3>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination améliorée -->
    <div class="bg-white px-6 py-4 flex items-center justify-between border-t border-gray-200">
        <div class="flex-1 flex justify-between sm:hidden">
            <button wire:click="previousPage" {{ ($ventes->currentPage() ?? 1) <= 1 ? 'disabled' : '' }}
                class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:bg-gray-100 disabled:text-gray-400">
                Précédent
            </button>
            <button wire:click="nextPage" {{ ($ventes->currentPage() ?? 1) >= ($ventes->lastPage() ?? 1) ? 'disabled' : '' }}
                class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:bg-gray-100 disabled:text-gray-400">
                Suivant
            </button>
        </div>

        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-700">
                    Affichage de <span class="font-medium">{{ $from ?? 1 }}</span> à
                    <span class="font-medium">{{ $to ?? ($ventes->count() ?? 0) }}</span> sur
                    <span class="font-medium">{{ $totalVentes ?? ($ventes->total() ?? 0) }}</span> résultats
                </p>
            </div>
            <div>
                @if(isset($ventes) && method_exists($ventes, 'links'))
                    {{ $ventes->links() }}
                @endif
            </div>
        </div>
    </div>
</div>

    {{-- Modal dynamique pour les ventes (utilise le système de modals dynamiques) --}}
    @include('livewire.voyage.modals.dechargement-modal', ['type_dechargement' => 'vente'])
</div>