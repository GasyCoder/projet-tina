
{{-- resources/views/livewire/stocks/stock-index.blade.php --}}
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                
                {{-- En-tête --}}
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">Gestion de Stock</h2>
                    <p class="text-gray-600">Gérez vos ventes, retours, dépôts et transferts</p>
                </div>

                {{-- Navigation par onglets --}}
                <div class="border-b border-gray-200 mb-6">
                    <nav class="-mb-px flex space-x-8">
                        <button wire:click="$set('activeTab', 'ventes')" 
                                class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm flex items-center
                                {{ $activeTab === 'ventes' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                            Ventes
                        </button>
                        
                        <button wire:click="$set('activeTab', 'retours')" 
                                class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm flex items-center
                                {{ $activeTab === 'retours' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                            </svg>
                            Retours
                        </button>
                        
                        <button wire:click="$set('activeTab', 'depot')" 
                                class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm flex items-center
                                {{ $activeTab === 'depot' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h1.586a1 1 0 01.707.293l1.414 1.414a1 1 0 00.707.293H19a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                            </svg>
                            Dépôt
                        </button>
                        
                        <button wire:click="$set('activeTab', 'transferts')" 
                                class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm flex items-center
                                {{ $activeTab === 'transferts' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                            </svg>
                            Transferts
                        </button>
                    </nav>
                </div>

                {{-- Contenu des onglets --}}
                <div>
                    @if($activeTab === 'ventes')
                        @livewire('stocks.vente')
                    @elseif($activeTab === 'retours')
                        @livewire('stocks.retour')
                    @elseif($activeTab === 'depot')
                        @livewire('stocks.depot')
                    @elseif($activeTab === 'transferts')
                        @livewire('stocks.transfert')
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>