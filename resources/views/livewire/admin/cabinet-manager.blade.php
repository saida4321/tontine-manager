<div>
    <div class="bg-white dark:bg-gray-800 shadow mb-6">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                🏢 {{ __('Gestion des Cabinets') }}
            </h2>
            <button wire:click="openModal('create')"
                class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold py-2.5 px-5 rounded-lg shadow-lg transform transition hover:scale-105">
                <span class="mr-2">+</span> Nouveau Cabinet
            </button>
        </div>

        <div class="py-12">
            <div class="w-full py-6 px-4 lg:px-6">

                {{-- Messages Flash --}}
                @if ($message)
                    <div
                        class="border px-4 py-3 rounded mb-4 {{ $messageType === 'success' ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700' }}">
                        {{ $message }}
                    </div>
                @endif

                {{-- Barre de recherche --}}
                <div class="mb-6 flex gap-4">
                    <div class="flex-1">
                        <input type="text" wire:model.live="search" placeholder="🔍 Rechercher un cabinet..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
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
                                        Adresse</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Téléphone</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Email</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($filteredCabinets as $index => $cabinet)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $index + 1 }}</td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap font-medium text-gray-900 dark:text-gray-100">
                                            {{ $cabinet->nom }}</td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $cabinet->adresse ?? '-' }}</td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $cabinet->telephone ?? '-' }}</td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $cabinet->email ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm space-x-3">
                                            <button wire:click="openModal('edit', {{ $cabinet->id }})"
                                                class="text-yellow-600 hover:text-yellow-900 dark:hover:text-yellow-400 transition">
                                                ✏️ Modifier
                                            </button>
                                            <button wire:click="delete({{ $cabinet->id }})"
                                                wire:confirm="Êtes-vous sûr de vouloir supprimer ce cabinet ?"
                                                class="text-red-600 hover:text-red-900 dark:hover:text-red-400 transition">
                                                🗑️ Supprimer
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6"
                                            class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                            Aucun cabinet trouvé.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- MODAL SLIDE-IN --}}
        @if ($modalOpen)
            <div class="fixed inset-0 z-50 overflow-hidden">
                {{-- Overlay --}}
                <div wire:click="closeModal" class="absolute inset-0 bg-gray-500 bg-opacity-75 transition-opacity">
                </div>

                {{-- Slide-in Panel --}}
                <div class="fixed inset-y-0 right-0 flex max-w-full pl-10">
                    <div class="w-screen max-w-md">
                        <div class="h-full flex flex-col bg-white dark:bg-gray-800 shadow-xl overflow-y-scroll">
                            {{-- Header --}}
                            <div class="px-6 py-6 bg-gradient-to-r from-blue-500 to-blue-600">
                                <div class="flex items-center justify-between">
                                    <h2 class="text-xl font-semibold text-white">
                                        {{ $modalMode === 'create' ? '🏢 Nouveau Cabinet' : '✏️ Modifier Cabinet' }}
                                    </h2>
                                    <button wire:click="closeModal" class="text-white hover:text-gray-200 transition">
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            {{-- Form --}}
                            <form wire:submit="save" class="flex-1 px-6 py-6">
                                {{-- Erreurs --}}
                                @if ($errors->any())
                                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                                        <ul class="list-disc list-inside">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                {{-- Champ Nom --}}
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Nom du cabinet <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" wire:model="form.nom"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                        placeholder="Ex: Agence Lomé Centre">
                                    @error('form.nom')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Champ Adresse --}}
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Adresse
                                    </label>
                                    <textarea wire:model="form.adresse" rows="3"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                        placeholder="Ex: Avenue de la Libération, Lomé"></textarea>
                                </div>

                                {{-- Champ Téléphone --}}
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Téléphone
                                    </label>
                                    <input type="text" wire:model="form.telephone"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                        placeholder="Ex: +228 22 11 00 01">
                                </div>

                                {{-- Champ Email --}}
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Email
                                    </label>
                                    <input type="email" wire:model="form.email"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                        placeholder="Ex: lome.centre@tontine.com">
                                </div>

                                {{-- Boutons --}}
                                <div class="flex justify-end space-x-3">
                                    <button type="button" wire:click="closeModal"
                                        class="px-5 py-2.5 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-lg transition">
                                        Annuler
                                    </button>
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

        {{-- Auto-hide message after 5 seconds --}}
        @if ($message)
            <script>
                setTimeout(() => {
                    @this.set('message', '');
                }, 5000);
            </script>
        @endif
    </div>
</div>
