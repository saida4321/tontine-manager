<div>
    <div class="bg-white dark:bg-gray-800 shadow mb-6">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                👥 {{ __('Gestion des Utilisateurs') }}
            </h2>
            <button wire:click="openModal('create')"
                class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold py-2.5 px-5 rounded-lg shadow-lg transform transition hover:scale-105">
                <span class="mr-2">+</span> Nouvel Utilisateur
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
                        <select wire:model.live="filterRole"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">🎯 Tous les rôles</option>
                            @foreach ($roles as $roleId => $roleName)
                                <option value="{{ $roleId }}">{{ $roleName }}</option>
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
                                        Nom</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Email</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Téléphone</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Rôle</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Cabinets</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        État</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($filteredUsers as $index => $user)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $index + 1 }}</td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap font-medium text-gray-900 dark:text-gray-100">
                                            {{ $user->nom }}</td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $user->email }}</td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $user->telephone ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $roleColors = [
                                                    2 => 'bg-blue-100 text-blue-800',
                                                    3 => 'bg-green-100 text-green-800',
                                                    4 => 'bg-purple-100 text-purple-800',
                                                ];
                                                $roleColor = $roleColors[$user->role] ?? 'bg-gray-100 text-gray-800';
                                            @endphp
                                            <span
                                                class="px-2 py-1 text-xs font-semibold rounded-full {{ $roleColor }}">
                                                {{ $user->role_name }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                            @if ($user->cabinets->count() > 0)
                                                <div class="flex flex-wrap gap-1">
                                                    @foreach ($user->cabinets as $cabinet)
                                                        <span
                                                            class="px-2 py-0.5 text-xs bg-indigo-100 text-indigo-700 rounded">{{ $cabinet->nom }}</span>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="text-gray-400 italic">Aucun</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $etatColors = [
                                                    'actif' => 'bg-green-100 text-green-800',
                                                    'inactif' => 'bg-gray-100 text-gray-800',
                                                    'suspendu' => 'bg-yellow-100 text-yellow-800',
                                                    'supprime' => 'bg-red-100 text-red-800',
                                                ];
                                                $etatColor = $etatColors[$user->etat] ?? 'bg-gray-100 text-gray-800';
                                            @endphp
                                            <span
                                                class="px-2 py-1 text-xs font-semibold rounded-full {{ $etatColor }}">
                                                {{ ucfirst($user->etat) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <div class="flex items-center space-x-3">
                                                <button wire:click="openModal('edit', {{ $user->id }})"
                                                    class="text-yellow-600 hover:text-yellow-900 transition"
                                                    title="Modifier">✏️</button>

                                                @if ($user->etat === 'actif')
                                                    <button wire:click="changeEtat({{ $user->id }}, 'inactif')"
                                                        wire:confirm="Désactiver ?"
                                                        class="text-orange-600 hover:text-orange-900 transition"
                                                        title="Désactiver">⏸️</button>
                                                @else
                                                    <button wire:click="changeEtat({{ $user->id }}, 'actif')"
                                                        class="text-green-600 hover:text-green-900 transition"
                                                        title="Activer">▶️</button>
                                                @endif

                                                <button wire:click="changeEtat({{ $user->id }}, 'suspendu')"
                                                    wire:confirm="Suspendre ?"
                                                    class="text-yellow-600 hover:text-yellow-900 transition"
                                                    title="Suspendre">⚠️</button>

                                                <button wire:click="changeEtat({{ $user->id }}, 'supprime')"
                                                    wire:confirm="Supprimer définitivement ?"
                                                    class="text-red-600 hover:text-red-900 transition"
                                                    title="Supprimer">🗑️</button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8"
                                            class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">Aucun
                                            utilisateur trouvé.</td>
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
                                        {{ $modalMode === 'create' ? '👥 Nouvel Utilisateur' : '✏️ Modifier Utilisateur' }}
                                    </h2>
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

                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nom
                                        <span class="text-red-500">*</span></label>
                                    <input type="text" wire:model="form.nom"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                        placeholder="Ex: Jean KOFFI">
                                </div>

                                <div class="mb-4">
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email
                                        <span class="text-red-500">*</span></label>
                                    <input type="email" wire:model="form.email"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                        placeholder="jean@tontine.com">
                                </div>

                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Téléphone <span class="text-red-500">*</span>
                                    </label>
                                    <input type="tel" id="user-phone-input" wire:model="form.telephone"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                                </div>

                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Rôle
                                        <span class="text-red-500">*</span></label>
                                    <select wire:model="form.role"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                        <option value="">-- Sélectionner --</option>
                                        @foreach ($roles as $roleId => $roleName)
                                            <option value="{{ $roleId }}">{{ $roleName }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-4">
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Cabinets
                                        <span class="text-red-500">*</span></label>
                                    <div
                                        class="border border-gray-300 rounded-lg p-3 max-h-48 overflow-y-auto dark:bg-gray-700 dark:border-gray-600">
                                        @foreach ($cabinets as $cabinet)
                                            <label
                                                class="flex items-center py-2 hover:bg-gray-50 dark:hover:bg-gray-600 px-2 rounded cursor-pointer">
                                                <input type="checkbox" wire:model="form.cabinets"
                                                    value="{{ $cabinet->id }}"
                                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                                <span
                                                    class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ $cabinet->nom }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Mot de passe {{ $modalMode === 'edit' ? '(vide = pas de changement)' : '' }}
                                        @if ($modalMode === 'create')
                                            <span class="text-red-500">*</span>
                                        @endif
                                    </label>
                                    <div class="relative">
                                        <input type="{{ $showPassword ? 'text' : 'password' }}"
                                            wire:model="form.password"
                                            class="w-full px-4 py-2 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                            placeholder="••••••••">
                                        <button type="button" wire:click="togglePasswordVisibility"
                                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                            @if ($showPassword)
                                                🙈 {{-- Masquer --}}
                                            @else
                                                👁️ {{-- Voir --}}
                                            @endif
                                        </button>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Min 8 caractères, 1 majuscule, 1 chiffre</p>
                                </div>

                                <div class="mb-4">
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Confirmer</label>
                                    <input type="{{ $showPassword ? 'text' : 'password' }}"
                                        wire:model="form.password_confirmation"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                        placeholder="••••••••">
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
            let itiUserInstance = null;

            function initUserPhone() {
                const phoneInput = document.querySelector("#user-phone-input");
                if (phoneInput && !itiUserInstance) {
                    itiUserInstance = window.intlTelInput(phoneInput, {
                        initialCountry: "tg",
                        preferredCountries: ["tg", "bj", "ci", "gh", "ng", "sn"],
                        separateDialCode: true,
                        utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@23.0.12/build/js/utils.js"
                    });

                    phoneInput.addEventListener('blur', () => {
                        @this.set('form.telephone', itiUserInstance.getNumber());
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
                setTimeout(initUserPhone, 150);
            });

            Livewire.on('modalClosed', () => {
                if (itiUserInstance) {
                    itiUserInstance.destroy();
                    itiUserInstance = null;
                }
            });

            Livewire.hook('morph.updated', () => {
                if (document.querySelector("#user-phone-input")) {
                    if (itiUserInstance) {
                        itiUserInstance.destroy();
                        itiUserInstance = null;
                    }
                    setTimeout(initUserPhone, 100);
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
