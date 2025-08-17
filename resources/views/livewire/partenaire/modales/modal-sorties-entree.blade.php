{{-- Modal pour voir toutes les sorties d'une entrée spécifique --}}
@if($showSortiesEntreeModal && $entreeSource)
@php
    $totalUtilise = collect($sortiesEntree)->sum('montant_mga');
    $disponible = $entreeSource->montant_mga - $totalUtilise;
    $pourcentageUtilise = $entreeSource->montant_mga > 0 ? ($totalUtilise / $entreeSource->montant_mga) * 100 : 0;
@endphp

<div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-2 md:p-4 z-50">
    <div class="bg-white dark:bg-gray-800 rounded-lg md:rounded-xl shadow-xl w-full max-w-5xl max-h-screen overflow-hidden flex flex-col" style="max-height: 90vh">
        
        {{-- Header Modal --}}
        <div class="p-4 md:p-6 border-b border-gray-200 dark:border-gray-700">
            <div class="flex justify-between items-start">
                <div class="pr-2">
                    <h3 class="text-lg md:text-xl font-bold text-gray-900 dark:text-white flex items-center">
                        <svg class="w-5 h-5 md:w-6 md:h-6 mr-2 md:mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        Sorties - {{ $partenaire->nom }}
                    </h3>
                    <p class="text-xs md:text-sm text-gray-500 dark:text-gray-400 mt-1 truncate">
                        Entrée {{ $entreeSource->reference }} • {{ $entreeSource->motif }}
                    </p>
                </div>
                <button wire:click="fermerSortiesEntreeModal" 
                        class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 transition-colors p-1 md:p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                    <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        {{-- Résumé pour mobile --}}
        <div class="md:hidden p-3 bg-blue-50 dark:bg-blue-900/20 border-b border-gray-200 dark:border-gray-700">
            <div class="flex justify-between items-center space-x-2">
                <div class="text-center flex-1">
                    <p class="text-xs text-blue-600 dark:text-blue-400">Initial</p>
                    <p class="text-sm font-bold">{{ number_format($entreeSource->montant_mga, 0, ',', ' ') }} Ar</p>
                </div>
                <div class="text-center flex-1">
                    <p class="text-xs text-orange-600 dark:text-orange-400">Utilisé</p>
                    <p class="text-sm font-bold">{{ number_format($totalUtilise, 0, ',', ' ') }} Ar</p>
                </div>
                <div class="text-center flex-1">
                    <p class="text-xs text-green-600 dark:text-green-400">Disponible</p>
                    <p class="text-sm font-bold">{{ number_format($disponible, 0, ',', ' ') }} Ar</p>
                </div>
            </div>
            <div class="mt-2">
                <div class="text-xs text-blue-600 dark:text-blue-400 mb-1 flex justify-between">
                    <span>{{ number_format($pourcentageUtilise, 1) }}% utilisé</span>
                    <span>{{ number_format($totalUtilise, 0, ',', ' ') }}/{{ number_format($entreeSource->montant_mga, 0, ',', ' ') }} Ar</span>
                </div>
                <div class="w-full bg-blue-200 dark:bg-blue-800 rounded-full h-1.5">
                    <div class="bg-blue-600 dark:bg-blue-400 h-1.5 rounded-full" 
                         style="width: {{ min($pourcentageUtilise, 100) }}%"></div>
                </div>
            </div>
        </div>

        {{-- Résumé pour desktop --}}
        <div class="hidden md:block p-6 bg-gradient-to-r from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/30 border-b border-gray-200 dark:border-gray-700">
            <div class="grid grid-cols-3 gap-4">
                <div class="text-left">
                    <p class="text-sm font-medium text-blue-600 dark:text-blue-400 mb-1">Montant Initial</p>
                    <p class="text-2xl font-bold text-blue-900 dark:text-blue-100">
                        {{ number_format($entreeSource->montant_mga, 0, ',', ' ') }} Ar
                    </p>
                </div>
                
                <div class="text-center">
                    <p class="text-sm font-medium text-orange-600 dark:text-orange-400 mb-1">Montant Utilisé</p>
                    <p class="text-2xl font-bold text-orange-700 dark:text-orange-300">
                        {{ number_format($totalUtilise, 0, ',', ' ') }} Ar
                    </p>
                </div>
                
                <div class="text-right">
                    <p class="text-sm font-medium text-green-600 dark:text-green-400 mb-1">Montant Disponible</p>
                    <p class="text-2xl font-bold {{ $disponible > 0 ? 'text-green-700 dark:text-green-300' : 'text-gray-500 dark:text-gray-400' }}">
                        {{ number_format($disponible, 0, ',', ' ') }} Ar
                    </p>
                </div>
            </div>
            
            <div class="mt-4">
                <div class="flex items-center justify-between text-xs text-blue-600 dark:text-blue-400 mb-1">
                    <span>Progression d'utilisation</span>
                    <span>{{ number_format($pourcentageUtilise, 1) }}%</span>
                </div>
                <div class="w-full bg-blue-200 dark:bg-blue-800 rounded-full h-2">
                    <div class="bg-blue-600 dark:bg-blue-400 h-2 rounded-full" 
                         style="width: {{ min($pourcentageUtilise, 100) }}%"></div>
                </div>
            </div>
        </div>

        {{-- Contenu principal - Version Mobile --}}
        <div class="flex-1 overflow-y-auto md:hidden">
            @if(!empty($sortiesEntree) && count($sortiesEntree) > 0)
            <div class="p-3 sticky top-0 bg-white dark:bg-gray-800 z-10 border-b border-gray-200 dark:border-gray-700 shadow-sm">
                <h4 class="text-base font-semibold flex items-center">
                    <svg class="w-4 h-4 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                    </svg>
                    {{ count($sortiesEntree) }} sortie{{ count($sortiesEntree) > 1 ? 's' : '' }}
                </h4>
            </div>

            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($sortiesEntree as $sortie)
                <div class="p-3">
                    <div class="flex justify-between items-start">
                        <div class="flex items-center space-x-2">
                            <div class="w-8 h-8 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $sortie['reference'] }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ \Carbon\Carbon::parse($sortie['date_transaction'])->format('d/m/Y H:i') }}
                                </p>
                            </div>
                        </div>
                        <p class="text-base font-bold text-red-600 dark:text-red-400">
                            {{ number_format($sortie['montant_mga'], 0, ',', ' ') }} Ar
                        </p>
                    </div>
                    
                    <p class="mt-1 text-sm text-gray-700 dark:text-gray-300">{{ $sortie['motif'] }}</p>
                    
                    @if(!empty($sortie['observation']))
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $sortie['observation'] }}</p>
                    @endif

                    @if(!empty($sortie['details']) && count($sortie['details']) > 0)
                    <div class="mt-2">
                        <div class="flex items-center text-xs text-blue-600 dark:text-blue-400 mb-1">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6M7 21h10a2 2 0 002-2V7a2 2 0 00-2-2h-6.586a1 1 0 00-.707.293l-5.414 5.414A1 1 0 003 11.414V19a2 2 0 002 2z"></path>
                            </svg>
                            Détails ({{ count($sortie['details']) }})
                        </div>
                        
                        <div class="space-y-2">
                            @foreach($sortie['details'] as $detail)
                            <div class="p-2 bg-blue-50 dark:bg-blue-900/10 rounded text-xs">
                                <div class="flex justify-between">
                                    <span class="font-medium">{{ $detail['description'] }}</span>
                                    <span class="font-bold">{{ number_format($detail['montant_mga'], 0, ',', ' ') }} Ar</span>
                                </div>
                                @if(!empty($detail['quantite']) && !empty($detail['prix_unitaire_mga']))
                                <div class="text-gray-500 dark:text-gray-400 mt-1">
                                    {{ $detail['quantite'] }} × {{ number_format($detail['prix_unitaire_mga'], 0, ',', ' ') }} Ar
                                </div>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
            @else
            <div class="p-8 text-center">
                <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                    </svg>
                </div>
                <h3 class="text-base font-semibold text-gray-900 dark:text-white">Aucune sortie</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Cette entrée n'a pas été utilisée</p>
            </div>
            @endif
        </div>

        {{-- Contenu principal - Version Desktop --}}
        <div class="hidden md:block flex-1 overflow-y-auto p-6">
            @if(!empty($sortiesEntree) && count($sortiesEntree) > 0)
                <div class="mb-4">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                        <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                        </svg>
                        Sorties effectuées ({{ count($sortiesEntree) }})
                    </h4>
                </div>

                <div class="space-y-4">
                    @foreach($sortiesEntree as $sortie)
                        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-lg overflow-hidden hover:shadow-md transition-all">
                            <div class="grid grid-cols-9 gap-4 p-4 bg-gray-50 dark:bg-gray-700">
                                <div class="col-span-3">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="font-semibold text-gray-900 dark:text-white">
                                                {{ $sortie['reference'] }}
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ \Carbon\Carbon::parse($sortie['date_transaction'])->format('d/m/Y à H:i') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-span-4 flex items-center">
                                    <div>
                                        <div class="font-medium text-gray-900 dark:text-white">
                                            {{ $sortie['motif'] }}
                                        </div>
                                        @if(!empty($sortie['observation']))
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ \Illuminate\Support\Str::limit($sortie['observation'], 50) }}
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-span-2 text-right flex items-center justify-end">
                                    <div class="text-lg font-bold text-red-600 dark:text-red-400">
                                        {{ number_format($sortie['montant_mga'], 0, ',', ' ') }} Ar
                                    </div>
                                </div>
                            </div>

                            @if(!empty($sortie['details']) && count($sortie['details']) > 0)
                                <div class="p-4 border-t border-gray-200 dark:border-gray-600">
                                    <h5 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3 flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6M7 21h10a2 2 0 002-2V7a2 2 0 00-2-2h-6.586a1 1 0 00-.707.293l-5.414 5.414A1 1 0 003 11.414V19a2 2 0 002 2z"></path>
                                        </svg>
                                        Détails ({{ count($sortie['details']) }})
                                    </h5>
                                    
                                    <div class="grid grid-cols-12 gap-3 mb-2 px-3 py-2 bg-blue-50 dark:bg-blue-900/20 rounded-lg text-xs font-semibold text-blue-700 dark:text-blue-300 uppercase">
                                        <div class="col-span-5">Description</div>
                                        <div class="col-span-2 text-center">Quantité</div>
                                        <div class="col-span-2 text-right">Prix Unit.</div>
                                        <div class="col-span-3 text-right">Montant</div>
                                    </div>

                                    <div class="space-y-2">
                                        @foreach($sortie['details'] as $detail)
                                            <div class="grid grid-cols-12 gap-3 items-center p-3 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-600 rounded-lg">
                                                <div class="col-span-5">
                                                    <div class="font-medium text-gray-900 dark:text-white text-sm">
                                                        {{ $detail['description'] }}
                                                    </div>
                                                    @if(!empty($detail['type_detail']) && $detail['type_detail'] !== 'autre')
                                                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                            {{ ucfirst(str_replace('_', ' ', $detail['type_detail'])) }}
                                                        </div>
                                                    @endif
                                                </div>

                                                <div class="col-span-2 text-center text-sm text-gray-600 dark:text-gray-300">
                                                    {{ $detail['quantite'] ?? 1 }}.{{ $detail['unite'] ?? 'N/A'}}
                                                </div>

                                                <div class="col-span-2 text-right text-sm text-gray-600 dark:text-gray-300">
                                                    @if(!empty($detail['prix_unitaire_mga']) && is_numeric($detail['prix_unitaire_mga']))
                                                        {{ number_format($detail['prix_unitaire_mga'], 0, ',', ' ') }} Ar
                                                    @else
                                                        -
                                                    @endif
                                                </div>

                                                <div class="col-span-3 text-right">
                                                    <div class="font-bold text-gray-900 dark:text-white">
                                                        {{ number_format($detail['montant_mga'], 0, ',', ' ') }} Ar
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <div class="p-4 border-t border-gray-200 dark:border-gray-600">
                                    <p class="text-sm text-gray-500 dark:text-gray-400 italic text-center">
                                        Aucun détail pour cette sortie
                                    </p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Aucune sortie effectuée</h3>
                    <p class="text-gray-500 dark:text-gray-400">Cette entrée n'a pas encore été utilisée</p>
                </div>
            @endif
        </div>

        {{-- Footer --}}
        <div class="border-t border-gray-200 dark:border-gray-700 p-3 md:p-6">
            <div class="flex justify-between items-center">
                <div class="text-xs md:text-sm text-gray-500 dark:text-gray-400">
                    @if(!empty($sortiesEntree) && count($sortiesEntree) > 0)
                        Total utilisé : {{ number_format($totalUtilise, 0, ',', ' ') }} Ar
                    @else
                        Entrée disponible
                    @endif
                </div>
                <button wire:click="fermerSortiesEntreeModal" 
                        class="px-4 py-1.5 md:px-6 md:py-2 text-sm md:text-base border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    Fermer
                </button>
            </div>
        </div>
    </div>
</div>
@endif