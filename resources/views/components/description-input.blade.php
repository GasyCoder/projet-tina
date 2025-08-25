@props([
    'textModel',
    'amountModel',
    'onRemove' => null,
    'showRemove' => true,
])

<div class="flex items-center gap-2">
    <!-- Champ de description -->
    <input 
        type="text" 
        @php echo 'wire:model="' . $textModel . '"' @endphp
        class="flex-1 border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150"
        placeholder="Description"
    >

    <!-- Champ de montant avec préfixe Ar -->
    <div class="relative w-28 rounded-md shadow-sm">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <span class="text-gray-500">Ar</span>
        </div>
        <input 
            type="number" 
            step="0.01"
            @php echo 'wire:model="' . $amountModel . '"' @endphp
            class="block w-full pl-10 pr-2 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150"
            placeholder="0.00"
        >
    </div>

    @if($showRemove)
        <button 
            type="button" 
            wire:click="{{ $onRemove }}"
            class="p-2 text-red-500 hover:text-red-700 rounded-md hover:bg-red-50 transition duration-150"
            title="Supprimer"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
        </button>
    @endif
</div>
