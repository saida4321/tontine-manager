<div>
    <div class="bg-white dark:bg-gray-800 shadow mb-6">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                💰 {{ __('Gestion des Cotisations') }}
            </h2>
            <button wire:click="openModal('create')"
                class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold py-2.5 px-5 rounded-lg shadow-lg transform transition hover:scale-105">
                <span class="mr-2">+</span> Nouvelle Cotisation
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
                        <select wire:model.live="filterStatut"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">📊 Tous les statuts</option>
                            <option value="en_attente">En attente</option>
                            <option value="validé">Validé</option>
                            <option value="rejeté">Rejeté</option>
                        </select>
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
                                        Référence</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Client</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Montant</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Date</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Cabinet</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Paiement</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Statut</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($filteredCotisations as $index => $cotisation)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2 py-1 text-xs font-mono bg-gray-100 text-gray-800 rounded">{{ $cotisation->reference_recu }}</span>
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap font-medium text-gray-900 dark:text-gray-100">
                                            {{ $cotisation->client->prenom ?? '' }} {{ $cotisation->client->nom ?? '' }}
                                            <br>
                                            <span
                                                class="text-xs text-gray-500">{{ $cotisation->client->identifiant_unique ?? '' }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600">
                                            {{ number_format($cotisation->montant, 0, ',', ' ') }} FCFA
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ \Carbon\Carbon::parse($cotisation->date_cotisation)->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                                                {{ $cotisation->cabinet->nom ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2 py-1 text-xs font-medium {{ $cotisation->type_paiement === 'especes' ? 'bg-yellow-100 text-yellow-800' : 'bg-purple-100 text-purple-800' }} rounded-full">
                                                {{ $cotisation->type_paiement === 'especes' ? '💵 Espèces' : '📱 Mobile Money' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $statutColors = [
                                                    'en_attente' => 'bg-yellow-100 text-yellow-800',
                                                    'validé' => 'bg-green-100 text-green-800',
                                                    'rejeté' => 'bg-red-100 text-red-800',
                                                ];
                                                $statutColor =
                                                    $statutColors[$cotisation->statut] ?? 'bg-gray-100 text-gray-800';
                                            @endphp
                                            <span
                                                class="px-2 py-1 text-xs font-semibold rounded-full {{ $statutColor }}">
                                                {{ ucfirst($cotisation->statut) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <div class="flex items-center space-x-3">
                                                @if ($cotisation->statut === 'en_attente')
                                                    <button wire:click="valider({{ $cotisation->id }})"
                                                        wire:confirm="Valider cette cotisation ?"
                                                        class="text-green-600 hover:text-green-900 transition"
                                                        title="Valider">✅</button>
                                                    <button wire:click="rejeter({{ $cotisation->id }})"
                                                        wire:confirm="Rejeter cette cotisation ?"
                                                        class="text-red-600 hover:text-red-900 transition"
                                                        title="Rejeter">❌</button>
                                                @else
                                                    <span class="text-gray-400 text-xs">
                                                        {{ $cotisation->statut === 'validé' ? 'Validée' : 'Rejetée' }}
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9"
                                            class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">Aucune
                                            cotisation trouvée.</td>
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
                                    <h2 class="text-xl font-semibold text-white">💰 Nouvelle Cotisation</h2>
                                    <button wire:click="closeModal" class="text-white hover:text-gray-200 transition">
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

                                <div class="mb-4" x-data="{ searchClient: '', showDropdown: false }">
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Client
                                        <span class="text-red-500">*</span></label>

                                    <div class="relative">
                                        <input type="text" x-model="searchClient" @focus="showDropdown = true"
                                            placeholder="🔍 Rechercher un client..."
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">

                                        <div x-show="showDropdown && searchClient.length > 0"
                                            @click.away="showDropdown = false"
                                            class="absolute z-10 w-full mt-1 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                                            @foreach ($clients as $client)
                                                <div x-show="'{{ strtolower($client->prenom . ' ' . $client->nom . ' ' . $client->identifiant_unique) }}'.includes(searchClient.toLowerCase())"
                                                    wire:click="$set('form.client_id', {{ $client->id }})"
                                                    @click="searchClient = '{{ $client->prenom }} {{ $client->nom }}'; showDropdown = false"
                                                    class="px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 cursor-pointer">
                                                    <div class="font-medium">{{ $client->prenom }}
                                                        {{ $client->nom }}</div>
                                                    <div class="text-xs text-gray-500">
                                                        {{ $client->identifiant_unique }} - {{ $client->telephone }}
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    @if ($form['client_id'])
                                        <p class="text-xs text-green-600 mt-1">✅ Client sélectionné :
                                            {{ $clients->firstWhere('id', $form['client_id'])->prenom ?? '' }}
                                            {{ $clients->firstWhere('id', $form['client_id'])->nom ?? '' }}
                                        </p>
                                    @endif
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

                                {{-- ✅ MODIFICATION 1: Montant minimum = 100 FCFA --}}
                                <div class="mb-4">
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Montant
                                        (FCFA) <span class="text-red-500">*</span></label>
                                    <input type="number" wire:model="form.montant"
                                        min="{{ $montantMinCotisation }}" step="50"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                        placeholder="Ex: 1000">
                                    <p class="text-xs text-gray-500 mt-1">Minimum
                                        {{ number_format($montantMinCotisation, 0, ',', ' ') }} FCFA</p>
                                </div>

                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date
                                        <span class="text-red-500">*</span></label>
                                    <input type="date" wire:model="form.date_cotisation"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                </div>

                                {{-- ✅ MODIFICATION 2: Détecter changement de mode de paiement --}}
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Mode
                                        de paiement <span class="text-red-500">*</span></label>
                                    <select wire:model.live="form.type_paiement"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                        <option value="especes">💵 Espèces</option>
                                        <option value="mobile_money">📱 Mobile Money</option>
                                    </select>
                                </div>

                                {{-- ✅ MODIFICATION 3: Champ téléphone Mobile Money avec intl-tel-input --}}
                                @if ($form['type_paiement'] === 'mobile_money')
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Numéro Mobile Money <span class="text-red-500">*</span>
                                        </label>
                                        <input type="tel" id="cotisation-phone-input"
                                            wire:model="form.telephone_mobile"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                                        <p class="text-xs text-gray-500 mt-1">Numéro pour le paiement Mobile Money</p>
                                    </div>
                                @endif

                                <div class="flex justify-end space-x-3">
                                    <button type="button" wire:click="closeModal"
                                        class="px-5 py-2.5 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-lg transition">Annuler</button>
                                    <button type="submit"
                                        class="px-5 py-2.5 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-medium rounded-lg transition">✅
                                        Enregistrer</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- ✅ Scripts intl-tel-input pour cotisations --}}
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
            let itiCotisationInstance = null;

            function initCotisationPhone() {
                const phoneInput = document.querySelector("#cotisation-phone-input");
                if (phoneInput && !itiCotisationInstance) {
                    itiCotisationInstance = window.intlTelInput(phoneInput, {
                        initialCountry: "tg",
                        preferredCountries: ["tg", "bj", "ci", "gh", "ng", "sn"],
                        separateDialCode: true,
                        utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@23.0.12/build/js/utils.js"
                    });

                    phoneInput.addEventListener('blur', () => {
                        @this.set('form.telephone_mobile', itiCotisationInstance.getNumber());
                    });
                }
            }

            Livewire.on('modalOpened', () => {
                setTimeout(initCotisationPhone, 150);
            });

            Livewire.on('modalClosed', () => {
                if (itiCotisationInstance) {
                    itiCotisationInstance.destroy();
                    itiCotisationInstance = null;
                }
            });

            Livewire.hook('morph.updated', () => {
                if (document.querySelector("#cotisation-phone-input")) {
                    if (itiCotisationInstance) {
                        itiCotisationInstance.destroy();
                        itiCotisationInstance = null;
                    }
                    setTimeout(initCotisationPhone, 100);
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
