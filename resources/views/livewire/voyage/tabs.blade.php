<div class="bg-white rounded-lg shadow">
    <div class="border-b border-gray-200">
        <nav class="-mb-px flex space-x-8 px-6">
            <button 
                wire:click="setActiveTab('chargements')"
                class="py-4 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'chargements' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                📦 Chargements ({{ $voyage->chargements->count() }})
            </button>
            <button 
                wire:click="setActiveTab('dechargements')"
                class="py-4 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'dechargements' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                🏪 Déchargements ({{ $voyage->dechargements->count() }})
            </button>
            <button 
                wire:click="setActiveTab('synthese')"
                class="py-4 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'synthese' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                📊 Synthèse
            </button>
        </nav>
    </div>

    <!-- Contenu des tabs avec wire:key -->
    <div class="p-6">
        @if($activeTab === 'chargements')
            <div wire:key="tab-chargements-{{ $voyage->id }}">
                @include('livewire.voyage.tabs.chargements', ['voyage' => $voyage])
            </div>
        @elseif($activeTab === 'dechargements')
            <div wire:key="tab-dechargements-{{ $voyage->id }}">
                @include('livewire.voyage.tabs.dechargements', ['voyage' => $voyage])
            </div>
        @else
            <div wire:key="tab-synthese-{{ $voyage->id }}">
                @include('livewire.voyage.tabs.synthese', [
                    'voyage' => $voyage,
                    'totalVentes' => $totalVentes,
                    'totalPaiements' => $totalPaiements,
                    'totalReste' => $totalReste
                ])
            </div>
        @endif
    </div>
</div>
