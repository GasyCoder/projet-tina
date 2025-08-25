{{-- resources/views/livewire/voyage/modals/dechargement-preview-modal.blade.php --}}
@if($showPreviewModal)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-75 transition-opacity"></div>
            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-5xl sm:w-full">
                
                <!-- En-tête -->
                <div class="bg-blue-50 dark:bg-blue-900 px-6 py-4 border-b border-blue-200 dark:border-blue-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                        🔍 Aperçu avant confirmation du déchargement
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-blue-200 mt-1">
                        Vérifiez toutes les informations avant de sauvegarder le déchargement
                    </p>
                </div>

                <div class="bg-white dark:bg-gray-800 px-6 py-6">
                    <div class="space-y-6">
                        
                        <!-- ✅ INFORMATIONS DU VOYAGE -->
                        <div class="bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-700 rounded-lg p-4">
                            <h4 class="font-medium text-blue-900 dark:text-blue-100 mb-3">🚛 Voyage {{ $previewData['voyage']['reference'] ?? 'N/A' }}</h4>
                            <div class="grid grid-cols-3 gap-4 text-sm">
                                <div>
                                    <span class="text-blue-700 dark:text-blue-300">Date du voyage:</span> 
                                    <span class="font-medium dark:text-white">{{ $previewData['voyage']['date'] ?? 'N/A' }}</span>
                                </div>
                                <div>
                                    <span class="text-blue-700 dark:text-blue-300">Véhicule:</span> 
                                    <span class="font-medium dark:text-white">{{ $previewData['voyage']['vehicule'] ?? 'N/A' }}</span>
                                </div>
                                <div>
                                    <span class="text-blue-700 dark:text-blue-300">Référence voyage:</span> 
                                    <span class="font-medium dark:text-white">{{ $previewData['voyage']['reference'] ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- ✅ INFORMATIONS DU CHARGEMENT SOURCE -->
                        <div class="bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-700 rounded-lg p-4">
                            <h4 class="font-medium text-green-900 dark:text-green-100 mb-3">📦 Chargement source {{ $previewData['chargement']['reference'] ?? 'N/A' }}</h4>
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="text-green-700 dark:text-green-300">Propriétaire:</span> 
                                    <span class="font-medium dark:text-white">{{ $previewData['chargement']['proprietaire_nom'] ?? 'N/A' }}</span>
                                </div>
                                <div>
                                    <span class="text-green-700 dark:text-green-300">Produit:</span> 
                                    <span class="font-medium dark:text-white">{{ $previewData['chargement']['produit'] ?? 'N/A' }}</span>
                                </div>
                                <div>
                                    <span class="text-green-700 dark:text-green-300">Lieu de départ:</span> 
                                    <span class="font-medium dark:text-white">{{ $previewData['chargement']['depart'] ?? 'N/A' }}</span>
                                </div>
                                <div>
                                    <span class="text-green-700 dark:text-green-300">Poids chargé:</span> 
                                    <span class="font-medium dark:text-white">{{ number_format($previewData['chargement']['poids_depart_kg'] ?? 0, 1) }} kg</span>
                                </div>
                            </div>
                            
                            <!-- Détails des sacs chargés -->
                            <div class="mt-3 p-3 bg-white dark:bg-gray-700 rounded border border-green-100 dark:border-green-800">
                                <div class="text-xs text-green-600 dark:text-green-300 mb-1">Détails du chargement initial:</div>
                                <div class="flex items-center space-x-4 text-sm dark:text-gray-200">
                                    <span>
                                        <strong>{{ $previewData['chargement']['sacs_pleins_depart'] ?? 0 }}</strong> sacs pleins
                                    </span>
                                    @if(($previewData['chargement']['sacs_demi_depart'] ?? 0) > 0)
                                        <span>
                                            + <strong>{{ $previewData['chargement']['sacs_demi_depart'] }}</strong> demi-sacs
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- ✅ INFORMATIONS DU DÉCHARGEMENT -->
                        <div class="bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                            <h4 class="font-medium text-gray-900 dark:text-white mb-3">📋 Déchargement {{ $previewData['dechargement']['reference'] ?? 'N/A' }}</h4>
                            
                            <!-- Informations générales -->
                            <div class="grid grid-cols-2 gap-4 text-sm mb-4">
                                <div>
                                    <span class="text-gray-700 dark:text-gray-300">Type d'opération:</span> 
                                    <span class="font-medium px-2 py-1 text-xs rounded-full
                                        @if($previewData['dechargement']['type'] === 'vente') bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200
                                        @elseif($previewData['dechargement']['type'] === 'retour') bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200
                                        @elseif($previewData['dechargement']['type'] === 'depot') bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200
                                        @else bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200 @endif">
                                        {{ ucfirst($previewData['dechargement']['type'] ?? 'N/A') }}
                                    </span>
                                </div>
                                <div>
                                    <span class="text-gray-700 dark:text-gray-300">Statut commercial:</span> 
                                    <span class="font-medium dark:text-white">{{ ucfirst(str_replace('_', ' ', $previewData['dechargement']['statut_commercial'] ?? 'N/A')) }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-700 dark:text-gray-300">Interlocuteur/Client:</span> 
                                    <span class="font-medium dark:text-white">{{ $previewData['dechargement']['interlocuteur_nom'] ?? 'Non renseigné' }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-700 dark:text-gray-300">Pointeur/Superviseur:</span> 
                                    <span class="font-medium dark:text-white">{{ $previewData['dechargement']['pointeur_nom'] ?? 'Non renseigné' }}</span>
                                </div>
                                <div class="col-span-2">
                                    <span class="text-gray-700 dark:text-gray-300">Lieu de livraison:</span> 
                                    <span class="font-medium dark:text-white">{{ $previewData['dechargement']['lieu_livraison'] ?? 'Non renseigné' }}</span>
                                </div>
                            </div>

                            <!-- Quantités déchargées -->
                            <div class="p-3 bg-white dark:bg-gray-700 rounded border border-gray-100 dark:border-gray-600">
                                <div class="text-xs text-gray-600 dark:text-gray-300 mb-2">Quantités reçues à destination:</div>
                                <div class="grid grid-cols-3 gap-4 text-sm">
                                    <div>
                                        <div class="text-gray-500 dark:text-gray-400">Sacs pleins reçus</div>
                                        <div class="font-bold text-lg dark:text-white">{{ $previewData['dechargement']['sacs_pleins_arrivee'] ?? 0 }}</div>
                                    </div>
                                    <div>
                                        <div class="text-gray-500 dark:text-gray-400">Sacs demi reçus</div>
                                        <div class="font-bold text-lg dark:text-white">{{ $previewData['dechargement']['sacs_demi_arrivee'] ?? 0 }}</div>
                                    </div>
                                    <div>
                                        <div class="text-gray-500 dark:text-gray-400">Poids total reçu</div>
                                        <div class="font-bold text-lg text-blue-600 dark:text-blue-400">{{ number_format($previewData['dechargement']['poids_arrivee_kg'] ?? 0, 1) }} kg</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ✅ ANALYSE DES ÉCARTS -->
                        <div class="bg-yellow-50 dark:bg-yellow-900/30 border border-yellow-200 dark:border-yellow-700 rounded-lg p-4">
                            <h4 class="font-medium text-yellow-900 dark:text-yellow-100 mb-3">📊 Analyse des écarts de transport</h4>
                            
                            <div class="grid grid-cols-2 gap-4 text-sm mb-3">
                                <div>
                                    <span class="text-yellow-700 dark:text-yellow-300">Écart sacs pleins:</span>
                                    <span class="font-medium {{ ($previewData['calculs']['ecart_sacs_pleins'] ?? 0) > 0 ? 'text-orange-600 dark:text-orange-400' : (($previewData['calculs']['ecart_sacs_pleins'] ?? 0) < 0 ? 'text-green-600 dark:text-green-400' : 'text-blue-600 dark:text-blue-400') }}">
                                        {{ ($previewData['calculs']['ecart_sacs_pleins'] ?? 0) > 0 ? '-' : (($previewData['calculs']['ecart_sacs_pleins'] ?? 0) < 0 ? '+' : '') }}{{ abs($previewData['calculs']['ecart_sacs_pleins'] ?? 0) }} sacs
                                    </span>
                                </div>
                                <div>
                                    <span class="text-yellow-700 dark:text-yellow-300">Écart sacs demi:</span>
                                    <span class="font-medium {{ ($previewData['calculs']['ecart_sacs_demi'] ?? 0) > 0 ? 'text-orange-600 dark:text-orange-400' : (($previewData['calculs']['ecart_sacs_demi'] ?? 0) < 0 ? 'text-green-600 dark:text-green-400' : 'text-blue-600 dark:text-blue-400') }}">
                                        {{ ($previewData['calculs']['ecart_sacs_demi'] ?? 0) > 0 ? '-' : (($previewData['calculs']['ecart_sacs_demi'] ?? 0) < 0 ? '+' : '') }}{{ abs($previewData['calculs']['ecart_sacs_demi'] ?? 0) }} sacs
                                    </span>
                                </div>
                                <div>
                                    <span class="text-yellow-700 dark:text-yellow-300">Écart poids:</span>
                                    <span class="font-medium {{ ($previewData['calculs']['ecart_poids_kg'] ?? 0) > 0 ? 'text-orange-600 dark:text-orange-400' : (($previewData['calculs']['ecart_poids_kg'] ?? 0) < 0 ? 'text-green-600 dark:text-green-400' : 'text-blue-600 dark:text-blue-400') }}">
                                        {{ ($previewData['calculs']['ecart_poids_kg'] ?? 0) > 0 ? '-' : (($previewData['calculs']['ecart_poids_kg'] ?? 0) < 0 ? '+' : '') }}{{ number_format(abs($previewData['calculs']['ecart_poids_kg'] ?? 0), 1) }} kg
                                    </span>
                                </div>
                                <div>
                                    <span class="text-yellow-700 dark:text-yellow-300">Pourcentage d'écart:</span>
                                    <span class="font-medium {{ abs($previewData['calculs']['pourcentage_ecart'] ?? 0) > 5 ? 'text-orange-600 dark:text-orange-400' : 'text-blue-600 dark:text-blue-400' }}">
                                        {{ number_format(abs($previewData['calculs']['pourcentage_ecart'] ?? 0), 1) }}%
                                    </span>
                                </div>
                            </div>
                            
                            @php
                                $pourcentage_ecart = abs($previewData['calculs']['pourcentage_ecart'] ?? 0);
                            @endphp
                            
                            <!-- Interprétation intelligente des écarts -->
                            <div class="p-3 rounded text-sm 
                                {{ $pourcentage_ecart > 10 ? 'bg-orange-100 dark:bg-orange-900/30 border border-orange-200 dark:border-orange-700 text-orange-800 dark:text-orange-200' : 
                                   ($pourcentage_ecart > 5 ? 'bg-yellow-100 dark:bg-yellow-900/30 border border-yellow-200 dark:border-yellow-700 text-yellow-800 dark:text-yellow-200' : 
                                   'bg-green-100 dark:bg-green-900/30 border border-green-200 dark:border-green-700 text-green-800 dark:text-green-200') }}">
                                
                                @if($pourcentage_ecart > 10)
                                    <div class="flex items-start space-x-2">
                                        <span class="text-lg">⚠️</span>
                                        <div>
                                            <div class="font-medium">Écart significatif ({{ number_format($pourcentage_ecart, 1) }}%)</div>
                                            <div class="text-xs mt-1">Cet écart important peut être dû à : qualité du produit, conditions d'humidité, tassement durant le transport, différences de pesée, ou conditions de stockage. Il est recommandé de vérifier les conditions de transport et de documenter les observations.</div>
                                        </div>
                                    </div>
                                @elseif($pourcentage_ecart > 5)
                                    <div class="flex items-start space-x-2">
                                        <span class="text-lg">ℹ️</span>
                                        <div>
                                            <div class="font-medium">Écart modéré ({{ number_format($pourcentage_ecart, 1) }}%)</div>
                                            <div class="text-xs mt-1">Variation normale pouvant être due aux conditions de transport, à la qualité du produit, à l'humidité, ou aux différences de méthodes de pesée. Cet écart reste dans une fourchette acceptable.</div>
                                        </div>
                                    </div>
                                @else
                                    <div class="flex items-start space-x-2">
                                        <span class="text-lg">✅</span>
                                        <div>
                                            <div class="font-medium">Écart faible ({{ number_format($pourcentage_ecart, 1) }}%)</div>
                                            <div class="text-xs mt-1">Variation minime et tout à fait normale dans les opérations de transport. Cet écart peut s'expliquer par les tolérances de pesée et les conditions normales de manutention.</div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="mt-3 p-2 bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-700 rounded text-xs text-blue-800 dark:text-blue-200">
                                <strong>Important :</strong> Ces écarts ne constituent pas automatiquement des "pertes". Ils reflètent les variations normales liées au transport, à la qualité, aux conditions météorologiques, et aux méthodes de pesée.
                            </div>
                        </div>

                        <!-- ✅ INFORMATIONS FINANCIÈRES (si vente) -->
                        @if(($previewData['dechargement']['type'] ?? '') === 'vente')
                            <div class="bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-700 rounded-lg p-4">
                                <h4 class="font-medium text-green-900 dark:text-green-100 mb-3">💰 Informations financières</h4>
                                
                                @php
                                    $unite = $previewData['dechargement']['product_unite'] ?? 'kg';
                                    $quantite = $previewData['dechargement']['quantite_vendue'] ?? 0;
                                    $unite_display = $unite === 'kg' ? 'kg' : ($unite === 'sacs' ? 'sacs' : $unite);
                                    $unite_sing = $unite === 'kg' ? 'kg' : ($unite === 'sacs' ? 'sac' : $unite);
                                @endphp
                                
                                <!-- Information sur l'unité de vente -->
                                <div class="mb-4 p-3 bg-white dark:bg-gray-700 border border-green-100 dark:border-green-800 rounded">
                                    <div class="text-xs text-green-600 dark:text-green-300 mb-1">Produit et unité de facturation:</div>
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="font-medium dark:text-white">{{ $previewData['chargement']['produit'] ?? 'N/A' }}</span>
                                        <span class="px-2 py-1 bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 rounded text-xs font-bold uppercase">{{ $unite }}</span>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-4 gap-4 text-center mb-4">
                                    <div>
                                        <div class="text-green-700 dark:text-green-300 text-sm">Quantité facturée</div>
                                        <div class="font-bold text-lg dark:text-white">{{ number_format($quantite, $unite === 'tonnes' ? 3 : 1) }}</div>
                                        <div class="text-xs text-green-600 dark:text-green-400">{{ $unite_display }}</div>
                                    </div>
                                    <div>
                                        <div class="text-green-700 dark:text-green-300 text-sm">Prix unitaire</div>
                                        <div class="font-bold text-lg dark:text-white">{{ number_format($previewData['dechargement']['prix_unitaire_mga'] ?? 0, 0) }} MGA</div>
                                        <div class="text-xs text-green-600 dark:text-green-400">par {{ $unite_sing }}</div>
                                    </div>
                                    <div>
                                        <div class="text-green-700 dark:text-green-300 text-sm">Montant total</div>
                                        <div class="font-bold text-xl text-blue-600 dark:text-blue-400">{{ number_format($previewData['dechargement']['montant_total_mga'] ?? 0, 0) }} MGA</div>
                                        <div class="text-xs text-green-600 dark:text-green-400">à encaisser</div>
                                    </div>
                                    <div>
                                        <div class="text-green-700 dark:text-green-300 text-sm">Paiement reçu</div>
                                        <div class="font-bold text-lg text-green-600 dark:text-green-400">{{ number_format($previewData['dechargement']['paiement_mga'] ?? 0, 0) }} MGA</div>
                                        <div class="text-xs text-green-600 dark:text-green-400">encaissé</div>
                                    </div>
                                </div>
                                
                                @if($quantite > 0 && ($previewData['dechargement']['prix_unitaire_mga'] ?? 0) > 0)
                                    <div class="p-3 bg-white dark:bg-gray-700 border border-green-100 dark:border-green-800 rounded text-center text-sm mb-4 dark:text-gray-200">
                                        <strong>Détail du calcul :</strong>
                                        {{ number_format($previewData['dechargement']['prix_unitaire_mga'], 0) }} MGA/{{ $unite_sing }}
                                        ×
                                        {{ number_format($quantite, $unite === 'tonnes' ? 3 : 1) }} {{ $unite_display }}
                                        =
                                        {{ number_format($previewData['dechargement']['montant_total_mga'] ?? 0, 0) }} MGA
                                    </div>
                                @endif
                                
                                @php
                                    $reste = $previewData['dechargement']['reste_mga'] ?? 0;
                                @endphp
                                @if($reste != 0)
                                    <div class="p-3 rounded border text-center
                                        @if($reste > 0) bg-yellow-100 dark:bg-yellow-900/30 border-yellow-300 dark:border-yellow-700
                                        @else bg-red-100 dark:bg-red-900/30 border-red-300 dark:border-red-700 @endif">
                                        <div class="text-sm text-gray-600 dark:text-gray-300">Reste à encaisser</div>
                                        <div class="font-bold text-xl 
                                            @if($reste > 0) text-yellow-800 dark:text-yellow-200
                                            @else text-red-800 dark:text-red-200 @endif">
                                            {{ number_format(abs($reste), 0) }} MGA
                                            @if($reste < 0) <span class="text-sm">(Trop-perçu - vérifiez les montants)</span> @endif
                                        </div>
                                        @if($reste > 0)
                                            <div class="text-xs text-yellow-700 dark:text-yellow-300 mt-1">⚠️ Paiement partiel - créance client</div>
                                        @endif
                                    </div>
                                @else
                                    <div class="p-3 bg-green-100 dark:bg-green-900/30 border border-green-300 dark:border-green-700 rounded text-center">
                                        <div class="text-sm text-green-600 dark:text-green-300">Statut du paiement</div>
                                        <div class="font-bold text-xl text-green-800 dark:text-green-200">✅ Transaction soldée</div>
                                        <div class="text-xs text-green-700 dark:text-green-300 mt-1">Paiement intégralement reçu</div>
                                    </div>
                                @endif
                            </div>
                        @endif

                    </div>
                </div>

                <!-- Boutons de confirmation -->
                <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 sm:flex sm:flex-row-reverse border-t border-gray-200 dark:border-gray-600">
                    <button type="button" wire:click="confirmSaveDechargement" 
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-6 py-3 bg-green-600 hover:bg-green-700 text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                        ✅ Confirmer et sauvegarder le déchargement
                    </button>
                    <button type="button" wire:click="closePreviewModal" 
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-600 text-base font-medium text-gray-700 dark:text-white hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        ← Retour aux détails pour modification
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif