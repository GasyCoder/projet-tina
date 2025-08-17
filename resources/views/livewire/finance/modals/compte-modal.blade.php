@if($showCompteModal)
<div class="fixed inset-0 z-50 overflow-y-auto">
  <div class="flex items-center justify-center min-h-screen px-4 py-6">

    <div class="fixed inset-0 bg-black bg-opacity-50 dark:bg-opacity-70 transition-opacity"
         wire:click="closeCompteModal"></div>

    <div class="bg-white dark:bg-gray-800 rounded-lg max-w-md w-full shadow-xl relative z-10">
      <div class="flex items-center justify-between p-6 border-b border-gray-100 dark:border-gray-700">
        <div>
          <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">
            {{ $editingCompte ? 'Modifier le compte' : 'Nouveau compte' }}
          </h2>
          <p class="text-sm text-gray-500 dark:text-gray-400">
            {{ $editingCompte ? 'Modifiez les informations du compte' : 'Créez un nouveau compte financier' }}
          </p>
        </div>
        <button wire:click="closeCompteModal"
                class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
      </div>

      <form wire:submit.prevent="saveCompte" class="p-6">
        <div class="space-y-5">

          {{-- Type de compte --}}
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Type de compte *</label>
            <select wire:model.live="type_compte"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-300">
              <option value="Principal">💰 Principal (Espèces/Caisse)</option>
              <option value="MobileMoney">📱 Mobile Money</option>
              <option value="Banque">🏦 Banque</option>
            </select>
            @error('type_compte') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
          </div>

          {{-- Sous-type : opérateur ou banque --}}
          @if(in_array($type_compte, ['MobileMoney','Banque']))
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                {{ $type_compte === 'MobileMoney' ? 'Opérateur Mobile Money *' : 'Banque *' }}
              </label>

              @if($type_compte === 'MobileMoney')
                <select wire:model="type_compte_mobilemoney_or_banque"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-300">
                  <option value="" disabled>— Choisir —</option>
                  @foreach($mmSousTypes as $op)
                    <option value="{{ $op }}">{{ $op }}</option>
                  @endforeach
                </select>
              @else
                {{-- input + datalist pour autoriser la saisie libre (BNI, BFV, …) --}}
                <input type="text" list="banques-list"
                       wire:model="type_compte_mobilemoney_or_banque"
                       placeholder="Ex: BNI, BFV, BOA..."
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-300"/>
                <datalist id="banques-list">
                  @foreach($bankSousTypes as $b)
                    <option value="{{ $b }}"></option>
                  @endforeach
                </datalist>
              @endif

              @error('type_compte_mobilemoney_or_banque')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
              @enderror
            </div>
          @endif

          {{-- Propriétaire (caché, si tu veux l’afficher remplace par un input visible) --}}
          <input wire:model="nom_proprietaire" type="hidden">

          {{-- Numéro de compte (affiché sauf Principal) --}}
          @if($type_compte !== 'Principal')
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Numéro de compte</label>
              <input wire:model="numero_compte" type="text"
                     class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-300"
                     placeholder="Ex: 034 12 345 67 (Mvola) / RIB/IBAN (Banque)">
              @error('numero_compte') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>
          @endif

          {{-- Solde actuel --}}
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Solde actuel (MGA)</label>
            <input wire:model="solde_actuel_mga" type="number" step="0.01"
                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-300"
                   placeholder="0">
            @if($solde_actuel_mga)
              <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                ≈ {{ number_format($solde_actuel_mga, 0, ',', ' ') }} Ariary
              </p>
            @endif
            @error('solde_actuel_mga') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
          </div>

          {{-- Actif --}}
          <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-md">
            <div>
              <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Compte actif</label>
              <p class="text-xs text-gray-500 dark:text-gray-400">Le compte peut être utilisé pour les transactions</p>
            </div>
            <input wire:model="compte_actif" type="checkbox"
                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600 rounded">
          </div>
        </div>

        <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-100 dark:border-gray-700">
          <button type="button" wire:click="closeCompteModal"
                  class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-600 hover:bg-gray-200 dark:hover:bg-gray-500 rounded-md">
            Annuler
          </button>
          <button type="submit"
                  class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md"
                  wire:loading.attr="disabled" wire:target="saveCompte">
            <span wire:loading.remove wire:target="saveCompte">{{ $editingCompte ? 'Modifier' : 'Créer' }} le compte</span>
            <span wire:loading wire:target="saveCompte">Sauvegarde...</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endif
