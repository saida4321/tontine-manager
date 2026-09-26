<div class="space-y-6">
    {{-- Informations personnelles --}}
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Informations personnelles</h3>
        
        @if (session()->has('message'))
            <div class="mb-4 p-4 bg-green-50 text-green-800 rounded-xl">
                {{ session('message') }}
            </div>
        @endif

        <div class="flex items-center gap-6 mb-6 pb-6 border-b border-gray-100">
            <div class="w-24 h-24 bg-blue-600 rounded-full flex items-center justify-center text-white text-3xl font-bold">
                {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
            </div>
            <div>
                <h4 class="text-xl font-bold text-gray-900">{{ Auth::user()->name }}</h4>
                <p class="text-gray-500">Administrateur</p>
            </div>
        </div>

        <form wire:submit.prevent="saveProfil">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nom complet</label>
                    <input type="text" wire:model="nom_user" placeholder="ADMIN Système" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('nom_user') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                    <input type="email" wire:model="email_user" placeholder="admin@tontine.com" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('email_user') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-full font-semibold hover:bg-blue-700 transition-colors" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="saveProfil">Enregistrer</span>
                    <span wire:loading wire:target="saveProfil">Enregistrement...</span>
                </button>
            </div>
        </form>
    </div>

    {{-- Changer mot de passe --}}
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Changer le mot de passe</h3>
        
        @if (session()->has('error'))
            <div class="mb-4 p-4 bg-red-50 text-red-800 rounded-xl">
                {{ session('error') }}
            </div>
        @endif

        <form wire:submit.prevent="changePassword">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Mot de passe actuel</label>
                    <input type="password" wire:model="password_actuel" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('password_actuel') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nouveau mot de passe</label>
                    <input type="password" wire:model="nouveau_password" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('nouveau_password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    <p class="text-xs text-gray-500 mt-1">Minimum 8 caractères</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Confirmer le mot de passe</label>
                    <input type="password" wire:model="confirmation_password" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('confirmation_password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-full font-semibold hover:bg-blue-700 transition-colors" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="changePassword">Changer le mot de passe</span>
                    <span wire:loading wire:target="changePassword">Changement...</span>
                </button>
            </div>
        </form>
    </div>
</div>