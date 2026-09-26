<div>
    <div class="bg-white dark:bg-gray-800 shadow mb-6">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                🧑‍💼 {{ __('Gestion des Clients') }}
            </h2>
            <button wire:click="openModal('create')"
                class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold py-2.5 px-5 rounded-lg shadow-lg transform transition hover:scale-105">
                <span class="mr-2">+</span> Nouveau Client
            </button>
        </div>

        <div class="py-12">
            <div class="w-full py-6 px-4 lg:px-6">

                @if ($message)
                    <div
                        class="border px-4 py-3 rounded mb-4 {{ $messageType === 'success' ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700' }}">
                        {{ $message }}
                    </div>
                @endif

                <div class="mb-6 flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <input type="text" wire:model.live="search" placeholder="🔍 Rechercher..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                    <div class="w-48">
                        <select wire:model.live="filterCabinet"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">🏢 Tous les cabinets</option>
                            @foreach ($cabinets as $cabinet)
                                <option value="{{ $cabinet->id }}">{{ $cabinet->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="w-48">
                        <select wire:model.live="filterEtat"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">📊 Tous les états</option>
                            <option value="actif">Actif</option>
                            <option value="inactif">Inactif</option>
                            <option value="suspendu">Suspendu</option>
                        </select>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        #</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Identifiant</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Nom & Prénom</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Téléphone</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Email</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Cabinet</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Solde</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        État</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($filteredClients as $index => $client)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2 py-1 text-xs font-mono bg-gray-100 text-gray-800 rounded">{{ $client->identifiant_unique }}</span>
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap font-medium text-gray-900 dark:text-gray-100">
                                            {{ $client->prenom }} {{ $client->nom }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $client->telephone }}</td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $client->email ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                                                🏢 {{ $client->cabinet->nom }}
                                            </span>
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-semibold {{ $client->solde > 0 ? 'text-green-600' : 'text-gray-500' }}">
                                            {{ number_format($client->solde, 0, ',', ' ') }} FCFA
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $etatColors = [
                                                    'actif' => 'bg-green-100 text-green-800',
                                                    'inactif' => 'bg-gray-100 text-gray-800',
                                                    'suspendu' => 'bg-yellow-100 text-yellow-800',
                                                    'supprime' => 'bg-red-100 text-red-800',
                                                ];
                                                $etatColor = $etatColors[$client->etat] ?? 'bg-gray-100 text-gray-800';
                                            @endphp
                                            <span
                                                class="px-2 py-1 text-xs font-semibold rounded-full {{ $etatColor }}">
                                                {{ ucfirst($client->etat) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <div class="flex items-center space-x-3">
                                                {{-- Modifier (tous les rôles) --}}
                                                <button wire:click="openModal('edit', {{ $client->id }})"
                                                    class="text-yellow-600 hover:text-yellow-900 transition"
                                                    title="Modifier">✏️</button>

                                                {{-- Voir détails (ADMIN et COLLECTEUR uniquement) --}}
                                                @if (auth()->user()->role == 1 || auth()->user()->role == 3)
                                                    <button wire:click="openDetailsModal({{ $client->id }})"
                                                        class="text-blue-600 hover:text-blue-900 transition"
                                                        title="Voir détails">👁️</button>
                                                @endif
                                            </div>
                                        </td>

                                        {{-- Modal Détails Client --}}
                                        @if ($detailsModalOpen && $selectedClient)
                                            <div class="fixed inset-0 z-50 overflow-hidden">
                                                <div wire:click="closeDetailsModal"
                                                    class="absolute inset-0 bg-gray-500 bg-opacity-75 transition-opacity">
                                                </div>
                                                <div class="fixed inset-y-0 right-0 flex max-w-full pl-10">
                                                    <div class="w-screen max-w-4xl">
                                                        <div
                                                            class="h-full flex flex-col bg-white dark:bg-gray-800 shadow-xl overflow-y-scroll">
                                                            <div
                                                                class="px-6 py-6 bg-gradient-to-r from-purple-500 to-purple-600">
                                                                <div class="flex items-center justify-between">
                                                                    <h2 class="text-xl font-semibold text-white">
                                                                        👁️ Détails Client -
                                                                        {{ $selectedClient->prenom }}
                                                                        {{ $selectedClient->nom }}
                                                                    </h2>
                                                                    <button wire:click="closeDetailsModal"
                                                                        class="text-white hover:text-gray-200 transition">
                                                                        <svg class="h-6 w-6" fill="none"
                                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round" stroke-width="2"
                                                                                d="M6 18L18 6M6 6l12 12" />
                                                                        </svg>
                                                                    </button>
                                                                </div>
                                                            </div>

                                                            <div class="flex-1 px-6 py-6">
                                                                {{-- Informations du client --}}
                                                                <div
                                                                    class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-lg p-6 mb-6">
                                                                    <h3 class="text-lg font-bold text-gray-800 mb-4">📋
                                                                        Informations Générales</h3>
                                                                    <div class="grid grid-cols-2 gap-4">
                                                                        <div>
                                                                            <p class="text-sm text-gray-600">Identifiant
                                                                            </p>
                                                                            <p class="font-mono font-semibold">
                                                                                {{ $selectedClient->identifiant_unique }}
                                                                            </p>
                                                                        </div>
                                                                        <div>
                                                                            <p class="text-sm text-gray-600">Cabinet</p>
                                                                            <p class="font-semibold">
                                                                                {{ $selectedClient->cabinet->nom }}</p>
                                                                        </div>
                                                                        <div>
                                                                            <p class="text-sm text-gray-600">Téléphone
                                                                            </p>
                                                                            <p class="font-semibold">
                                                                                {{ $selectedClient->telephone }}</p>
                                                                        </div>
                                                                        <div>
                                                                            <p class="text-sm text-gray-600">Email</p>
                                                                            <p class="font-semibold">
                                                                                {{ $selectedClient->email ?? '-' }}</p>
                                                                        </div>
                                                                        <div>
                                                                            <p class="text-sm text-gray-600">Date
                                                                                d'inscription</p>
                                                                            <p class="font-semibold">
                                                                                {{ \Carbon\Carbon::parse($selectedClient->date_inscription)->format('d/m/Y') }}
                                                                            </p>
                                                                        </div>
                                                                        <div>
                                                                            <p class="text-sm text-gray-600">État</p>
                                                                            <span
                                                                                class="px-2 py-1 text-xs font-semibold rounded-full 
                                        {{ $selectedClient->etat === 'actif' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                                                {{ ucfirst($selectedClient->etat) }}
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                {{-- Actions sur le client (ADMIN uniquement) --}}
                                                                @if (auth()->user()->role == 1)
                                                                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
                                                                        <h3
                                                                            class="text-sm font-bold text-gray-700 mb-3">
                                                                            ⚙️ Actions</h3>
                                                                        <div class="flex flex-wrap gap-2">
                                                                            @if ($selectedClient->etat === 'actif')
                                                                                <button
                                                                                    wire:click="changeEtat({{ $selectedClient->id }}, 'inactif')"
                                                                                    wire:confirm="Désactiver ce client ?"
                                                                                    class="px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white text-sm rounded-lg transition">
                                                                                    ⏸️ Désactiver
                                                                                </button>
                                                                            @else
                                                                                <button
                                                                                    wire:click="changeEtat({{ $selectedClient->id }}, 'actif')"
                                                                                    class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white text-sm rounded-lg transition">
                                                                                    ▶️ Activer
                                                                                </button>
                                                                            @endif

                                                                            <button
                                                                                wire:click="changeEtat({{ $selectedClient->id }}, 'suspendu')"
                                                                                wire:confirm="Suspendre ce client ?"
                                                                                class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white text-sm rounded-lg transition">
                                                                                ⚠️ Suspendre
                                                                            </button>

                                                                            <button
                                                                                wire:click="changeEtat({{ $selectedClient->id }}, 'supprime')"
                                                                                wire:confirm="Supprimer définitivement ce client ?"
                                                                                class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-sm rounded-lg transition">
                                                                                🗑️ Supprimer
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                @endif

                                                                {{-- Statistiques --}}
                                                                <div class="grid grid-cols-3 gap-4 mb-6">
                                                                    <div
                                                                        class="bg-green-50 border border-green-200 rounded-lg p-4">
                                                                        <p
                                                                            class="text-xs text-green-600 font-semibold uppercase">
                                                                            Total Cotisations</p>
                                                                        <p class="text-2xl font-bold text-green-700">
                                                                            {{ number_format($clientStats['total_cotisations'], 0, ',', ' ') }}
                                                                            FCFA
                                                                        </p>
                                                                        <p class="text-xs text-gray-600 mt-1">
                                                                            {{ $clientStats['nombre_cotisations'] }}
                                                                            versement(s)</p>
                                                                    </div>
                                                                    <div
                                                                        class="bg-red-50 border border-red-200 rounded-lg p-4">
                                                                        <p
                                                                            class="text-xs text-red-600 font-semibold uppercase">
                                                                            Total Retraits</p>
                                                                        <p class="text-2xl font-bold text-red-700">
                                                                            {{ number_format($clientStats['total_retraits'], 0, ',', ' ') }}
                                                                            FCFA
                                                                        </p>
                                                                        <p class="text-xs text-gray-600 mt-1">
                                                                            {{ $clientStats['nombre_retraits'] }}
                                                                            retrait(s)</p>
                                                                    </div>
                                                                    <div
                                                                        class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                                                        <p
                                                                            class="text-xs text-blue-600 font-semibold uppercase">
                                                                            Solde Actuel</p>
                                                                        <p class="text-2xl font-bold text-blue-700">
                                                                            {{ number_format($clientStats['solde_actuel'], 0, ',', ' ') }}
                                                                            FCFA
                                                                        </p>
                                                                        <p class="text-xs text-gray-600 mt-1">
                                                                            Disponible</p>
                                                                    </div>
                                                                </div>

                                                                {{-- Historique des cotisations --}}
                                                                <div class="mb-6">
                                                                    <h3 class="text-lg font-bold text-gray-800 mb-3">💰
                                                                        Historique des Cotisations</h3>
                                                                    @if ($clientCotisations->count() > 0)
                                                                        <div class="overflow-x-auto">
                                                                            <table
                                                                                class="min-w-full divide-y divide-gray-200">
                                                                                <thead class="bg-gray-50">
                                                                                    <tr>
                                                                                        <th
                                                                                            class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                                                                            Date</th>
                                                                                        <th
                                                                                            class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                                                                            Référence</th>
                                                                                        <th
                                                                                            class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                                                                            Montant</th>
                                                                                        <th
                                                                                            class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                                                                            Mode</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody
                                                                                    class="bg-white divide-y divide-gray-200">
                                                                                    @foreach ($clientCotisations as $cotisation)
                                                                                        <tr>
                                                                                            <td
                                                                                                class="px-4 py-2 text-sm">
                                                                                                {{ \Carbon\Carbon::parse($cotisation->date_cotisation)->format('d/m/Y') }}
                                                                                            </td>
                                                                                            <td
                                                                                                class="px-4 py-2 text-xs font-mono">
                                                                                                {{ $cotisation->reference_recu }}
                                                                                            </td>
                                                                                            <td
                                                                                                class="px-4 py-2 text-sm font-semibold text-green-600">
                                                                                                {{ number_format($cotisation->montant, 0, ',', ' ') }}
                                                                                                FCFA
                                                                                            </td>
                                                                                            <td
                                                                                                class="px-4 py-2 text-xs">
                                                                                                {{ $cotisation->type_paiement === 'especes' ? '💵 Espèces' : '📱 Mobile' }}
                                                                                            </td>
                                                                                        </tr>
                                                                                    @endforeach
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    @else
                                                                        <p class="text-gray-500 text-sm italic">Aucune
                                                                            cotisation validée</p>
                                                                    @endif
                                                                </div>

                                                                {{-- Historique des retraits --}}
                                                                <div>
                                                                    <h3 class="text-lg font-bold text-gray-800 mb-3">💸
                                                                        Historique des Retraits</h3>
                                                                    @if ($clientRetraits->count() > 0)
                                                                        <div class="overflow-x-auto">
                                                                            <table
                                                                                class="min-w-full divide-y divide-gray-200">
                                                                                <thead class="bg-gray-50">
                                                                                    <tr>
                                                                                        <th
                                                                                            class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                                                                            Date</th>
                                                                                        <th
                                                                                            class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                                                                            Référence</th>
                                                                                        <th
                                                                                            class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                                                                            Montant</th>
                                                                                        <th
                                                                                            class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                                                                            Motif</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody
                                                                                    class="bg-white divide-y divide-gray-200">
                                                                                    @foreach ($clientRetraits as $retrait)
                                                                                        <tr>
                                                                                            <td
                                                                                                class="px-4 py-2 text-sm">
                                                                                                {{ \Carbon\Carbon::parse($retrait->date_retrait)->format('d/m/Y') }}
                                                                                            </td>
                                                                                            <td
                                                                                                class="px-4 py-2 text-xs font-mono">
                                                                                                {{ $retrait->reference }}
                                                                                            </td>
                                                                                            <td
                                                                                                class="px-4 py-2 text-sm font-semibold text-red-600">
                                                                                                {{ number_format($retrait->montant, 0, ',', ' ') }}
                                                                                                FCFA
                                                                                            </td>
                                                                                            <td
                                                                                                class="px-4 py-2 text-xs">
                                                                                                {{ $retrait->motif ?? '-' }}
                                                                                            </td>
                                                                                        </tr>
                                                                                    @endforeach
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    @else
                                                                        <p class="text-gray-500 text-sm italic">Aucun
                                                                            retrait effectué</p>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9"
                                            class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">Aucun client
                                            trouvé.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        @if ($modalOpen)
            <div class="fixed inset-0 z-50 overflow-hidden">
                <div wire:click="closeModal" class="absolute inset-0 bg-gray-500 bg-opacity-75 transition-opacity">
                </div>
                <div class="fixed inset-y-0 right-0 flex max-w-full pl-10">
                    <div class="w-screen max-w-md">
                        <div class="h-full flex flex-col bg-white dark:bg-gray-800 shadow-xl overflow-y-scroll">
                            <div class="px-6 py-6 bg-gradient-to-r from-blue-500 to-blue-600">
                                <div class="flex items-center justify-between">
                                    <h2 class="text-xl font-semibold text-white">
                                        {{ $modalMode === 'create' ? '🧑‍💼 Nouveau Client' : '✏️ Modifier Client' }}
                                    </h2>
                                    <button wire:click="closeModal" class="text-white hover:text-gray-200 transition">
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <form wire:submit="save" class="flex-1 px-6 py-6">
                                @if ($errors->any())
                                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                                        <ul class="list-disc list-inside">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                @if ($modalMode === 'edit')
                                    <div class="mb-4">
                                        <label
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Identifiant</label>
                                        <input type="text" value="{{ $form['identifiant_unique'] }}" disabled
                                            class="w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg font-mono text-sm">
                                    </div>
                                @endif

                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nom
                                        <span class="text-red-500">*</span></label>
                                    <input type="text" wire:model="form.nom"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                        placeholder="AFOUDJI">
                                </div>

                                <div class="mb-4">
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Prénom
                                        <span class="text-red-500">*</span></label>
                                    <input type="text" wire:model="form.prenom"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                        placeholder="Kokou">
                                </div>

                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Téléphone <span class="text-red-500">*</span>
                                    </label>
                                    <input type="tel" id="phone-input" wire:model="form.telephone"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                                </div>

                                <div class="mb-4">
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email</label>
                                    <input type="email" wire:model="form.email"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                        placeholder="kokou@example.com">
                                </div>

                                <div class="mb-4">
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Adresse</label>
                                    <textarea wire:model="form.adresse" rows="2"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                        placeholder="Quartier Adidogomé"></textarea>
                                </div>

                                <div class="mb-4">
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Cabinet
                                        <span class="text-red-500">*</span></label>
                                    <select wire:model="form.cabinet_id"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                        <option value="">-- Sélectionner --</option>
                                        @foreach ($cabinets as $cabinet)
                                            <option value="{{ $cabinet->id }}">{{ $cabinet->nom }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date
                                        d'inscription <span class="text-red-500">*</span></label>
                                    <input type="date" wire:model="form.date_inscription"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                </div>

                                <div class="mb-6">
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">État</label>
                                    <select wire:model="form.etat"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                        <option value="actif">Actif</option>
                                        <option value="inactif">Inactif</option>
                                        <option value="suspendu">Suspendu</option>
                                    </select>
                                </div>

                                <div class="flex justify-end space-x-3">
                                    <button type="button" wire:click="closeModal"
                                        class="px-5 py-2.5 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-lg transition">Annuler</button>
                                    <button type="submit"
                                        class="px-5 py-2.5 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-medium rounded-lg transition">
                                        {{ $modalMode === 'create' ? '✅ Créer' : '💾 Enregistrer' }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    @assets
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@23.0.12/build/css/intlTelInput.css">
        <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@23.0.12/build/js/intlTelInput.min.js"></script>
        <style>
            .iti {
                width: 100%;
            }

            .iti__tel-input {
                width: 100% !important;
            }
        </style>
    @endassets

    @script
        <script>
            let itiInstance = null;

            function initPhone() {
                const phoneInput = document.querySelector("#phone-input");
                if (phoneInput && !itiInstance) {
                    itiInstance = window.intlTelInput(phoneInput, {
                        initialCountry: "tg",
                        preferredCountries: ["tg", "bj", "ci", "gh", "ng", "sn"],
                        separateDialCode: true,
                        utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@23.0.12/build/js/utils.js"
                    });

                    phoneInput.addEventListener('blur', () => {
                        @this.set('form.telephone', itiInstance.getNumber());
                    });

                    phoneInput.addEventListener('blur', () => {
                        @this.set('form.telephone', itiInstance.getNumber());
                    });
                    
                    phoneInput.addEventListener('change', () => {
                        @this.set('form.telephone', itiInstance.getNumber());
                    });
                }
            }

            Livewire.on('modalOpened', () => {
                setTimeout(initPhone, 150);
            });

            Livewire.on('modalClosed', () => {
                if (itiInstance) {
                    itiInstance.destroy();
                    itiInstance = null;
                }
            });

            // ✅ Réinitialiser après chaque update Livewire
            Livewire.hook('morph.updated', () => {
                if (document.querySelector("#phone-input")) {
                    if (itiInstance) {
                        itiInstance.destroy();
                        itiInstance = null;
                    }
                    setTimeout(initPhone, 100);
                }
            });
        </script>
    @endscript

    @if ($message)
        <script>
            setTimeout(() => {
                @this.set('message', '');
            }, 5000);
        </script>
    @endif
</div>
