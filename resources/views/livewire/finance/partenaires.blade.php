<div>
<div class="max-w-6xl mx-auto p-4 space-y-4">
    {{-- Messages --}}
    @if (session('success'))
        <div class="bg-green-50 p-2 rounded">{{ session('success') }}</div>
    @endif

    {{-- Filtres --}}
    <div class="flex gap-2">
        <input type="text" wire:model.live="search" placeholder="Rechercher..." class="border p-1 rounded">
        <select wire:model.live="filterType" class="border p-1 rounded">
            <option value="all">Tous types</option>
            <option value="fournisseur">Fournisseurs</option>
            <option value="client">Clients</option>
        </select>
        <button wire:click="create" class="bg-black text-white px-3 py-1 rounded">Nouveau</button>
    </div>

    {{-- Formulaire --}}
    <div class="border rounded p-3">
        <input type="text" wire:model.defer="nom" placeholder="Nom" class="border p-1 rounded w-full mb-2">
        <input type="text" wire:model.defer="telephone" placeholder="Téléphone" class="border p-1 rounded w-full mb-2">
        <input type="text" wire:model.defer="adresse" placeholder="Adresse" class="border p-1 rounded w-full mb-2">
        <select wire:model.defer="type" class="border p-1 rounded w-full mb-2">
            <option value="fournisseur">Fournisseur</option>
            <option value="client">Client</option>
        </select>
        <label class="flex items-center gap-2">
            <input type="checkbox" wire:model.defer="is_active"> Actif
        </label>
        <div class="mt-2 flex gap-2">
            <button wire:click="save" class="bg-blue-600 text-white px-3 py-1 rounded">
                {{ $editingId ? 'Mettre à jour' : 'Créer' }}
            </button>
            <button wire:click="create" type="button" class="border px-3 py-1 rounded">Réinitialiser</button>
        </div>
    </div>

    {{-- Tableau --}}
    <table class="w-full border">
        <thead class="bg-gray-100">
            <tr>
                <th class="border p-1">#</th>
                <th class="border p-1">Nom</th>
                <th class="border p-1">Téléphone</th>
                <th class="border p-1">Adresse</th>
                <th class="border p-1">Type</th>
                <th class="border p-1">Actif</th>
                <th class="border p-1">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rows as $p)
                <tr>
                    <td class="border p-1">{{ $p->id }}</td>
                    <td class="border p-1">{{ $p->nom }}</td>
                    <td class="border p-1">{{ $p->telephone }}</td>
                    <td class="border p-1">{{ $p->adresse }}</td>
                    <td class="border p-1">{{ $p->type }}</td>
                    <td class="border p-1">
                        <button wire:click="toggle({{ $p->id }})" class="px-2 py-1 text-xs rounded border">
                            {{ $p->is_active ? 'Actif' : 'Inactif' }}
                        </button>
                    </td>
                    <td class="border p-1 space-x-1">
                        <button wire:click="show({{ $p->id }})" class="border px-2 py-1 text-xs rounded">Voir</button>
                        <button wire:click="edit({{ $p->id }})" class="border px-2 py-1 text-xs rounded">Éditer</button>
                        <button wire:click="delete({{ $p->id }})" class="border px-2 py-1 text-xs rounded"
                                onclick="return confirm('Supprimer ce partenaire ?')">Supprimer</button>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="p-2 text-center">Aucun partenaire</td></tr>
            @endforelse
        </tbody>
    </table>

    {{-- Pagination --}}
    {{ $rows->links() }}

    {{-- Modal Détails --}}
    @if($showDetail && $detail)
        <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
            <div class="bg-white rounded p-4 w-96">
                <h2 class="text-lg font-semibold mb-3">Détails du partenaire</h2>
                <ul class="text-sm space-y-1">
                    <li><strong>ID:</strong> {{ $detail['id'] }}</li>
                    <li><strong>Nom:</strong> {{ $detail['nom'] }}</li>
                    <li><strong>Téléphone:</strong> {{ $detail['telephone'] ?? '—' }}</li>
                    <li><strong>Adresse:</strong> {{ $detail['adresse'] ?? '—' }}</li>
                    <li><strong>Type:</strong> {{ $detail['type'] }}</li>
                    <li><strong>Statut:</strong> {{ $detail['is_active'] ? 'Actif' : 'Inactif' }}</li>
                    <li><strong>Créé le:</strong> {{ $detail['created_at'] }}</li>
                    <li><strong>Mis à jour le:</strong> {{ $detail['updated_at'] }}</li>
                </ul>
                <div class="mt-3 flex justify-end gap-2">
                    <button wire:click="closeDetail" class="border px-3 py-1 rounded">Fermer</button>
                </div>
            </div>
        </div>
    @endif
</div>

</div>
