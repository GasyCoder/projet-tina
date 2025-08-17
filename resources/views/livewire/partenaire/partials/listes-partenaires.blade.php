{{-- En-tête desktop --}}
<div class="hidden lg:grid grid-cols-12 gap-4 mb-3 px-4 py-3 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
    <div class="col-span-3 text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Référence & Date</div>
    <div class="col-span-4 text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Motif</div>
    <div class="col-span-3 text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider text-right">Montant</div>
    <div class="col-span-2 text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider text-center">Actions</div>
</div>

{{-- Liste --}}
<div class="space-y-3">
    @forelse($transactions as $transaction)
        @php
            $isEntree = $transaction->type === 'entree';
            
            // Calculer montant disponible pour les entrées
            if ($isEntree) {
                $montantUtilise = \App\Models\PartenaireTransaction::where('entree_source_id', $transaction->id)
                    ->where('type', 'sortie')
                    ->sum('montant_mga');
                $montantDisponible = $transaction->montant_mga - $montantUtilise;
            } else {
                $montantDisponible = 0;
                $montantUtilise = 0;
            }
        @endphp

        {{-- Afficher seulement si ce n'est pas une sortie liée à une entrée --}}
        @if($transaction->type === 'entree' || ($transaction->type === 'sortie' && !$transaction->entree_source_id))
            
            {{-- Version Desktop --}}
            <div class="hidden lg:grid grid-cols-12 gap-4 p-4 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 hover:shadow-lg hover:border-blue-300 dark:hover:border-blue-600 transition-all duration-300 group">
                
                {{-- Référence & Date --}}
                <div wire:click="voirSortiesEntree({{ $transaction->id }})" class="col-span-3 cursor-pointer">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-lg flex items-center justify-center {{ $isEntree ? 'bg-blue-100 dark:bg-blue-900/30' : 'bg-red-100 dark:bg-red-900/30' }}">
                            @if($isEntree)
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                            @else
                                <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                </svg>
                            @endif
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                {{ $transaction->reference }}
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $transaction->date_transaction->format('d/m/Y à H:i') }}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Motif --}}
                <div wire:click="voirSortiesEntree({{ $transaction->id }})" class="col-span-4 cursor-pointer flex items-center">
                    <div class="flex items-center space-x-2">
                        <span class="px-3 py-1 rounded-full text-xs font-medium {{ $isEntree ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/20 dark:text-blue-300' : 'bg-red-50 text-red-700 dark:bg-red-900/20 dark:text-red-300' }}">
                            {{ $isEntree ? 'Entrée' : 'Sortie' }}
                        </span>
                        <span class="text-gray-700 dark:text-gray-300 truncate">{{ $transaction->motif }}</span>
                    </div>
                </div>

                {{-- Montant --}}
                <div wire:click="voirSortiesEntree({{ $transaction->id }})" class="col-span-3 cursor-pointer text-right">
                    @if($isEntree)
                        <div class="space-y-1">
                            @if($montantDisponible > 0)
                                <div class="text-lg font-bold text-green-600 dark:text-green-400">
                                    {{ number_format($montantDisponible, 0, ',', ' ') }} Ar
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    Disponible sur {{ number_format($transaction->montant_mga, 0, ',', ' ') }} Ar
                                </div>
                                @if($montantUtilise > 0)
                                    <div class="text-xs text-orange-600 dark:text-orange-400">
                                        {{ number_format($montantUtilise, 0, ',', ' ') }} Ar utilisé
                                    </div>
                                @endif
                            @else
                                <div class="text-lg font-bold text-gray-400">
                                    Épuisé
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ number_format($transaction->montant_mga, 0, ',', ' ') }} Ar entièrement utilisé
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="text-lg font-bold text-red-600 dark:text-red-400">
                            {{ $transaction->montant_formatted }}
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">
                            Sortie générale
                        </div>
                    @endif
                </div>
                
                {{-- Actions --}}
                <div class="col-span-2 flex items-center justify-center space-x-2">
                    @if($isEntree)
                        <button wire:click="ouvrirSortieDepuisEntree({{ $transaction->id }})"
                                @if($montantDisponible <= 0) 
                                    disabled 
                                    class="px-3 py-2 bg-gray-100 text-gray-400 text-xs rounded-lg cursor-not-allowed flex items-center space-x-1 border"
                                @else
                                    class="px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-xs rounded-lg transition-all duration-200 flex items-center space-x-1 shadow-sm hover:shadow-md transform hover:-translate-y-0.5"
                                @endif
                                title="Nouvelle sortie">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                            </svg>
                            <span>Sortie</span>
                        </button>
                        <button wire:click="voirSortiesEntree({{ $transaction->id }})"
                                class="px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs rounded-lg transition-all duration-200 flex items-center space-x-1 shadow-sm hover:shadow-md transform hover:-translate-y-0.5"
                                title="Voir sorties">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <span>Detail</span>
                        </button>
                    @else
                        <div class="px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 text-xs rounded-lg border">
                            <span>Sortie générale</span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Version Mobile/Tablet --}}
            <div class="lg:hidden bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-lg transition-all duration-300">
                
                {{-- Header Mobile --}}
                <div wire:click="voirSortiesEntree({{ $transaction->id }})" class="p-4 cursor-pointer">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center {{ $isEntree ? 'bg-blue-100 dark:bg-blue-900/30' : 'bg-red-100 dark:bg-red-900/30' }}">
                                @if($isEntree)
                                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                @else
                                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                    </svg>
                                @endif
                            </div>
                            <div>
                                <div class="font-semibold text-gray-900 dark:text-white">
                                    {{ $transaction->reference }}
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $transaction->date_transaction->format('d/m/Y à H:i') }}
                                </div>
                            </div>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-medium {{ $isEntree ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/20 dark:text-blue-300' : 'bg-red-50 text-red-700 dark:bg-red-900/20 dark:text-red-300' }}">
                            {{ $isEntree ? 'Entrée' : 'Sortie' }}
                        </span>
                    </div>

                    {{-- Motif --}}
                    <div class="mb-3">
                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ $transaction->motif }}
                        </div>
                    </div>

                    {{-- Montant --}}
                    <div class="flex items-center justify-between">
                        <div>
                            @if($isEntree)
                                @if($montantDisponible > 0)
                                    <div class="text-xl font-bold text-green-600 dark:text-green-400">
                                        {{ number_format($montantDisponible, 0, ',', ' ') }} Ar
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        Disponible / {{ number_format($transaction->montant_mga, 0, ',', ' ') }} Ar
                                    </div>
                                    @if($montantUtilise > 0)
                                        <div class="text-xs text-orange-600 dark:text-orange-400">
                                            {{ number_format($montantUtilise, 0, ',', ' ') }} Ar utilisé
                                        </div>
                                    @endif
                                @else
                                    <div class="text-xl font-bold text-gray-400">
                                        Épuisé
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ number_format($transaction->montant_mga, 0, ',', ' ') }} Ar utilisé
                                    </div>
                                @endif
                            @else
                                <div class="text-xl font-bold text-red-600 dark:text-red-400">
                                    {{ $transaction->montant_formatted }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    Sortie générale
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Actions Mobile --}}
                @if($isEntree)
                    <div class="border-t border-gray-100 dark:border-gray-700 p-4">
                        <div class="flex space-x-3">
                            <button wire:click="ouvrirSortieDepuisEntree({{ $transaction->id }})"
                                    @if($montantDisponible <= 0) 
                                        disabled 
                                        class="flex-1 px-4 py-3 bg-gray-100 text-gray-400 text-sm rounded-lg cursor-not-allowed flex items-center justify-center space-x-2 border"
                                    @else
                                        class="flex-1 px-4 py-3 bg-red-600 hover:bg-red-700 text-white text-sm rounded-lg transition-all duration-200 flex items-center justify-center space-x-2 shadow-sm active:scale-95"
                                    @endif
                                    title="Nouvelle sortie">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                </svg>
                                <span>Créer Sortie</span>
                            </button>
                            <button wire:click="voirSortiesEntree({{ $transaction->id }})"
                                    class="flex-1 px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition-all duration-200 flex items-center justify-center space-x-2 shadow-sm active:scale-95"
                                    title="Voir sorties">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <span>Supp.</span>
                            </button>
                            <button wire:click="voirSortiesEntree({{ $transaction->id }})"
                                    class="flex-1 px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition-all duration-200 flex items-center justify-center space-x-2 shadow-sm active:scale-95"
                                    title="Voir sorties">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <span>Voir Sorties</span>
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        @endif
    @empty
        <div class="text-center py-16 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
            <div class="w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6M7 21h10a2 2 0 002-2V7a2 2 0 00-2-2h-6.586a1 1 0 00-.707.293l-5.414 5.414A1 1 0 003 11.414V19a2 2 0 002 2z"/>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Aucune transaction</h3>
            <p class="text-gray-500 dark:text-gray-400">Aucune transaction trouvée pour ce partenaire</p>
        </div>
    @endforelse
</div>