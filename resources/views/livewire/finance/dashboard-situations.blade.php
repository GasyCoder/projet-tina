<div class="min-h-screen bg-gray-50 pt-20 pb-6 px-4 md:px-6">
    <div class="space-y-6 max-w-7xl mx-auto">
        <!-- En-tête du dashboard -->
        <div class="bg-white shadow rounded-lg p-4 sm:p-6">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                <div>
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Dashboard situations financières</h2>
                    <p class="text-sm sm:text-base text-gray-600">Aperçu global de la situation financière par lieu</p>
                </div>
                <div class="flex flex-col sm:flex-row gap-2">
                    <select wire:model.live="periodeSelectionnee" class="rounded-md border-gray-300 text-sm">
                        <option value="this_month">Ce mois</option>
                        <option value="last_month">Mois dernier</option>
                        <option value="this_year">Cette année</option>
                        <option value="custom">Période personnalisée</option>
                    </select>
                </div>
            </div>

            @if($periodeSelectionnee === 'custom')
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Date début</label>
                        <input wire:model.live="dateDebutCustom" type="date" class="mt-1 block w-full rounded-md border-gray-300">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Date fin</label>
                        <input wire:model.live="dateFinCustom" type="date" class="mt-1 block w-full rounded-md border-gray-300">
                    </div>
                </div>
            @endif
        </div>

        <!-- Métriques principales -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            <!-- Total Initial -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-4 sm:p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                                <span class="text-white text-sm">💵</span>
                            </div>
                        </div>
                        <div class="ml-4 sm:ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">
                                    Total Initial
                                </dt>
                                <dd class="text-base sm:text-lg font-medium text-gray-900">
                                    {{ number_format($resumeGlobal->total_initial ?? 0, 0, ',', ' ') }} MGA
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Final -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-4 sm:p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                                <span class="text-white text-sm">💰</span>
                            </div>
                        </div>
                        <div class="ml-4 sm:ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">
                                    Total Final
                                </dt>
                                <dd class="text-base sm:text-lg font-medium text-gray-900">
                                    {{ number_format($resumeGlobal->total_final ?? 0, 0, ',', ' ') }} MGA
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Écart Total -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-4 sm:p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            @php $ecartTotal = ($resumeGlobal->ecart_total ?? 0) @endphp
                            <div class="w-8 h-8 {{ $ecartTotal >= 0 ? 'bg-green-500' : 'bg-red-500' }} rounded-md flex items-center justify-center">
                                <span class="text-white text-sm">{{ $ecartTotal >= 0 ? '📈' : '📉' }}</span>
                            </div>
                        </div>
                        <div class="ml-4 sm:ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">
                                    Écart Total
                                </dt>
                                <dd class="text-base sm:text-lg font-medium {{ $ecartTotal >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $ecartTotal >= 0 ? '+' : '' }}{{ number_format($ecartTotal, 0, ',', ' ') }} MGA
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Nombre d'entrées -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-4 sm:p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                                <span class="text-white text-sm">📋</span>
                            </div>
                        </div>
                        <div class="ml-4 sm:ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">
                                    Nombre d'entrées
                                </dt>
                                <dd class="text-base sm:text-lg font-medium text-gray-900">
                                    {{ $resumeGlobal->nombre_entrees ?? 0 }}
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Répartition par lieu -->
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-4 sm:gap-6">
            <!-- Tableau par lieu -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
                    <h3 class="text-base sm:text-lg font-medium text-gray-900">📍 Répartition par lieu</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Lieu
                                </th>
                                <th class="px-4 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Écart
                                </th>
                                <th class="px-4 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Entrées
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($totauxParLieu as $lieu)
                                <tr>
                                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                            {{ $lieu->lieu === 'mahajanga' ? 'bg-blue-100 text-blue-800' : 
                                               ($lieu->lieu === 'antananarivo' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800') }}">
                                            @switch($lieu->lieu)
                                                @case('mahajanga') 🏢 Mahajanga @break
                                                @case('antananarivo') 🏢 Antananarivo @break
                                                @default 🏢 {{ ucfirst($lieu->lieu) }}
                                            @endswitch
                                        </span>
                                    </td>
                                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <span class="text-{{ $lieu->ecart_total >= 0 ? 'green' : 'red' }}-600">
                                            {{ $lieu->ecart_total >= 0 ? '+' : '' }}{{ number_format($lieu->ecart_total, 0, ',', ' ') }} MGA
                                        </span>
                                    </td>
                                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-right text-sm text-gray-900">
                                        {{ $lieu->nombre_entrees }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-center text-gray-500">
                                        Aucune donnée disponible
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Graphique simple -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
                    <h3 class="text-base sm:text-lg font-medium text-gray-900">📊 Performance par lieu</h3>
                </div>
                <div class="p-4 sm:p-6">
                    @foreach($totauxParLieu as $lieu)
                        <div class="mb-4">
                            <div class="flex justify-between text-sm">
                                <span class="font-medium">
                                    @switch($lieu->lieu)
                                        @case('mahajanga') 🏢 Mahajanga @break
                                        @case('antananarivo') 🏢 Antananarivo @break
                                        @default 🏢 {{ ucfirst($lieu->lieu) }}
                                    @endswitch
                                </span>
                                <span class="text-{{ $lieu->ecart_total >= 0 ? 'green' : 'red' }}-600 font-medium">
                                    {{ $lieu->ecart_total >= 0 ? '+' : '' }}{{ number_format($lieu->ecart_total, 0, ',', ' ') }} MGA
                                </span>
                            </div>
                            <div class="mt-1">
                                @php
                                    $maxEcart = $totauxParLieu->max('ecart_total');
                                    $minEcart = $totauxParLieu->min('ecart_total');
                                    $range = $maxEcart - $minEcart;
                                    $percentage = $range > 0 ? (($lieu->ecart_total - $minEcart) / $range) * 100 : 50;
                                @endphp
                                <div class="bg-gray-200 rounded-full h-2">
                                    <div class="h-2 rounded-full {{ $lieu->ecart_total >= 0 ? 'bg-green-500' : 'bg-red-500' }}" 
                                         style="width: {{ max(5, $percentage) }}%"></div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    
                    @if($totauxParLieu->isEmpty())
                        <div class="text-center text-gray-500 py-6 sm:py-8">
                            <svg class="mx-auto h-10 sm:h-12 w-10 sm:w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            <p class="mt-2 text-sm">Aucune donnée à afficher</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Dernières situations -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2">
                    <h3 class="text-base sm:text-lg font-medium text-gray-900">📋 Dernières situations</h3>
                    <a href="{{ route('finance.situations') }}" class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                        Voir tout →
                    </a>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date & Description
                            </th>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">
                                Lieu
                            </th>
                            <th class="px-4 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Montants
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($dernieresSituations as $situation)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 sm:px-6 py-4">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $situation->description }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            📅 {{ $situation->date_situation->format('d/m/Y') }}
                                        </div>
                                        <div class="sm:hidden mt-1">
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                                {{ $situation->lieu === 'mahajanga' ? 'bg-blue-100 text-blue-800' : 
                                                   ($situation->lieu === 'antananarivo' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800') }}">
                                                {{ $situation->lieu_label }}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap hidden sm:table-cell">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                        {{ $situation->lieu === 'mahajanga' ? 'bg-blue-100 text-blue-800' : 
                                           ($situation->lieu === 'antananarivo' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800') }}">
                                        {{ $situation->lieu_label }}
                                    </span>
                                </td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-right">
                                    <div class="text-sm">
                                        <div>Initial: {{ number_format($situation->montant_initial, 0, ',', ' ') }}</div>
                                        <div>Final: {{ number_format($situation->montant_final, 0, ',', ' ') }}</div>
                                        <div class="font-medium text-{{ $situation->ecart >= 0 ? 'green' : 'red' }}-600">
                                            Écart: {{ $situation->ecart >= 0 ? '+' : '' }}{{ number_format($situation->ecart, 0, ',', ' ') }}
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-center text-gray-500">
                                    Aucune situation récente
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>