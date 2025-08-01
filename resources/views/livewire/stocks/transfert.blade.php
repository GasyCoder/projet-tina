{{-- resources/views/livewire/stocks/transfert.blade.php --}}
<div>
    <!-- Statistiques des transferts -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-6">
        <!-- Transferts du mois -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Transferts du mois</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $transfertsMois ?? '15' }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- En préparation -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">En préparation</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $transfertsPreparation ?? '4' }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- En transit -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-orange-500 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">En transit</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $transfertsTransit ?? '6' }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reçus -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Reçus</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $transfertsRecus ?? '5' }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Barre d'outils -->
    <div class="bg-white shadow rounded-lg mb-6">
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
                                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Rechercher un transfert...">
                            </div>
                        </div>

                        <!-- Filtre par statut -->
                        <div class="mt-3 sm:mt-0">
                            <select wire:model.live="filterStatus"
                                class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                <option value="">Tous les statuts</option>
                                <option value="en_preparation">En préparation</option>
                                <option value="en_transit">En transit</option>
                                <option value="recu">Reçu</option>
                                <option value="annule">Annulé</option>
                            </select>
                        </div>

                        <!-- Filtre par dépôt -->
                        <div class="mt-3 sm:mt-0">
                            <select wire:model.live="filterDepot"
                                class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                <option value="">Tous les dépôts</option>
                                <option value="depot_central">Dépôt Central</option>
                                <option value="depot_nord">Dépôt Nord</option>
                                <option value="depot_sud">Dépôt Sud</option>
                                <option value="depot_est">Dépôt Est</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="mt-5 sm:mt-0 sm:ml-6 sm:flex-shrink-0 sm:flex sm:items-center">
                    <button wire:click="$toggle('showModal')" type="button"
                        class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Nouveau Transfert
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Assure-toi d’avoir Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <div x-data="transfertDashboard()" class="space-y-6">
        <!-- Liste des transferts -->
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Liste des transferts</h3>
                    <p class="mt-1 text-sm text-gray-500">Tous les transferts entre dépôts</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th @click="sort('numero')"
                                class="cursor-pointer px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hover:bg-gray-100">
                                N° Transfert <span
                                    x-text="sortField === 'numero' ? (sortDirection === 'asc' ? '↑' : '↓') : ''"></span>
                            </th>
                            <th @click="sort('date')"
                                class="cursor-pointer px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hover:bg-gray-100">
                                Date <span
                                    x-text="sortField === 'date' ? (sortDirection === 'asc' ? '↑' : '↓') : ''"></span>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Origine</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Destination</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Produits</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Véhicule</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Statut</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <template x-for="t in sortedTransferts" :key="t.id">
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-600"
                                    x-text="'#' + t.numero"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                    x-text="formatDate(t.date)"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 flex items-center">
                                    <div class="h-2 w-2 bg-blue-400 rounded-full mr-2"></div>
                                    <span x-text="t.depot_origine"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 flex items-center">
                                    <div class="h-2 w-2 bg-green-400 rounded-full mr-2"></div>
                                    <span x-text="t.depot_destination"></span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    <div class="max-w-xs">
                                        <template x-for="(produit, idx) in t.produits.slice(0,2)"
                                            :key="idx">
                                            <div class="text-xs text-gray-600" x-text="produit"></div>
                                        </template>
                                        <div x-show="t.produits.length > 2" class="text-xs text-blue-600">
                                            +<span x-text="t.produits.length - 2"></span> autres
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" x-text="t.vehicule">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <template x-if="t.statut === 'en_preparation'">
                                        <span
                                            class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">En
                                            préparation</span>
                                    </template>
                                    <template x-if="t.statut === 'en_transit'">
                                        <span
                                            class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">En
                                            transit</span>
                                    </template>
                                    <template x-if="t.statut === 'recu'">
                                        <span
                                            class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Reçu</span>
                                    </template>
                                    <template x-if="t.statut === 'annule'">
                                        <span
                                            class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Annulé</span>
                                    </template>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <template x-if="t.statut === 'en_preparation'">
                                            <button @click.prevent="changerStatut(t, 'en_transit')"
                                                class="text-blue-600 hover:text-blue-900">Démarrer</button>
                                        </template>
                                        <template x-if="t.statut === 'en_transit'">
                                            <button @click.prevent="changerStatut(t, 'recu')"
                                                class="text-green-600 hover:text-green-900">Confirmer</button>
                                        </template>
                                        <button @click.prevent="ouvrirDetails(t)"
                                            class="text-indigo-600 hover:text-indigo-900">Détails</button>
                                        <template x-if="t.statut === 'en_preparation'">
                                            <button @click.prevent="changerStatut(t, 'annule')"
                                                class="text-red-600 hover:text-red-900">Annuler</button>
                                        </template>
                                    </div>
                                </td>
                            </tr>
                        </template>
                        <template x-if="transferts.length === 0">
                            <tr>
                                <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">
                                    Aucun transfert trouvé
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <!-- Pagination simplifiée (placeholder) -->
            <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                <div>
                    <p class="text-sm text-gray-700">
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
            <div class="bg-white rounded-2xl shadow-xl max-w-2xl w-full overflow-hidden">
                <div class="px-6 py-4 flex justify-between items-center border-b">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Détails du transfert <span
                                x-text="'#' + detail.numero"></span></h2>
                        <p class="text-sm text-gray-500">Suivi complet</p>
                    </div>
                    <button @click="fermerDetails" class="text-gray-500 hover:text-gray-700"
                        aria-label="Fermer">✕</button>
                </div>
                <div class="px-6 py-4">
                    <!-- Timeline simplifiée -->
                    <div class="flow-root mb-6">
                        <ul class="-mb-8">
                            <template x-for="(etape, idx) in detail.timeline" :key="idx">
                                <li>
                                    <div class="relative pb-8">
                                        <div class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"></div>
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span
                                                    :class="etape.iconBg +
                                                        ' h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white'">
                                                    <svg class="w-4 h-4 text-white" x-html="etape.icon"></svg>
                                                </span>
                                            </div>
                                            <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                <div>
                                                    <p class="text-sm text-gray-500" x-text="etape.text"></p>
                                                </div>
                                                <div class="text-right text-sm whitespace-nowrap text-gray-500">
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
                            <h4 class="text-sm font-medium text-gray-900 mb-2">Informations générales</h4>
                            <dl class="space-y-1 text-sm">
                                <div class="flex justify-between">
                                    <dt class="text-gray-500">Origine:</dt>
                                    <dd class="text-gray-900" x-text="detail.depot_origine"></dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-500">Destination:</dt>
                                    <dd class="text-gray-900" x-text="detail.depot_destination"></dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-500">Véhicule:</dt>
                                    <dd class="text-gray-900" x-text="detail.vehicule"></dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-500">Chauffeur:</dt>
                                    <dd class="text-gray-900" x-text="detail.chauffeur"></dd>
                                </div>
                            </dl>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 mb-2">Produits transférés</h4>
                            <ul class="space-y-1 text-sm">
                                <template x-for="(prod, i) in detail.produits" :key="i">
                                    <li class="flex justify-between">
                                        <span class="text-gray-500" x-text="prod.nom + ':'"></span>
                                        <span class="text-gray-900" x-text="prod.quantite + ' kg'"></span>
                                    </li>
                                </template>
                                <li class="flex justify-between font-medium border-t pt-1">
                                    <span class="text-gray-900">Total:</span>
                                    <span class="text-gray-900"
                                        x-text="totalQuantite(detail.produits) + ' kg'"></span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-3 flex justify-end">
                    <button @click="fermerDetails"
                        class="px-4 py-2 rounded-md bg-gray-100 hover:bg-gray-200 text-sm">Fermer</button>
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
