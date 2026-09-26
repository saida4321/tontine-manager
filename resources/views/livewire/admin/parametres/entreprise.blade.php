<div class="space-y-6">
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Informations générales</h3>
        
        @if (session()->has('message'))
            <div class="mb-4 p-4 bg-green-50 text-green-800 rounded-xl">
                {{ session('message') }}
            </div>
        @endif

        <div class="space-y-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nom de l'entreprise</label>
                <input type="text" wire:model="nom_entreprise" placeholder="Tontine Manager" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-violet-500 focus:border-transparent">
                @error('nom_entreprise') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Logo</label>
                <div class="flex items-center gap-4">
                    <div class="w-20 h-20 bg-violet-100 rounded-2xl flex items-center justify-center text-3xl">
                        🏢
                    </div>
                    <button class="px-4 py-2 bg-violet-600 text-white rounded-full text-sm font-semibold hover:bg-violet-700 transition-colors">
                        Changer le logo
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Téléphone</label>
                    <input type="tel" wire:model="telephone_entreprise" placeholder="+228 XX XX XX XX" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-violet-500 focus:border-transparent">
                    @error('telephone_entreprise') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                    <input type="email" wire:model="email_entreprise" placeholder="contact@tontine.com" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-violet-500 focus:border-transparent">
                    @error('email_entreprise') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Adresse complète</label>
                <textarea wire:model="adresse_entreprise" placeholder="Lomé, Togo" rows="3" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-violet-500 focus:border-transparent"></textarea>
                @error('adresse_entreprise') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="mt-6 flex justify-end">
            <button wire:click="saveEntreprise" class="px-6 py-3 bg-violet-600 text-white rounded-full font-semibold hover:bg-violet-700 transition-colors">
                Enregistrer les modifications
            </button>
        </div>
    </div>
</div>