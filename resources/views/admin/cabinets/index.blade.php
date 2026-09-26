<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                🏢 {{ __('Gestion des Cabinets') }}
            </h2>
            <button @click="openModal('create')"
                class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold py-2.5 px-5 rounded-lg shadow-lg transform transition hover:scale-105">
                <span class="mr-2">+</span> Nouveau Cabinet
            </button>
        </div>
    </x-slot>

    <div class="py-12" x-data="cabinetManager()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Messages Flash --}}
            <div x-show="message" x-transition x-cloak
                :class="messageType === 'success' ? 'bg-green-100 border-green-400 text-green-700' :
                    'bg-red-100 border-red-400 text-red-700'"
                class="border px-4 py-3 rounded mb-4">
                <span x-text="message"></span>
            </div>

            {{-- Barre de recherche --}}
            <div class="mb-6 flex gap-4">
                <div class="flex-1">
                    <input type="text" x-model="search" @input="filterCabinets()"
                        placeholder="🔍 Rechercher un cabinet..."
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
                            <template x-for="(cabinet, index) in filteredCabinets" :key="cabinet.id">
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm" x-text="index + 1"></td>
                                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900 dark:text-gray-100"
                                        x-text="cabinet.nom"></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"
                                        x-text="cabinet.adresse || '-'"></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"
                                        x-text="cabinet.telephone || '-'"></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"
                                        x-text="cabinet.email || '-'"></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm space-x-3">
                                        <button @click="openModal('edit', cabinet)"
                                            class="text-yellow-600 hover:text-yellow-900 dark:hover:text-yellow-400 transition">
                                            ✏️ Modifier
                                        </button>
                                        <button @click="deleteCabinet(cabinet.id)"
                                            class="text-red-600 hover:text-red-900 dark:hover:text-red-400 transition">
                                            🗑️ Supprimer
                                        </button>
                                    </td>
                                </tr>
                            </template>

                            <tr x-show="filteredCabinets.length === 0">
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                    Aucun cabinet trouvé.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- MODAL SLIDE-IN --}}
        <div x-show="modalOpen" x-cloak class="fixed inset-0 z-50 overflow-hidden"
            @keydown.escape.window="closeModal()">

            {{-- Overlay --}}
            <div x-show="modalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="closeModal()"
                class="absolute inset-0 bg-gray-500 bg-opacity-75 transition-opacity">
            </div>

            {{-- Slide-in Panel --}}
            <div class="fixed inset-y-0 right-0 flex max-w-full pl-10">
                <div x-show="modalOpen" x-transition:enter="transform transition ease-in-out duration-300"
                    x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                    x-transition:leave="transform transition ease-in-out duration-300"
                    x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
                    class="w-screen max-w-md">

                    <div class="h-full flex flex-col bg-white dark:bg-gray-800 shadow-xl overflow-y-scroll">
                        {{-- Header --}}
                        <div class="px-6 py-6 bg-gradient-to-r from-blue-500 to-blue-600">
                            <div class="flex items-center justify-between">
                                <h2 class="text-xl font-semibold text-white"
                                    x-text="modalMode === 'create' ? '🏢 Nouveau Cabinet' : '✏️ Modifier Cabinet'"></h2>
                                <button @click="closeModal()" class="text-white hover:text-gray-200 transition">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        {{-- Form --}}
                        <form @submit.prevent="submitForm()" class="flex-1 px-6 py-6">
                            {{-- Erreurs --}}
                            <div x-show="errors.length > 0"
                                class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                                <ul class="list-disc list-inside">
                                    <template x-for="error in errors">
                                        <li x-text="error"></li>
                                    </template>
                                </ul>
                            </div>

                            {{-- Champ Nom --}}
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Nom du cabinet <span class="text-red-500">*</span>
                                </label>
                                <input type="text" x-model="form.nom"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    placeholder="Ex: Agence Lomé Centre" required>
                            </div>

                            {{-- Champ Adresse --}}
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Adresse
                                </label>
                                <textarea x-model="form.adresse" rows="3"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    placeholder="Ex: Avenue de la Libération, Lomé"></textarea>
                            </div>

                            {{-- Champ Téléphone --}}
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Téléphone
                                </label>
                                <input type="text" x-model="form.telephone"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    placeholder="Ex: +228 22 11 00 01">
                            </div>

                            {{-- Champ Email --}}
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Email
                                </label>
                                <input type="email" x-model="form.email"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    placeholder="Ex: lome.centre@tontine.com">
                            </div>

                            {{-- Boutons --}}
                            <div class="flex justify-end space-x-3">
                                <button type="button" @click="closeModal()"
                                    class="px-5 py-2.5 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-lg transition">
                                    Annuler
                                </button>
                                <button type="submit" :disabled="loading"
                                    class="px-5 py-2.5 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-medium rounded-lg transition disabled:opacity-50">
                                    <span x-show="!loading"
                                        x-text="modalMode === 'create' ? '✅ Créer' : '💾 Enregistrer'"></span>
                                    <span x-show="loading">⏳ Enregistrement...</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Alpine.js Component - DOIT ÊTRE AVANT </x-app-layout> --}}
    @push('scripts')
        <script>
            // Attendre qu'Alpine soit prêt
            window.addEventListener('alpine:init', () => {
                Alpine.data('cabinetManager', () => ({
                    cabinets: @json($cabinets),
                    filteredCabinets: [],
                    search: '',
                    modalOpen: false,
                    modalMode: 'create',
                    loading: false,
                    message: '',
                    messageType: '',
                    errors: [],
                    form: {
                        id: null,
                        nom: '',
                        adresse: '',
                        telephone: '',
                        email: ''
                    },

                    init() {
                        this.filteredCabinets = this.cabinets;
                    },

                    filterCabinets() {
                        const searchTerm = this.search.toLowerCase();
                        this.filteredCabinets = this.cabinets.filter(cabinet =>
                            cabinet.nom.toLowerCase().includes(searchTerm) ||
                            (cabinet.adresse && cabinet.adresse.toLowerCase().includes(searchTerm)) ||
                            (cabinet.telephone && cabinet.telephone.toLowerCase().includes(
                            searchTerm)) ||
                            (cabinet.email && cabinet.email.toLowerCase().includes(searchTerm))
                        );
                    },

                    openModal(mode, cabinet = null) {
                        this.modalMode = mode;
                        this.errors = [];

                        if (mode === 'edit' && cabinet) {
                            this.form = {
                                ...cabinet
                            };
                        } else {
                            this.resetForm();
                        }

                        this.modalOpen = true;
                    },

                    closeModal() {
                        this.modalOpen = false;
                        this.resetForm();
                    },

                    resetForm() {
                        this.form = {
                            id: null,
                            nom: '',
                            adresse: '',
                            telephone: '',
                            email: ''
                        };
                    },

                    async submitForm() {
                        this.loading = true;
                        this.errors = [];

                        const url = this.modalMode === 'create' ?
                            '{{ route('admin.cabinets.store') }}' :
                            `/admin/cabinets/${this.form.id}`;

                        const method = this.modalMode === 'create' ? 'POST' : 'PUT';

                        try {
                            const response = await fetch(url, {
                                method: method,
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector(
                                        'meta[name="csrf-token"]').content,
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify(this.form)
                            });

                            const data = await response.json();

                            if (response.ok) {
                                if (this.modalMode === 'create') {
                                    this.cabinets.push(data.cabinet);
                                } else {
                                    const index = this.cabinets.findIndex(c => c.id === data.cabinet
                                    .id);
                                    if (index !== -1) {
                                        this.cabinets[index] = data.cabinet;
                                    }
                                }

                                this.filterCabinets();
                                this.closeModal();
                                this.showMessage(data.message, 'success');
                            } else {
                                this.errors = Object.values(data.errors || {}).flat();
                            }
                        } catch (error) {
                            this.errors = ['Une erreur est survenue. Veuillez réessayer.'];
                        } finally {
                            this.loading = false;
                        }
                    },

                    async deleteCabinet(id) {
                        if (!confirm('Êtes-vous sûr de vouloir supprimer ce cabinet ?')) {
                            return;
                        }

                        try {
                            const response = await fetch(`/admin/cabinets/${id}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector(
                                        'meta[name="csrf-token"]').content,
                                    'Accept': 'application/json'
                                }
                            });

                            const data = await response.json();

                            if (response.ok) {
                                this.cabinets = this.cabinets.filter(c => c.id !== id);
                                this.filterCabinets();
                                this.showMessage(data.message, 'success');
                            } else {
                                this.showMessage(data.message, 'error');
                            }
                        } catch (error) {
                            this.showMessage('Une erreur est survenue.', 'error');
                        }
                    },

                    showMessage(msg, type) {
                        this.message = msg;
                        this.messageType = type;
                        setTimeout(() => {
                            this.message = '';
                        }, 5000);
                    }
                }));
            });
        </script>
    @endpush
</x-app-layout>
