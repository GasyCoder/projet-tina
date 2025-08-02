{{-- resources/views/livewire/stocks/transfert.blade.php --}}
<div x-data="{ darkMode: $persist(false) }" :class="{ 'dark': darkMode }">
 <!-- Statistiques des transferts -->
<!-- Statistiques en temps réel -->
<div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-6">
    <!-- Transferts du mois -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg border-l-4 border-blue-500 dark:border-blue-600">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-blue-500 dark:bg-blue-600 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Transferts du mois</dt>
                        <dd class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $transfertsMois ?? '15' }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <!-- En préparation -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg border-l-4 border-yellow-500 dark:border-yellow-600">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-yellow-500 dark:bg-yellow-600 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">En préparation</dt>
                        <dd class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $transfertsPreparation ?? '4' }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <!-- En transit -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg border-l-4 border-orange-500 dark:border-orange-600">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-orange-500 dark:bg-orange-600 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">En transit</dt>
                        <dd class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $transfertsTransit ?? '6' }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <!-- Reçus -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg border-l-4 border-green-500 dark:border-green-600">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-green-500 dark:bg-green-600 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Reçus</dt>
                        <dd class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $transfertsRecus ?? '5' }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Alertes et notifications -->
@if(session()->has('success'))
    <div class="mb-6 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 rounded-md p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-green-800 dark:text-green-200">{{ session('success') }}</p>
            </div>
        </div>
    </div>
@endif

<!-- Barre d'outils -->
<div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg mb-6 border border-gray-200 dark:border-gray-700">
    <div class="px-4 py-5 sm:p-6">
        <div class="sm:flex sm:items-center sm:justify-between">
            <div class="flex-1 min-w-0">
                <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-4">
                    <!-- Recherche -->
                    <div class="flex-1">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input wire:model.live="search" type="text"
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md leading-5 bg-white dark:bg-gray-900 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:placeholder-gray-400 dark:focus:placeholder-gray-300 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:text-gray-200"
                                placeholder="Rechercher un transfert...">
                        </div>
                    </div>

                    <!-- Filtre par statut -->
                    <div class="mt-3 sm:mt-0">
                        <select wire:model.live="filterStatus"
                            class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 dark:border-gray-700 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200">
                            <option value="">Tous les statuts</option>
                            <option value="en_preparation">En préparation</option>
                            <option value="en_transit">En transit</option>
                            <option value="recu">Reçu</option>
                            <option value="annule">Annulé</option>
                        </select>
                    </div>

                </div>
            </div>

         
        </div>
    </div>
</div>

<div x-data="transfertDashboard()" class="space-y-6">
    <!-- Liste des transferts -->
    <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-md border border-gray-200 dark:border-gray-700">
        <div class="px-4 py-5 sm:px-6 flex justify-between items-center border-b border-gray-200 dark:border-gray-700">
            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Liste des transferts</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Tous les transferts entre dépôts</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th @click="sort('numero')"
                            class="cursor-pointer px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors duration-150">
                            N° Transfert <span
                                x-text="sortField === 'numero' ? (sortDirection === 'asc' ? '↑' : '↓') : ''"></span>
                        </th>
                        <th @click="sort('date')"
                            class="cursor-pointer px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors duration-150">
                            Date <span
                                x-text="sortField === 'date' ? (sortDirection === 'asc' ? '↑' : '↓') : ''"></span>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Origine</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Destination</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Produits</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Véhicule</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Statut</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    <template x-for="t in sortedTransferts" :key="t.id">
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-600 dark:text-blue-400"
                                x-text="'#' + t.numero"></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200"
                                x-text="formatDate(t.date)"></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200 flex items-center">
                                <div class="h-2 w-2 bg-blue-400 rounded-full mr-2"></div>
                                <span x-text="t.depot_origine"></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200 flex items-center">
                                <div class="h-2 w-2 bg-green-400 rounded-full mr-2"></div>
                                <span x-text="t.depot_destination"></span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-200">
                                <div class="max-w-xs">
                                    <template x-for="(produit, idx) in t.produits.slice(0,2)"
                                        :key="idx">
                                        <div class="text-xs text-gray-600 dark:text-gray-400" x-text="produit"></div>
                                    </template>
                                    <div x-show="t.produits.length > 2" class="text-xs text-blue-600 dark:text-blue-400">
                                        +<span x-text="t.produits.length - 2"></span> autres
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200" x-text="t.vehicule">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <template x-if="t.statut === 'en_preparation'">
                                    <span
                                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-200">En
                                        préparation</span>
                                </template>
                                <template x-if="t.statut === 'en_transit'">
                                    <span
                                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-orange-100 dark:bg-orange-900/30 text-orange-800 dark:text-orange-200">En
                                        transit</span>
                                </template>
                                <template x-if="t.statut === 'recu'">
                                    <span
                                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-200">Reçu</span>
                                </template>
                                <template x-if="t.statut === 'annule'">
                                    <span
                                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-200">Annulé</span>
                                </template>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <template x-if="t.statut === 'en_preparation'">
                                        <button @click.prevent="changerStatut(t, 'en_transit')"
                                            class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300">Démarrer</button>
                                    </template>
                                    <template x-if="t.statut === 'en_transit'">
                                        <button @click.prevent="changerStatut(t, 'recu')"
                                            class="text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-300">Confirmer</button>
                                    </template>
                                    <button @click.prevent="ouvrirDetails(t)"
                                        class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">Détails</button>
                                    <template x-if="t.statut === 'en_preparation'">
                                        <button @click.prevent="changerStatut(t, 'annule')"
                                            class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300">Annuler</button>
                                    </template>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <template x-if="transferts.length === 0">
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                Aucun transfert trouvé
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <!-- Pagination simplifiée (placeholder) -->
        <div class="bg-white dark:bg-gray-800 px-4 py-3 flex items-center justify-between border-t border-gray-200 dark:border-gray-700">
            <div>
                <p class="text-sm text-gray-700 dark:text-gray-300">
                    Affichage de <span class="font-medium">1</span> à <span class="font-medium">5</span> sur <span
                        class="font-medium" x-text="transferts.length"></span> résultats
                </p>
            </div>
        </div>
    </div>

    <!-- Modal détails du transfert -->
    <div x-show="showDetails" x-transition
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 px-4"
        style="display: none;">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl max-w-2xl w-full overflow-hidden">
            <div class="px-6 py-4 flex justify-between items-center border-b border-gray-200 dark:border-gray-700">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Détails du transfert <span
                            x-text="'#' + detail.numero"></span></h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Suivi complet</p>
                </div>
                <button @click="fermerDetails" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300"
                    aria-label="Fermer">✕</button>
            </div>
            <div class="px-6 py-4">
                <!-- Timeline simplifiée -->
                <div class="flow-root mb-6">
                    <ul class="-mb-8">
                        <template x-for="(etape, idx) in detail.timeline" :key="idx">
                            <li>
                                <div class="relative pb-8">
                                    <div class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200 dark:bg-gray-700"></div>
                                    <div class="relative flex space-x-3">
                                        <div>
                                            <span
                                                :class="etape.iconBg +
                                                    ' h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white dark:ring-gray-800'">
                                                <svg class="w-4 h-4 text-white" x-html="etape.icon"></svg>
                                            </span>
                                        </div>
                                        <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                            <div>
                                                <p class="text-sm text-gray-500 dark:text-gray-400" x-text="etape.text"></p>
                                            </div>
                                            <div class="text-right text-sm whitespace-nowrap text-gray-500 dark:text-gray-400">
                                                <time x-text="etape.time"></time>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </template>
                    </ul>
                </div>

                <!-- Infos générales / produits -->
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Informations générales</h4>
                        <dl class="space-y-1 text-sm">
                            <div class="flex justify-between">
                                <dt class="text-gray-500 dark:text-gray-400">Origine:</dt>
                                <dd class="text-gray-900 dark:text-white" x-text="detail.depot_origine"></dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-gray-500 dark:text-gray-400">Destination:</dt>
                                <dd class="text-gray-900 dark:text-white" x-text="detail.depot_destination"></dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-gray-500 dark:text-gray-400">Véhicule:</dt>
                                <dd class="text-gray-900 dark:text-white" x-text="detail.vehicule"></dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-gray-500 dark:text-gray-400">Chauffeur:</dt>
                                <dd class="text-gray-900 dark:text-white" x-text="detail.chauffeur"></dd>
                            </div>
                        </dl>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Produits transférés</h4>
                        <ul class="space-y-1 text-sm">
                            <template x-for="(prod, i) in detail.produits" :key="i">
                                <li class="flex justify-between">
                                    <span class="text-gray-500 dark:text-gray-400" x-text="prod.nom + ':'"></span>
                                    <span class="text-gray-900 dark:text-white" x-text="prod.quantite + ' kg'"></span>
                                </li>
                            </template>
                            <li class="flex justify-between font-medium border-t border-gray-200 dark:border-gray-700 pt-1">
                                <span class="text-gray-900 dark:text-white">Total:</span>
                                <span class="text-gray-900 dark:text-white"
                                    x-text="totalQuantite(detail.produits) + ' kg'"></span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 dark:bg-gray-900/30 px-6 py-3 flex justify-end border-t border-gray-200 dark:border-gray-700">
                <button @click="fermerDetails"
                    class="px-4 py-2 rounded-md bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-sm text-gray-800 dark:text-gray-200">Fermer</button>
            </div>
        </div>
    </div>
</div>
    <script>
        function transfertDashboard() {
            return {
                transferts: [{
                        id: 1,
                        numero: 'T001',
                        date: '2025-07-30T08:30:00',
                        depot_origine: 'Dépôt Central',
                        depot_destination: 'Dépôt Nord',
                        produits: ['Riz: 500kg', 'Maïs: 200kg'],
                        vehicule: 'Camion ABC-123',
                        statut: 'en_transit',
                        chauffeur: 'Rakoto Jean',
                    },
                    {
                        id: 2,
                        numero: 'T002',
                        date: '2025-07-29T14:15:00',
                        depot_origine: 'Dépôt Nord',
                        depot_destination: 'Dépôt Sud',
                        produits: ['Haricot: 150kg', 'Patate: 300kg'],
                        vehicule: 'Fourgon DEF-456',
                        statut: 'recu',
                        chauffeur: 'Rabe Paul',
                    },
                    {
                        id: 3,
                        numero: 'T003',
                        date: '2025-07-28T09:00:00',
                        depot_origine: 'Dépôt Est',
                        depot_destination: 'Dépôt Central',
                        produits: ['Riz: 800kg'],
                        vehicule: 'Camion GHI-789',
                        statut: 'en_preparation',
                        chauffeur: 'Randria Pierre',
                    }
                ],
                sortField: 'date',
                sortDirection: 'desc',
                showDetails: false,
                detail: {},

                get sortedTransferts() {
                    return [...this.transferts].sort((a, b) => {
                        let aVal = a[this.sortField];
                        let bVal = b[this.sortField];
                        if (this.sortField === 'date') {
                            aVal = new Date(aVal);
                            bVal = new Date(bVal);
                        }
                        if (aVal < bVal) return this.sortDirection === 'asc' ? -1 : 1;
                        if (aVal > bVal) return this.sortDirection === 'asc' ? 1 : -1;
                        return 0;
                    });
                },

                sort(field) {
                    if (this.sortField === field) {
                        this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
                    } else {
                        this.sortField = field;
                        this.sortDirection = 'asc';
                    }
                },

                formatDate(iso) {
                    const d = new Date(iso);
                    const pad = (v) => String(v).padStart(2, '0');
                    return `${pad(d.getDate())}/${pad(d.getMonth()+1)}/${d.getFullYear()} ${pad(d.getHours())}:${pad(d.getMinutes())}`;
                },

                changerStatut(t, nouveau) {
                    t.statut = nouveau;
                },

                ouvrirDetails(t) {
                    // construire détail avec timeline simulée
                    this.detail = {
                        ...t,
                        produits: t.produits.map(p => {
                            const [nom, q] = p.split(':').map(s => s.trim());
                            return {
                                nom,
                                quantite: q.replace('kg', '')
                            };
                        }),
                        timeline: [{
                                text: 'Transfert créé',
                                time: '30/07/2025 08:30',
                                iconBg: 'bg-green-500',
                                icon: '<path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />'
                            },
                            {
                                text: 'Préparation terminée',
                                time: '30/07/2025 10:15',
                                iconBg: 'bg-blue-500',
                                icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />'
                            },
                            {
                                text: 'En transit',
                                time: '30/07/2025 11:00',
                                iconBg: 'bg-yellow-500',
                                icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />'
                            }
                        ]
                    };
                    this.showDetails = true;
                },

                fermerDetails() {
                    this.showDetails = false;
                    this.detail = {};
                },

                totalQuantite(prods) {
                    return prods.reduce((sum, p) => sum + Number(p.quantite), 0);
                }
            }
        }
    </script>
