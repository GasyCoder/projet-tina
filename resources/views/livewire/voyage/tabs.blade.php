<div class="bg-white dark:bg-gray-800 rounded-lg shadow dark:shadow-gray-900/20">
    <div class="border-b border-gray-200 dark:border-gray-700">
        <nav class="-mb-px flex space-x-8 px-6">
            <a 
                href="{{ route('voyages.show', $voyage->id) }}?tab=chargements"
                wire:click.prevent="setActiveTab('chargements')"
                class="py-4 px-1 border-b-2 font-medium text-sm 
                    {{ $activeTab === 'chargements' 
                        ? 'border-blue-500 dark:border-blue-400 text-blue-600 dark:text-blue-400' 
                        : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-500' }}">
                📦 Chargements ({{ $voyage->chargements->count() }})
            </a>
            <a 
                href="{{ route('voyages.show', $voyage->id) }}?tab=dechargements"
                wire:click.prevent="setActiveTab('dechargements')"
                class="py-4 px-1 border-b-2 font-medium text-sm 
                    {{ $activeTab === 'dechargements' 
                        ? 'border-blue-500 dark:border-blue-400 text-blue-600 dark:text-blue-400' 
                        : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-500' }}">
                🏪 Déchargements ({{ $voyage->dechargements->count() }})
            </a>
            <a 
                href="{{ route('voyages.show', $voyage->id) }}?tab=synthese"
                wire:click.prevent="setActiveTab('synthese')"
                class="py-4 px-1 border-b-2 font-medium text-sm 
                    {{ $activeTab === 'synthese' 
                        ? 'border-blue-500 dark:border-blue-400 text-blue-600 dark:text-blue-400' 
                        : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-500' }}">
                📊 Synthèse
            </a>
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