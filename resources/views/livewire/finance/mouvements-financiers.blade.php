<div class="min-h-screen bg-gradient-to-br from-green-50 to-emerald-100 pt-20 pb-6 px-4 md:px-6">
    <div class="space-y-6 max-w-7xl mx-auto">
        
        <!-- Header avec navigation -->
        <div class="bg-white shadow-lg rounded-xl p-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <h1 class="text-3xl font-bold text-gray-900">💰 Mouvements Financiers</h1>
                    <div class="flex items-center space-x-2">
                        @if($resumeJour)
                            <span class="px-3 py-1 text-sm rounded-full {{ $resumeJour->ecart >= 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $resumeJour->ecart >= 0 ? '📈' : '📉' }} {{ number_format($resumeJour->ecart, 0, ',', ' ') }} MGA
                            </span>
                        @endif
                        <div class="text-xs text-gray-500">
                            Comptes: {{ $comptes->count() }}
                        </div>
                    </div>
                </div>
                
                <!-- Navigation des modes -->
                <div class="flex items-center space-x-2">
                    <button wire:click="$set('modeAffichage', 'saisie')" 
                            class="px-4 py-2 text-sm font-medium rounded-lg transition-colors
                                {{ $modeAffichage === 'saisie' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        📝 Saisie
                    </button>
                    <button wire:click="$set('modeAffichage', 'tableau')" 
                            class="px-4 py-2 text-sm font-medium rounded-lg transition-colors
                                {{ $modeAffichage === 'tableau' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        📊 Tableau
                    </button>
                    <button wire:click="$set('modeAffichage', 'historique')" 
                            class="px-4 py-2 text-sm font-medium rounded-lg transition-colors
                                {{ $modeAffichage === 'historique' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        📈 Historique
                    </button>
                    
                </div>
            </div>
        </div>

        @if($modeAffichage === 'saisie')
            <!-- Contrôles de navigation de date -->
            <div class="bg-white shadow-lg rounded-xl p-6">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
                    <!-- Navigation date -->
                    <div class="md:col-span-6 flex items-center justify-center space-x-4">
                        <button wire:click="changerDate('precedent')" 
                                class="p-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </button>
                        
                        <div class="text-center">
                            <input wire:model.live="dateActuelle" 
                                   type="date" 
                                   class="text-lg font-semibold border-2 border-green-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            <div class="text-sm text-gray-600 mt-1">
                                {{ \Carbon\Carbon::parse($dateActuelle)->isoFormat('dddd D MMMM YYYY') }}
                            </div>
                        </div>
                        
                        <button wire:click="changerDate('suivant')" 
                                class="p-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Sélecteur de lieu -->
                    <div class="md:col-span-3">
                        <select wire:model.live="lieuSelectionne" wire:change="changerLieu" 
                                class="w-full border-2 border-green-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            <option value="antananarivo">🏢 Antananarivo</option>
                            <option value="mahajanga">🏢 Mahajanga</option>
                            <option value="autre">🏢 Autre lieu</option>
                        </select>
                    </div>
                    
                    <!-- Actions -->
                    <div class="md:col-span-3 flex space-x-2">
                        <button wire:click="openMouvementModal" 
                                class="flex-1 px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm">
                            Mouvement
                        </button>
                        <button wire:click="actualiserMouvement" 
                                class="flex-1 px-3 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm">
                            ✅ Actualiser
                        </button>
                    </div>
                </div>
            </div>

            <!-- Résumé du jour avec soldes des comptes -->
            <div class="bg-white shadow-lg rounded-xl p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">📊 Résumé du jour</h3>
                
                <!-- Totaux généraux -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600">{{ number_format($resumeJour->total_entrees ?? 0, 0, ',', ' ') }}</div>
                        <div class="text-sm text-gray-600">💰 Total Entrées (MGA)</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-red-600">{{ number_format($resumeJour->total_sorties ?? 0, 0, ',', ' ') }}</div>
                        <div class="text-sm text-gray-600">💸 Total Sorties (MGA)</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold {{ ($resumeJour->ecart ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ ($resumeJour->ecart ?? 0) >= 0 ? '+' : '' }}{{ number_format($resumeJour->ecart ?? 0, 0, ',', ' ') }}
                        </div>
                        <div class="text-sm text-gray-600">📈 Écart Net (MGA)</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-600">{{ $resumeJour->nombre_mouvements ?? 0 }}</div>
                        <div class="text-sm text-gray-600">🔄 Mouvements</div>
                    </div>
                </div>

                <!-- Soldes actuels des comptes -->
                <div class="border-t pt-4">
                    <h4 class="font-semibold text-gray-700 mb-3">💳 Soldes actuels des comptes</h4>
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                        @foreach($comptes as $compte)
                            <div class="text-center p-3 bg-gray-50 rounded-lg">
                                <div class="text-sm font-medium text-gray-700">{{ ucfirst($compte->type_compte) }}</div>
                                @if($compte->nom_proprietaire)
                                    <div class="text-xs text-gray-500">{{ $compte->nom_proprietaire }}</div>
                                @endif
                                <div class="text-lg font-bold {{ $compte->solde_actuel_mga >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ number_format($compte->solde_actuel_mga, 0, ',', ' ') }} MGA
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Saisie rapide -->
            <div class="bg-white shadow-lg rounded-xl overflow-hidden">
                <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-4">
                    <h2 class="text-xl font-semibold text-white">⚡ Saisie Rapide</h2>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                        <!-- Sélection du compte -->
                        <div class="md:col-span-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Compte</label>
                            <select wire:model="saisieRapide.compte_id" 
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                <option value="">-- Compte --</option>
                                @foreach($comptes as $compte)
                                    <option value="{{ $compte->id }}">
                                        {{ ucfirst($compte->type_compte) }}
                                        @if($compte->nom_proprietaire) - {{ $compte->nom_proprietaire }} @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('saisieRapide.compte_id') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Type de mouvement -->
                        <div class="md:col-span-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                            <select wire:model="saisieRapide.type" 
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                <option value="entree">💰 Entrée</option>
                                <option value="sortie">💸 Sortie</option>
                            </select>
                        </div>

                        <!-- Description -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <input wire:model="saisieRapide.description" 
                                   type="text" 
                                   placeholder="Ex: Loyer, Salaire, Achat..."
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            @error('saisieRapide.description') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Montant -->
                        <div class="md:col-span-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Montant (MGA)</label>
                            <input wire:model="saisieRapide.montant" 
                                   type="number" 
                                   step="0.01"
                                   placeholder="0.00"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            @error('saisieRapide.montant') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Bouton d'action -->
                        <div class="md:col-span-1 flex items-end">
                            <button wire:click="saisieRapideMouvement" 
                                    class="w-full bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 transition-colors font-semibold">
                                💾 Ajouter
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Liste des mouvements du jour -->
            @if(isset($mouvementsJour) && $mouvementsJour->count() > 0)
            <div class="space-y-4">
                @foreach($mouvementsJour as $nomCompte => $mouvements)
                    <div class="bg-white shadow-lg rounded-xl overflow-hidden">
                        <div class="bg-gray-50 px-6 py-3 border-b flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">
                                💳 {{ $nomCompte }}
                            </h3>
                            <div class="flex space-x-2">
                                <span class="text-sm text-gray-600">{{ $mouvements->count() }} mouvement(s)</span>
                            </div>
                        </div>

                        <div class="divide-y divide-gray-200">
                            @foreach($mouvements as $mouvement)
                                <div class="px-6 py-4 hover:bg-gray-50">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-3">
                                                <span class="text-2xl">
                                                    {{ $mouvement->type_mouvement === 'entree' ? '💰' : '💸' }}
                                                </span>
                                                <div>
                                                    <div class="font-semibold text-gray-900">{{ $mouvement->description }}</div>
                                                    @if($mouvement->commentaire)
                                                        <div class="text-sm text-gray-600">💬 {{ $mouvement->commentaire }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="flex items-center space-x-4">
                                            <div class="text-right">
                                                <div class="text-lg font-bold {{ $mouvement->type_mouvement === 'entree' ? 'text-green-600' : 'text-red-600' }}">
                                                    {{ $mouvement->montant_signe_formatted }}
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    {{ $mouvement->created_at->format('H:i') }}
                                                </div>
                                            </div>
                                            
                                            <div class="flex space-x-1">
                                                <button wire:click="editMouvement({{ $mouvement->id }})" 
                                                        class="text-blue-600 hover:text-blue-800">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                </button>
                                                <button wire:click="deleteMouvement({{ $mouvement->id }})" 
                                                        wire:confirm="Supprimer ce mouvement ?"
                                                        class="text-red-600 hover:text-red-800">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
            @else
                <div class="bg-white shadow-lg rounded-xl p-8 text-center">
                    <div class="text-gray-400 text-6xl mb-4">📝</div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun mouvement pour aujourd'hui</h3>
                    <p class="text-gray-600 mb-4">Commencez par ajouter votre premier mouvement financier.</p>
                    <button wire:click="openMouvementModal" 
                            class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition-colors">
                        ➕ Ajouter un mouvement
                    </button>
                </div>
            @endif

        @elseif($modeAffichage === 'tableau')
            <!-- Vue tableau semaine -->
            <div class="bg-white shadow-lg rounded-xl overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4 flex justify-between items-center">
                    <h2 class="text-xl font-semibold text-white">📊 Tableau Hebdomadaire</h2>
                    <div class="text-sm text-white opacity-75">
                        {{ \Carbon\Carbon::parse($dateActuelle)->startOfWeek()->format('d/m') }} - 
                        {{ \Carbon\Carbon::parse($dateActuelle)->endOfWeek()->format('d/m/Y') }}
                    </div>
                </div>

                @if(isset($tableauSemaine) && count($tableauSemaine) > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase sticky left-0 bg-gray-50">
                                        Compte
                                    </th>
                                    @foreach($tableauSemaine as $dateStr => $jour)
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase min-w-40">
                                            {{ $jour['date']->format('D') }}<br>
                                            <span class="text-gray-700 font-semibold">{{ $jour['date']->format('d/m') }}</span>
                                        </th>
                                    @endforeach
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase bg-blue-50">
                                        TOTAL
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($comptes as $compte)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 whitespace-nowrap font-medium text-gray-900 sticky left-0 bg-white">
                                            <div class="flex items-center space-x-2">
                                                <span class="w-3 h-3 rounded-full" style="background-color: {{ ['principal' => '#3B82F6', 'banque' => '#10B981', 'AirtelMoney' => '#EF4444', 'MVola' => '#F59E0B', 'Mvola' => '#F59E0B', 'OrangeMoney' => '#F97316'][$compte->type_compte] ?? '#6B7280' }}"></span>
                                                <span>{{ ucfirst($compte->type_compte) }}</span>
                                            </div>
                                            @if($compte->nom_proprietaire)
                                                <div class="text-xs text-gray-500">{{ $compte->nom_proprietaire }}</div>
                                            @endif
                                        </td>
                                        
                                        @php $totalCompte = 0; @endphp
                                        @foreach($tableauSemaine as $dateStr => $jour)
                                            <td class="px-4 py-3 text-center">
                                                @php 
                                                    $solde = $jour['comptes'][$compte->id]['solde'] ?? 0;
                                                    $totalCompte += $solde;
                                                    $entrees = $jour['comptes'][$compte->id]['entrees'] ?? 0;
                                                    $sorties = $jour['comptes'][$compte->id]['sorties'] ?? 0;
                                                @endphp
                                                
                                                @if($solde != 0)
                                                    <div class="text-sm font-semibold {{ $solde >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                                        {{ $solde >= 0 ? '+' : '' }}{{ number_format($solde, 0, ',', ' ') }}
                                                    </div>
                                                    @if($entrees > 0 || $sorties > 0)
                                                        <div class="text-xs text-gray-500">
                                                            @if($entrees > 0)<span class="text-green-600">E:{{ number_format($entrees, 0, ',', ' ') }}</span>@endif
                                                            @if($sorties > 0)@if($entrees > 0)<br>@endif<span class="text-red-600">S:{{ number_format($sorties, 0, ',', ' ') }}</span>@endif
                                                        </div>
                                                    @endif
                                                @else
                                                    <span class="text-gray-400">-</span>
                                                @endif
                                            </td>
                                        @endforeach
                                        
                                        <td class="px-4 py-3 text-center bg-blue-50">
                                            @if($totalCompte != 0)
                                                <span class="text-sm font-bold {{ $totalCompte >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                                    {{ $totalCompte >= 0 ? '+' : '' }}{{ number_format($totalCompte, 0, ',', ' ') }}
                                                </span>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                
                                <!-- Ligne TOTAL GLOBAL -->
                                <tr class="bg-blue-50 border-t-2 border-blue-200">
                                    <td class="px-4 py-3 font-bold text-blue-900 sticky left-0 bg-blue-50">
                                        TOTAL GLOBAL
                                    </td>
                                    @php $totalGeneral = 0; @endphp
                                    @foreach($tableauSemaine as $dateStr => $jour)
                                        <td class="px-4 py-3 text-center">
                                            @php $totalGeneral += $jour['ecart']; @endphp
                                            @if($jour['ecart'] != 0)
                                                <span class="text-sm font-bold {{ $jour['ecart'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                                    {{ $jour['ecart'] >= 0 ? '+' : '' }}{{ number_format($jour['ecart'], 0, ',', ' ') }}
                                                </span>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                    @endforeach
                                    <td class="px-4 py-3 text-center bg-blue-100">
                                        <span class="text-lg font-bold {{ $totalGeneral >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $totalGeneral >= 0 ? '+' : '' }}{{ number_format($totalGeneral, 0, ',', ' ') }}
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-8 text-center">
                        <div class="text-gray-400 text-6xl mb-4">📊</div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune donnée pour cette semaine</h3>
                        <p class="text-gray-600 mb-4">Ajoutez des mouvements financiers pour voir le tableau.</p>
                        <button wire:click="$set('modeAffichage', 'saisie')" 
                                class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition-colors">
                            📝 Aller à la saisie
                        </button>
                    </div>
                @endif
            </div>

        @elseif($modeAffichage === 'historique')
            <!-- Vue historique avec filtres -->
            <div class="bg-white shadow-lg rounded-xl p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                    <h2 class="text-xl font-semibold text-gray-900">📈 Historique des Mouvements</h2>
                    
                    <div class="flex flex-wrap gap-4">
                        <input wire:model.live="dateDebut" type="date" class="border border-gray-300 rounded-lg px-3 py-2">
                        <input wire:model.live="dateFin" type="date" class="border border-gray-300 rounded-lg px-3 py-2">
                        
                        <select wire:model.live="compteFiltre" class="border border-gray-300 rounded-lg px-3 py-2">
                            <option value="">Tous les comptes</option>
                            @foreach($comptes as $compte)
                                <option value="{{ $compte->id }}">{{ ucfirst($compte->type_compte) }}@if($compte->nom_proprietaire) - {{ $compte->nom_proprietaire }}@endif</option>
                            @endforeach
                        </select>
                        
                        <select wire:model.live="typeFiltre" class="border border-gray-300 rounded-lg px-3 py-2">
                            <option value="">Tous les types</option>
                            <option value="entree">💰 Entrées</option>
                            <option value="sortie">💸 Sorties</option>
                        </select>
                        
                        <button wire:click="resetFiltres" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                            🔄 Reset
                        </button>
                    </div>
                </div>

                <!-- Liste historique -->
                @if(isset($mouvements) && $mouvements->count() > 0)
                    <div class="space-y-3">
                        @foreach($mouvements as $mouvement)
                            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-4">
                                        <span class="text-2xl">{{ $mouvement->type_mouvement === 'entree' ? '💰' : '💸' }}</span>
                                        <div>
                                            <div class="font-semibold text-gray-900">{{ $mouvement->description }}</div>
                                            <div class="text-sm text-gray-600">
                                                {{ $mouvement->date_mouvement->format('d/m/Y') }} • 
                                                {{ ucfirst($mouvement->compte->type_compte) }}@if($mouvement->compte->nom_proprietaire) - {{ $mouvement->compte->nom_proprietaire }}@endif •
                                                {{ $mouvement->lieu_label }}
                                            </div>
                                            @if($mouvement->commentaire)
                                                <div class="text-sm text-gray-500 mt-1">💬 {{ $mouvement->commentaire }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="text-right">
                                        <div class="text-lg font-bold {{ $mouvement->type_mouvement === 'entree' ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $mouvement->montant_signe_formatted }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ $mouvement->created_at->format('H:i') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $mouvements->links() }}
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="text-gray-400 text-lg mb-2">📋</div>
                        <p class="text-gray-600">Aucun mouvement trouvé pour cette période</p>
                    </div>
                @endif
            </div>
        @endif

        <!-- Modal de saisie détaillée -->
        @if($showMouvementModal)
            <div class="fixed inset-0 z-50 overflow-y-auto">
                <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeMouvementModal"></div>
                    
                    <div class="inline-block bg-white rounded-lg shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                        <form wire:submit.prevent="sauvegarderMouvement" class="divide-y divide-gray-200">
                            <!-- Header -->
                            <div class="bg-green-600 px-4 py-3 sm:px-6 rounded-t-lg">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-lg font-semibold text-white">
                                        {{ $editingMouvement ? '✏️ MODIFIER MOUVEMENT' : '➕ NOUVEAU MOUVEMENT' }}
                                    </h3>
                                    <button wire:click="closeMouvementModal" class="text-white hover:text-gray-200">
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Contenu -->
                            <div class="px-4 py-5 sm:p-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Date -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                                        <input wire:model="dateMouvement" type="date" 
                                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                        @error('dateMouvement') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                    </div>

                                    <!-- Compte -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Compte</label>
                                        <select wire:model="compteId" 
                                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                            <option value="">-- Sélectionner un compte --</option>
                                            @foreach($comptes as $compte)
                                                <option value="{{ $compte->id }}">
                                                    {{ ucfirst($compte->type_compte) }}@if($compte->nom_proprietaire) - {{ $compte->nom_proprietaire }}@endif
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('compteId') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                    </div>

                                    <!-- Type de mouvement -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Type de mouvement</label>
                                        <select wire:model="typeMouvement" 
                                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                            <option value="entree">💰 Entrée</option>
                                            <option value="sortie">💸 Sortie</option>
                                        </select>
                                        @error('typeMouvement') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                    </div>

                                    <!-- Montant -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Montant (MGA)</label>
                                        <input wire:model="montant" type="number" step="0.01" placeholder="0.00"
                                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                        @error('montant') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                    </div>

                                    <!-- Description -->
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                        <input wire:model="description" type="text" placeholder="Ex: Loyer bureau, Salaire employé, Achat fournitures..."
                                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                        @error('description') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                    </div>

                                    <!-- Commentaire -->
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Commentaire (optionnel)</label>
                                        <textarea wire:model="commentaire" rows="3" placeholder="Notes supplémentaires..."
                                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500"></textarea>
                                        @error('commentaire') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Footer -->
                            <div class="px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse bg-gray-50 rounded-b-lg">
                                <button type="submit" 
                                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                                    {{ $editingMouvement ? '✏️ MODIFIER' : '💾 ENREGISTRER' }}
                                </button>
                                <button type="button" wire:click="closeMouvementModal" 
                                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                    Annuler
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>