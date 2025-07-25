{{-- Modal d'aperçu avec calculs automatiques - à ajouter dans voyage-show.blade.php --}}
@if($showPreviewModal)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-7xl sm:w-full">
                
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-gray-900">
                            🔍 Aperçu du déchargement
                        </h3>
                        <div class="text-right">
                            <span class="text-sm text-gray-500">Vérifiez les données avant validation</span>
                            <div class="text-xs text-blue-600 mt-1">
                                {{ $editingDechargement ? 'Modification' : 'Nouveau déchargement' }}
                            </div>
                        </div>
                    </div>

                    {{-- Comparaison Chargement vs Déchargement --}}
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                        
                        {{-- CHARGEMENT (DÉPART) --}}
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <h4 class="font-bold text-blue-900 mb-3 flex items-center">
                                📦 Chargement (Départ)
                                <span class="ml-2 text-sm font-normal">{{ $previewData['chargement']['reference'] ?? 'N/A' }}</span>
                            </h4>
                            
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-blue-700">Propriétaire:</span>
                                    <span class="font-medium">{{ $previewData['chargement']['proprietaire_nom'] ?? 'N/A' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-blue-700">Produit:</span>
                                    <span class="font-medium">{{ $previewData['chargement']['produit'] ?? 'N/A' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-blue-700">Sacs pleins:</span>
                                    <span class="font-medium">{{ $previewData['chargement']['sacs_pleins_depart'] ?? 0 }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-blue-700">Sacs demi:</span>
                                    <span class="font-medium">{{ $previewData['chargement']['sacs_demi_depart'] ?? 0 }}</span>
                                </div>
                                <div class="flex justify-between border-t pt-2">
                                    <span class="text-blue-700 font-medium">Poids total:</span>
                                    <span class="font-bold text-lg">{{ number_format($previewData['chargement']['poids_depart_kg'] ?? 0, 1) }} kg</span>
                                </div>
                            </div>
                        </div>

                        {{-- DÉCHARGEMENT (ARRIVÉE) --}}
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <h4 class="font-bold text-green-900 mb-3 flex items-center">
                                🚚 Déchargement (Arrivée)
                                <span class="ml-2 text-sm font-normal">{{ $previewData['dechargement']['reference'] ?? 'N/A' }}</span>
                            </h4>
                            
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-green-700">Type:</span>
                                    <span class="font-medium capitalize">{{ $previewData['dechargement']['type'] ?? 'N/A' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-green-700">Pointeur:</span>
                                    <span class="font-medium">{{ $previewData['dechargement']['pointeur_nom'] ?? 'N/A' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-green-700">Destination:</span>
                                    <span class="font-medium">{{ $previewData['dechargement']['lieu_livraison'] ?? 'N/A' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-green-700">Sacs pleins:</span>
                                    <span class="font-medium">{{ $previewData['dechargement']['sacs_pleins_arrivee'] ?? 0 }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-green-700">Sacs demi:</span>
                                    <span class="font-medium">{{ $previewData['dechargement']['sacs_demi_arrivee'] ?? 0 }}</span>
                                </div>
                                <div class="flex justify-between border-t pt-2">
                                    <span class="text-green-700 font-medium">Poids reçu:</span>
                                    <span class="font-bold text-lg">{{ number_format($previewData['dechargement']['poids_arrivee_kg'] ?: 0, 1) }} kg</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- CALCULS D'ÉCARTS AMÉLIORÉS --}}
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                        <h4 class="font-bold text-yellow-900 mb-3 flex items-center">
                            ⚖️ Écarts et Pertes/Gains
                            @php
                                $ecartPoids = $previewData['calculs']['ecart_poids_kg'] ?? 0;
                                $pourcentagePerte = $previewData['calculs']['pourcentage_perte'] ?? 0;
                            @endphp
                            <span class="ml-3 text-sm px-2 py-1 rounded-full
                                @if($pourcentagePerte > 10) bg-red-100 text-red-700
                                @elseif($pourcentagePerte > 5) bg-orange-100 text-orange-700
                                @elseif($pourcentagePerte > 0) bg-yellow-100 text-yellow-700
                                @else bg-green-100 text-green-700 @endif">
                                @if($pourcentagePerte > 10) 🚨 Perte importante
                                @elseif($pourcentagePerte > 5) ⚠️ Perte modérée  
                                @elseif($pourcentagePerte > 0) ℹ️ Perte normale
                                @else ✅ Aucune perte @endif
                            </span>
                        </h4>
                        
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                            <div class="text-center p-3 bg-white rounded-lg border">
                                <div class="text-yellow-700 font-medium">Écart Sacs Pleins</div>
                                <div class="font-bold text-2xl mt-1 {{ ($previewData['calculs']['ecart_sacs_pleins'] ?? 0) > 0 ? 'text-red-600' : 'text-green-600' }}">
                                    {{ ($previewData['calculs']['ecart_sacs_pleins'] ?? 0) > 0 ? '-' : '+' }}{{ abs($previewData['calculs']['ecart_sacs_pleins'] ?? 0) }}
                                </div>
                                <div class="text-xs text-gray-600 mt-1">
                                    {{ $previewData['chargement']['sacs_pleins_depart'] ?: 0 }} → {{ $previewData['dechargement']['sacs_pleins_arrivee'] ?: 0 }}
                                </div>
                            </div>
                            
                            <div class="text-center p-3 bg-white rounded-lg border">
                                <div class="text-yellow-700 font-medium">Écart Sacs Demi</div>
                                <div class="font-bold text-2xl mt-1 {{ ($previewData['calculs']['ecart_sacs_demi'] ?? 0) > 0 ? 'text-red-600' : 'text-green-600' }}">
                                    {{ ($previewData['calculs']['ecart_sacs_demi'] ?? 0) > 0 ? '-' : '+' }}{{ abs($previewData['calculs']['ecart_sacs_demi'] ?: 0) }}
                                </div>
                                <div class="text-xs text-gray-600 mt-1">
                                    {{ $previewData['chargement']['sacs_demi_depart'] ?: 0 }} → {{ $previewData['dechargement']['sacs_demi_arrivee'] ?: 0 }}
                                </div>
                            </div>
                            
                            <div class="text-center p-3 bg-white rounded-lg border">
                                <div class="text-yellow-700 font-medium">Écart Poids</div>
                                <div class="font-bold text-2xl mt-1 {{ $ecartPoids > 0 ? 'text-red-600' : 'text-green-600' }}">
                                    {{ $ecartPoids > 0 ? '-' : '+' }}{{ number_format(abs($ecartPoids ?: 0), 1) }} kg
                                </div>
                                <div class="text-xs text-gray-600 mt-1">
                                    {{ number_format($previewData['chargement']['poids_depart_kg'] ?: 0, 1) }} → {{ number_format($previewData['dechargement']['poids_arrivee_kg'] ?: 0, 1) }} kg
                                </div>
                            </div>
                            
                            <div class="text-center p-3 bg-white rounded-lg border">
                                <div class="text-yellow-700 font-medium">% Perte/Gain</div>
                                <div class="font-bold text-2xl mt-1 {{ $pourcentagePerte > 0 ? 'text-red-600' : 'text-green-600' }}">
                                    {{ $pourcentagePerte > 0 ? '-' : '+' }}{{ number_format(abs($pourcentagePerte ?: 0), 1) }}%
                                </div>
                                <div class="text-xs text-gray-600 mt-1">
                                    @if($pourcentagePerte > 0) Perte @else Gain @endif
                                </div>
                            </div>
                        </div>

                        {{-- Alerte si perte importante --}}
                        @if($pourcentagePerte > 10)
                            <div class="mt-4 bg-red-50 border border-red-200 rounded-lg p-3">
                                <div class="flex items-center">
                                    <div class="text-red-600 mr-2">🚨</div>
                                    <div class="text-red-800 font-medium">Attention: Perte importante de {{ number_format($pourcentagePerte ?: 0, 1) }}%</div>
                                </div>
                                <div class="text-red-700 text-sm mt-1">
                                    Vérifiez les quantités saisies ou documentez la cause de cette perte.
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- INFORMATIONS FINANCIÈRES AMÉLIORÉES (si vente) --}}
                    @if($previewData['dechargement']['type'] === 'vente')
                        <div class="bg-purple-50 border border-purple-200 rounded-lg p-4 mb-4">
                            <h4 class="font-bold text-purple-900 mb-3 flex items-center">
                                💰 Informations Financières
                                @php
                                    $montantTotal = $previewData['dechargement']['montant_total_mga'] ?? 0;
                                    $paiementRecu = $previewData['dechargement']['paiement_mga'] ?? 0;
                                    $reste = $previewData['dechargement']['reste_mga'] ?? 0;
                                @endphp
                                <span class="ml-3 text-sm px-2 py-1 rounded-full
                                    @if($reste > 0) bg-yellow-100 text-yellow-700
                                    @elseif($reste < 0) bg-red-100 text-red-700
                                    @else bg-green-100 text-green-700 @endif">
                                    @if($reste > 0) 💳 Paiement partiel
                                    @elseif($reste < 0) ⚠️ Trop-perçu
                                    @else ✅ Payé intégralement @endif
                                </span>
                            </h4>
                            
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm mb-4">
                                <div class="text-center p-3 bg-white rounded-lg border">
                                    <div class="text-purple-700 font-medium">Prix Unitaire</div>
                                    <div class="font-bold text-xl mt-1 text-purple-600">
                                        {{ number_format($previewData['dechargement']['prix_unitaire_mga'] ?: 0, 0) }}
                                    </div>
                                    <div class="text-xs text-gray-600">MGA/kg</div>
                                </div>
                                
                                <div class="text-center p-3 bg-white rounded-lg border">
                                    <div class="text-purple-700 font-medium">Montant Total</div>
                                    <div class="font-bold text-xl mt-1 text-green-600">
                                        {{ number_format($montantTotal ?: 0, 0) }}
                                    </div>
                                    <div class="text-xs text-gray-600">MGA</div>
                                </div>
                                
                                <div class="text-center p-3 bg-white rounded-lg border">
                                    <div class="text-purple-700 font-medium">Paiement Reçu</div>
                                    <div class="font-bold text-xl mt-1 text-blue-600">
                                        {{ number_format($paiementRecu ?: 0, 0) }}
                                    </div>
                                    <div class="text-xs text-gray-600">MGA</div>
                                </div>
                                
                                <div class="text-center p-3 bg-white rounded-lg border">
                                    <div class="text-purple-700 font-medium">Reste à Encaisser</div>
                                    <div class="font-bold text-xl mt-1 {{ $reste > 0 ? 'text-red-600' : ($reste < 0 ? 'text-red-600' : 'text-green-600') }}">
                                        {{ number_format($reste ?: 0, 0) }}
                                    </div>
                                    <div class="text-xs text-gray-600">MGA</div>
                                </div>
                            </div>

                            {{-- Détail du calcul --}}
                            <div class="bg-white border rounded-lg p-3">
                                <div class="text-sm text-gray-700 mb-2 font-medium">📊 Détail du calcul:</div>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                    <div class="text-center">
                                        <div class="text-gray-600">Formule</div>
                                        <div class="font-mono text-xs bg-gray-100 p-2 rounded mt-1">
                                            Prix × Poids = Total
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-gray-600">Calcul</div>
                                        <div class="font-mono text-xs bg-gray-100 p-2 rounded mt-1">
                                            {{ number_format($previewData['dechargement']['prix_unitaire_mga'] ?: 0, 0) }} × {{ number_format($previewData['dechargement']['poids_arrivee_kg'] ?: 0, 1) }} = {{ number_format($montantTotal ?: 0, 0) }}
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-gray-600">Reste</div>
                                        <div class="font-mono text-xs bg-gray-100 p-2 rounded mt-1">
                                            {{ number_format($montantTotal ?: 0, 0) }} - {{ number_format($paiementRecu ?: 0, 0) }} = {{ number_format($reste ?: 0, 0) }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Alertes financières --}}
                            @if($reste < 0)
                                <div class="mt-3 bg-red-50 border border-red-200 rounded-lg p-3">
                                    <div class="flex items-center">
                                        <div class="text-red-600 mr-2">⚠️</div>
                                        <div class="text-red-800 font-medium">Attention: Trop-perçu de {{ number_format(abs($reste ?: 0), 0) }} MGA</div>
                                    </div>
                                    <div class="text-red-700 text-sm mt-1">
                                        Le paiement reçu dépasse le montant dû. Vérifiez les montants ou documentez ce surplus.
                                    </div>
                                </div>
                            @elseif($reste > 0)
                                <div class="mt-3 bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                                    <div class="flex items-center">
                                        <div class="text-yellow-600 mr-2">💳</div>
                                        <div class="text-yellow-800 font-medium">Paiement partiel: {{ number_format($reste ?: 0, 0) }} MGA restant</div>
                                    </div>
                                    <div class="text-yellow-700 text-sm mt-1">
                                        Paiement de {{ $montantTotal > 0 ? number_format((($paiementRecu ?: 0) / ($montantTotal ?: 1)) * 100, 1) : 0 }}% effectué.
                                    </div>
                                </div>
                            @else
                                <div class="mt-3 bg-green-50 border border-green-200 rounded-lg p-3">
                                    <div class="flex items-center">
                                        <div class="text-green-600 mr-2">✅</div>
                                        <div class="text-green-800 font-medium">Paiement intégral effectué</div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                    {{-- STATUT COMMERCIAL ET RÉSUMÉ --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-gray-700 font-medium">Statut commercial:</span>
                                <span class="px-3 py-1 rounded-full text-sm font-medium
                                    @if($previewData['dechargement']['statut_commercial'] === 'en_attente') bg-yellow-100 text-yellow-800
                                    @elseif($previewData['dechargement']['statut_commercial'] === 'vendu') bg-green-100 text-green-800
                                    @elseif($previewData['dechargement']['statut_commercial'] === 'retourne') bg-red-100 text-red-800
                                    @else bg-blue-100 text-blue-800 @endif">
                                    {{ ucfirst(str_replace('_', ' ', $previewData['dechargement']['statut_commercial'] ?? 'en_attente')) }}
                                </span>
                            </div>
                            
                            @if($previewData['dechargement']['lieu_livraison'] && $previewData['dechargement']['lieu_livraison'] !== 'Non renseigné')
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-700 font-medium">Destination:</span>
                                    <span class="text-gray-900">{{ $previewData['dechargement']['lieu_livraison'] }}</span>
                                </div>
                            @endif
                        </div>

                        {{-- Résumé global --}}
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <h5 class="font-medium text-blue-900 mb-2">📋 Résumé de l'opération</h5>
                            <div class="space-y-1 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-blue-700">Type:</span>
                                    <span class="font-medium capitalize">{{ $previewData['dechargement']['type'] ?? 'N/A' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-blue-700">Référence:</span>
                                    <span class="font-medium">{{ $previewData['dechargement']['reference'] ?? 'N/A' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-blue-700">Pointeur:</span>
                                    <span class="font-medium">{{ $previewData['dechargement']['pointeur_nom'] ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Observation si présente --}}
                    @if($dechargement_observation)
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-3 mb-4">
                            <h5 class="font-medium text-gray-900 mb-2">📝 Observation</h5>
                            <p class="text-sm text-gray-700">{{ $dechargement_observation }}</p>
                        </div>
                    @endif
                </div>

                {{-- BOUTONS D'ACTION AMÉLIORÉS --}}
                <div class="bg-gray-50 px-4 py-3 sm:px-6">
                    {{-- Résumé rapide en haut des boutons --}}
                    <div class="flex items-center justify-between mb-4 text-sm">
                        <div class="flex items-center space-x-4">
                            <span class="text-gray-600">
                                📦 {{ number_format($previewData['chargement']['poids_depart_kg'] ?: 0, 1) }} kg → 
                                🚚 {{ number_format($previewData['dechargement']['poids_arrivee_kg'] ?: 0, 1) }} kg
                            </span>
                            @if($previewData['dechargement']['type'] === 'vente')
                                <span class="text-gray-600">
                                    💰 {{ number_format($previewData['dechargement']['montant_total_mga'] ?: 0, 0) }} MGA
                                </span>
                            @endif
                        </div>
                        
                        @php
                            $pourcentagePerte = $previewData['calculs']['pourcentage_perte'] ?? 0;
                        @endphp
                        <span class="px-2 py-1 rounded text-xs font-medium
                            @if($pourcentagePerte > 10) bg-red-100 text-red-700
                            @elseif($pourcentagePerte > 5) bg-orange-100 text-orange-700
                            @elseif($pourcentagePerte > 0) bg-yellow-100 text-yellow-700
                            @else bg-green-100 text-green-700 @endif">
                            {{ $pourcentagePerte > 0 ? 'Perte' : 'Gain' }}: {{ number_format(abs($pourcentagePerte ?: 0), 1) }}%
                        </span>
                    </div>

                    <div class="sm:flex sm:flex-row-reverse space-y-2 sm:space-y-0">
                        <button wire:click="confirmSaveDechargement" 
                                class="w-full sm:w-auto inline-flex justify-center items-center rounded-md border border-transparent shadow-sm px-6 py-3 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 sm:ml-3 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ $editingDechargement ? 'Confirmer les modifications' : 'Confirmer et Enregistrer' }}
                        </button>
                        
                        <button wire:click="closePreviewModal" 
                                class="w-full sm:w-auto inline-flex justify-center items-center rounded-md border border-gray-300 shadow-sm px-4 py-3 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                            Retour pour modifier
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif