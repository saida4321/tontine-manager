<div class="space-y-6">
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Configuration SMTP</h3>
        
        @if (session()->has('message'))
            <div class="mb-4 p-4 bg-green-50 text-green-800 rounded-xl">
                {{ session('message') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mb-4 p-4 bg-red-50 text-red-800 rounded-xl">
                {{ session('error') }}
            </div>
        @endif

        <form wire:submit.prevent="saveEmail">
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Serveur SMTP</label>
                        <input 
                            type="text" 
                            wire:model="smtp_host" 
                            placeholder="smtp.gmail.com" 
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-rose-500 focus:border-transparent"
                        >
                        @error('smtp_host') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Port</label>
                        <input 
                            type="number" 
                            wire:model="smtp_port" 
                            placeholder="587" 
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-rose-500 focus:border-transparent"
                        >
                        @error('smtp_port') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Adresse email d'envoi</label>
                    <input 
                        type="email" 
                        wire:model="smtp_email" 
                        placeholder="noreply@tontine.com" 
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-rose-500 focus:border-transparent"
                    >
                    @error('smtp_email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nom d'utilisateur</label>
                        <input 
                            type="text" 
                            wire:model="smtp_username" 
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-rose-500 focus:border-transparent"
                        >
                        @error('smtp_username') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Mot de passe</label>
                        <input 
                            type="password" 
                            wire:model="smtp_password" 
                            placeholder="••••••••"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-rose-500 focus:border-transparent"
                        >
                        @error('smtp_password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Encryption</label>
                    <select 
                        wire:model="smtp_encryption" 
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-rose-500 focus:border-transparent"
                    >
                        <option value="tls">TLS</option>
                        <option value="ssl">SSL</option>
                    </select>
                    @error('smtp_encryption') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="mt-6 flex gap-3 justify-end">
                <button 
                    type="button"
                    wire:click="testEmail"
                    wire:loading.attr="disabled"
                    class="px-6 py-3 bg-white border border-rose-600 text-rose-600 rounded-full font-semibold hover:bg-rose-50 transition-colors disabled:opacity-50"
                >
                    <span wire:loading.remove wire:target="testEmail">Tester la connexion</span>
                    <span wire:loading wire:target="testEmail">Test en cours...</span>
                </button>
                <button 
                    type="submit"
                    wire:loading.attr="disabled"
                    class="px-6 py-3 bg-rose-600 text-white rounded-full font-semibold hover:bg-rose-700 transition-colors disabled:opacity-50"
                >
                    <span wire:loading.remove wire:target="saveEmail">Enregistrer</span>
                    <span wire:loading wire:target="saveEmail">Enregistrement...</span>
                </button>
            </div>
        </form>
    </div>
</div>