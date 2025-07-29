<div class="space-y-6">
    <!-- Filtres période -->
    <div class="bg-gray-50 p-4 rounded-lg">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Du</label>
                <input wire:model.live="dateDebut" type="date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Au</label>
                <input wire:model.live="dateFin" type="date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm">
            </div>
            <div class="flex items-end">
                <button wire:click="genererRapport" class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    📊 Générer Rapport
                </button>
            </div>
        </div>
    </div>



    <!-- Résumé par type -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Types d'entrées -->
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-lg font-medium text-gray-900 mb-4 text-green-600">💰 Entrées par Type</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Ventes produits</span>
                    <span class="font-medium text-green-600">{{ number_format(random_int(1000000, 5000000), 0) }} MGA</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Entrées diverses</span>
                    <span class="font-medium text-green-600">{{ number_format(random_int(500000, 2000000), 0) }} MGA</span>
                </div>
                <div class="border-t pt-2 flex justify-between items-center font-semibold">
                    <span>Total Entrées</span>
                    <span class="text-green-600">{{ number_format($totalEntrees, 0) }} MGA</span>
                </div>
            </div>
        </div>

        <!-- Types de sorties -->
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-lg font-medium text-gray-900 mb-4 text-red-600">💸 Sorties par Type</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Achats produits</span>
                    <span class="font-medium text-red-600">{{ number_format(random_int(800000, 3000000), 0) }} MGA</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Frais voyages</span>
                    <span class="font-medium text-red-600">{{ number_format(random_int(200000, 800000), 0) }} MGA</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Salaires</span>
                    <span class="font-medium text-red-600">{{ number_format(random_int(300000, 1000000), 0) }} MGA</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Autres sorties</span>
                    <span class="font-medium text-red-600">{{ number_format(random_int(100000, 500000), 0) }} MGA</span>
                </div>
                <div class="border-t pt-2 flex justify-between items-center font-semibold">
                    <span>Total Sorties</span>
                    <span class="text-red-600">{{ number_format($totalSorties, 0) }} MGA</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Top voyages rentables -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-lg font-medium text-gray-900 mb-4">🚛 Top 5 Voyages les Plus Rentables</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Voyage</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Revenus</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Coûts</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bénéfice</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach(range(1, 5) as $i)
                        @php
                            $revenus = random_int(800000, 2000000);
                            $couts = random_int(400000, 1200000);
                            $benefice = $revenus - $couts;
                        @endphp
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                VOY{{ str_pad($i, 3, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ now()->subDays(rand(1, 30))->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600">
                                {{ number_format($revenus, 0) }} MGA
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600">
                                {{ number_format($couts, 0) }} MGA
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-{{ $benefice > 0 ? 'green' : 'red' }}-600">
                                {{ number_format($benefice, 0) }} MGA
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Actions export -->
    <div class="flex justify-end space-x-4">
        <button class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
            📄 Export PDF
        </button>
        <button class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
            📊 Export Excel
        </button>
    </div>
</div>