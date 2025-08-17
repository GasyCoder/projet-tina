<!-- Modal Nouvelle Entrée -->
@if($showNewEntreeModal)
<div class="fixed inset-0 bg-black/50 flex items-center justify-center p-4 z-50">
  <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-md transform transition-all duration-300">
    <div class="p-6">
      <div class="flex justify-between items-start mb-6">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Nouvelle Entrée</h3>
        <button wire:click="closeNewEntreeModal" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 transition-colors">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
      </div>

      {{-- IMPORTANT: .prevent pour éviter le rechargement --}}
      <form wire:submit.prevent="creerEntree" class="space-y-4">
        {{-- Montant --}}
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Montant (Ar)</label>
          <input type="number"  wire:model.live.debounce.400ms="entreeForm.montant_mga" step="0.01" min="0"
                 class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                 placeholder="Ex: 50 000">
          @error('entreeForm.montant_mga') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        {{-- Motif --}}
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Motif</label>
          <input type="text" wire:model.lazy="entreeForm.motif"
                 class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                 placeholder="Ex: Transfert d'argent">
          @error('entreeForm.motif') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        {{-- Mode de paiement (type) --}}
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Mode de Paiement</label>
          <select wire:model.live="entreeForm.mode_paiement"
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
            <option value="especes">Espèces (Compte Principal)</option>
            <option value="MobileMoney">MobileMoney</option>
            <option value="Banque">Banque</option>
          </select>
          @error('entreeForm.mode_paiement') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        {{-- Sous-type (affiché uniquement si nécessaire) --}}
        @if(($entreeForm['mode_paiement'] ?? 'especes') === 'MobileMoney')
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Opérateur Mobile Money</label>
            <select  wire:model.live="entreeForm.sous_type_compte"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
              <option value="">— Sélectionner —</option>
              <option value="Mvola">Mvola</option>
              <option value="OrangeMoney">OrangeMoney</option>
              <option value="AirtelMoney">AirtelMoney</option>
            </select>
            @error('entreeForm.sous_type_compte') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
          </div>
        @elseif(($entreeForm['mode_paiement'] ?? 'especes') === 'Banque')
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Banque</label>
            <select  wire:model.live="entreeForm.sous_type_compte"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
              <option value="">— Sélectionner —</option>
              <option value="BNI">BNI</option>
              <option value="BFV">BFV</option>
              <option value="BOA">BOA</option>
              <option value="BMOI">BMOI</option>
              <option value="SBM">SBM</option>
            </select>
            @error('entreeForm.sous_type_compte') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
          </div>
        @endif

        {{-- Observation --}}
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Observation (optionnel)</label>
          <textarea wire:model.lazy="entreeForm.observation" rows="3"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                    placeholder="Remarques supplémentaires..."></textarea>
        </div>

        {{-- Alerte si solde insuffisant --}}
        @php
            $insuffisant = $this->insuffisantEntree;
        @endphp

        @if($insuffisant)
            <div class="mt-3 rounded-lg border border-red-200 bg-red-50 text-red-800 px-3 py-2 text-sm">
                💡 Solde insuffisant pour ce montant.
                Veuillez réduire le montant ou choisir un autre compte.
            </div>
        @endif

        {{-- Actions --}}
        <div class="flex justify-end space-x-3 pt-4">
          <button type="button" wire:click="closeNewEntreeModal"
                  class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
            Annuler
          </button>
          <button type="submit"
                  class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors inline-flex items-center"
                  wire:loading.attr="disabled" wire:target="creerEntree">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Créer l'Entrée
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endif
