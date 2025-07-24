{{-- resources/views/livewire/voyage/tabs/synthese.blade.php --}}

<div class="space-y-6">
    <h3 class="text-lg font-medium text-gray-900">Synthèse du voyage</h3>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Résumé chargements par propriétaire -->
        <div class="bg-gray-50 rounded-lg p-4">
            <h4 class="text-md font-medium text-gray-900 mb-3">Chargements par propriétaire</h4>
            @php
                $chargementsParProprietaire = $voyage->chargements->groupBy('proprietaire.name');
            @endphp
            @foreach($chargementsParProprietaire as $proprietaire => $chargements)
                <div class="flex justify-between py-2 border-b border-gray-200">
                    <span class="text-sm text-gray-600">{{ $proprietaire }}</span>
                    <span class="text-sm font-medium">{{ number_format($chargements->sum('poids_depart_kg'), 0) }} kg</span>
                </div>
            @endforeach
        </div>

        <!-- Résumé financier -->
        <div class="bg-gray-50 rounded-lg p-4">
            <h4 class="text-md font-medium text-gray-900 mb-3">Résumé financier</h4>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">CA Total ventes</span>
                    <span class="text-sm font-medium">{{ number_format($totalVentes, 0) }} MGA</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Total payé</span>
                    <span class="text-sm font-medium text-green-600">{{ number_format($totalPaiements, 0) }} MGA</span>
                </div>
                <div class="flex justify-between border-t pt-2">
                    <span class="text-sm text-gray-600">Reste à encaisser</span>
                    <span class="text-sm font-medium {{ $totalReste > 0 ? 'text-red-600' : 'text-gray-900' }}">{{ number_format($totalReste, 0) }} MGA</span>
                </div>
            </div>
        </div>
    </div>

    @if($voyage->observation)
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <h4 class="text-md font-medium text-gray-900 mb-2">Observations</h4>
            <p class="text-sm text-gray-700">{{ $voyage->observation }}</p>
        </div>
    @endif
</div>