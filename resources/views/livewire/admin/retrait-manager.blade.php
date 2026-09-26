<div>
    <div class="bg-white dark:bg-gray-800 shadow mb-6">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                💸 {{ __('Gestion des Retraits') }}
            </h2>
            <button wire:click="openModal('create')"
                class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold py-2.5 px-5 rounded-lg shadow-lg transform transition hover:scale-105">
                <span class="mr-2">+</span> Nouveau Retrait
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

                {{-- ✅ FILTRES CORRECTS --}}
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
                            <option value="approuvé">Approuvé</option>
                            <option value="effectué">Effectué</option>
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
                                @forelse($filteredRetraits as $index => $retrait)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2 py-1 text-xs font-mono bg-gray-100 text-gray-800 rounded">{{ $retrait->reference }}</span>
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap font-medium text-gray-900 dark:text-gray-100">
                                            {{ $retrait->client->prenom ?? '' }} {{ $retrait->client->nom ?? '' }}
                                            <br>
                                            <span
                                                class="text-xs text-gray-500">{{ $retrait->client->identifiant_unique ?? '' }}</span>
                                            <br>
                                            <span class="text-xs text-green-600 font-semibold">Solde:
                                                {{ number_format($retrait->client->solde ?? 0, 0, ',', ' ') }}
                                                FCFA</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-red-600">
                                            {{ number_format($retrait->montant, 0, ',', ' ') }} FCFA
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ \Carbon\Carbon::parse($retrait->date_demande)->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                                                {{ $retrait->cabinet->nom ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2 py-1 text-xs font-medium {{ ($retrait->type_paiement ?? 'especes') === 'especes' ? 'bg-yellow-100 text-yellow-800' : 'bg-purple-100 text-purple-800' }} rounded-full">
                                                {{ ($retrait->type_paiement ?? 'especes') === 'especes' ? '💵 Espèces' : '📱 Mobile Money' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $statutColors = [
                                                    'en_attente' => 'bg-yellow-100 text-yellow-800',
                                                    'approuvé' => 'bg-blue-100 text-blue-800',
                                                    'effectué' => 'bg-green-100 text-green-800',
                                                    'rejeté' => 'bg-red-100 text-red-800',
                                                ];
                                                $statutColor =
                                                    $statutColors[$retrait->statut] ?? 'bg-gray-100 text-gray-800';
                                            @endphp
                                            <span
                                                class="px-2 py-1 text-xs font-semibold rounded-full {{ $statutColor }}">
                                                {{ ucfirst($retrait->statut) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <div class="flex items-center space-x-3">
                                                @if ($retrait->statut === 'en_attente')
                                                    <button wire:click="openModal('edit', {{ $retrait->id }})"
                                                        class="text-yellow-600 hover:text-yellow-900 transition"
                                                        title="Modifier">✏️</button>
                                                    <button wire:click="approuver({{ $retrait->id }})"
                                                        wire:confirm="Approuver ce retrait ?"
                                                        class="text-blue-600 hover:text-blue-900 transition"
                                                        title="Approuver">✅</button>
                                                    <button wire:click="rejeter({{ $retrait->id }})"
                                                        wire:confirm="Rejeter ce retrait ?"
                                                        class="text-red-600 hover:text-red-900 transition"
                                                        title="Rejeter">❌</button>
                                                @elseif($retrait->statut === 'approuvé')
                                                    <button wire:click="effectuer({{ $retrait->id }})"
                                                        wire:confirm="Effectuer ce retrait ? Le solde sera débité."
                                                        class="text-green-600 hover:text-green-900 transition"
                                                        title="Effectuer">💰</button>
                                                @else
                                                    <span class="text-gray-400 text-xs">
                                                        {{ $retrait->statut === 'effectué' ? 'Effectué' : 'Rejeté' }}
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9"
                                            class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">Aucun retrait
                                            trouvé.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- ✅ MODAL NOUVEAU RETRAIT --}}
        @if ($modalOpen)
            <div class="fixed inset-0 z-50 overflow-hidden">
                <div wire:click="closeModal" class="absolute inset-0 bg-gray-500 bg-opacity-75 transition-opacity">
                </div>
                <div class="fixed inset-y-0 right-0 flex max-w-full pl-10">
                    <div class="w-screen max-w-md">
                        <div class="h-full flex flex-col bg-white dark:bg-gray-800 shadow-xl overflow-y-scroll">
                            <div class="px-6 py-6 bg-gradient-to-r from-blue-500 to-blue-600">
                                <div class="flex items-center justify-between">
                                    <h2 class="text-xl font-semibold text-white">💸 Nouveau Retrait</h2>
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

                                {{-- ✅ RECHERCHE CLIENT CORRIGÉE --}}
                                <div class="mb-4">
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Client
                                        <span class="text-red-500">*</span></label>

                                    <div class="relative">
                                        <input type="text" wire:model.live="searchClient"
                                            placeholder="🔍 Rechercher un client..."
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">

                                        @if (strlen($searchClient ?? '') > 0)
                                            <div
                                                class="absolute z-10 w-full mt-1 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                                                @forelse($clients->filter(fn($c) => str_contains(strtolower($c->prenom . ' ' . $c->nom . ' ' . $c->identifiant_unique), strtolower($searchClient))) as $client)
                                                    <div wire:click="selectClient({{ $client->id }})"
                                                        class="px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 cursor-pointer">
                                                        <div class="font-medium">{{ $client->prenom }}
                                                            {{ $client->nom }}
                                                        </div>
                                                        <div class="text-xs text-gray-500">
                                                            {{ $client->identifiant_unique }} - Solde:
                                                            {{ number_format($client->solde, 0, ',', ' ') }} FCFA
                                                        </div>
                                                    </div>
                                                @empty
                                                    <div class="px-4 py-2 text-gray-500 text-sm">Aucun client trouvé
                                                    </div>
                                                @endforelse
                                            </div>
                                        @endif
                                    </div>

                                    @if ($form['client_id'])
                                        <div class="mt-2 p-2 bg-green-50 border border-green-200 rounded">
                                            <p class="text-xs text-green-800">✅ Client sélectionné :
                                                {{ $clients->firstWhere('id', $form['client_id'])->prenom ?? '' }}
                                                {{ $clients->firstWhere('id', $form['client_id'])->nom ?? '' }}
                                            </p>
                                            <p class="text-sm font-bold text-green-700">💰 Solde disponible:
                                                {{ number_format($soldeClient, 0, ',', ' ') }} FCFA</p>
                                        </div>
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

                                <div class="mb-4">
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Montant
                                        (FCFA) <span class="text-red-500">*</span></label>
                                    <input type="number" wire:model="form.montant" min="{{ $montantMinRetrait }}"
                                        step="50"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                        placeholder="Ex: 5000">
                                    <p class="text-xs text-gray-500 mt-1">Minimum
                                        {{ number_format($montantMinRetrait, 0, ',', ' ') }} FCFA</p>
                                </div>

                                <div class="mb-4">
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Motif</label>
                                    <textarea wire:model="form.motif" rows="2"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                        placeholder="Ex: Retrait pour urgence familiale"></textarea>
                                </div>

                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date
                                        <span class="text-red-500">*</span></label>
                                    <input type="date" wire:model="form.date_demande"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                </div>

                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Mode
                                        de paiement <span class="text-red-500">*</span></label>
                                    <select wire:model.live="form.type_paiement"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                        <option value="especes">💵 Espèces</option>
                                        <option value="mobile_money">📱 Mobile Money</option>
                                    </select>
                                </div>

                                @if ($form['type_paiement'] === 'mobile_money')
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Numéro Mobile Money <span class="text-red-500">*</span>
                                        </label>
                                        <input type="tel" id="retrait-phone-input"
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
            let itiRetraitInstance = null;

            function initRetraitPhone() {
                const phoneInput = document.querySelector("#retrait-phone-input");
                if (phoneInput && !itiRetraitInstance) {
                    itiRetraitInstance = window.intlTelInput(phoneInput, {
                        initialCountry: "tg",
                        preferredCountries: ["tg", "bj", "ci", "gh", "ng", "sn"],
                        separateDialCode: true,
                        utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@23.0.12/build/js/utils.js"
                    });

                    phoneInput.addEventListener('blur', () => {
                        @this.set('form.telephone_mobile', itiRetraitInstance.getNumber());
                    });
                }
            }

            Livewire.on('modalOpened', () => {
                setTimeout(initRetraitPhone, 150);
            });

            Livewire.on('modalClosed', () => {
                if (itiRetraitInstance) {
                    itiRetraitInstance.destroy();
                    itiRetraitInstance = null;
                }
            });

            Livewire.hook('morph.updated', () => {
                if (document.querySelector("#retrait-phone-input")) {
                    if (itiRetraitInstance) {
                        itiRetraitInstance.destroy();
                        itiRetraitInstance = null;
                    }
                    setTimeout(initRetraitPhone, 100);
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
