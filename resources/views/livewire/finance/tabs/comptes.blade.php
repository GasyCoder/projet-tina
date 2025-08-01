<div class="space-y-4">
    @foreach($comptes as $compte)
        <div class="border rounded-lg p-4 flex justify-between items-center bg-white">
            <div>
                <h3 class="text-sm font-medium text-gray-900">{{ $compte->nom }}</h3>
                <p class="text-xs text-gray-500">{{ $compte->type_compte }}</p>
                @if($compte->nom_proprietaire)
                    <p class="text-xs text-gray-500">Propriétaire: {{ $compte->nom_proprietaire }}</p>
                @endif
                @if($compte->numero_compte)
                    <p class="text-xs text-gray-500">N°: {{ $compte->numero_compte }}</p>
                @endif
                <p class="text-sm font-semibold text-gray-900">{{ number_format($compte->solde_actuel_mga, 0, ',', ' ') }} MGA</p>
            </div>
            <div class="flex space-x-2">
                <button wire:click="$dispatch('finance.compte-manager', 'editCompte', {{ $compte->id }})"
                        class="text-indigo-600 hover:text-indigo-900">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </button>
                <button wire:click="$dispatch('finance.compte-manager', 'deleteCompte', {{ $compte->id }})"
                        class="text-red-600 hover:text-red-900">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4m-4 0h4m-7 4h14" />
                    </svg>
                </button>
            </div>
        </div>
    @endforeach
</div>