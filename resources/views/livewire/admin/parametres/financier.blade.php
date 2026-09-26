<div class="space-y-6">
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Paramètres financiers</h3>
        
        @if (session()->has('message'))
            <div class="mb-4 p-4 bg-green-50 text-green-800 rounded-xl">
                {{ session('message') }}
            </div>
        @endif

        <form wire:submit.prevent="saveFinancier">
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Montant minimum cotisation</label>
                        <div class="relative">
                            <input type="number" wire:model="montant_min_cotisation" placeholder="5000" step="0.01" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                            <span class="absolute right-4 top-3 text-gray-500 font-semibold">FCFA</span>
                        </div>
                        @error('montant_min_cotisation') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Montant minimum retrait</label>
                        <div class="relative">
                            <input type="number" wire:model="montant_min_retrait" placeholder="10000" step="0.01" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                            <span class="absolute right-4 top-3 text-gray-500 font-semibold">FCFA</span>
                        </div>
                        @error('montant_min_retrait') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Commission collecteur (%)</label>
                    <input type="number" wire:model="commission_collecteur" placeholder="5" step="0.01" min="0" max="100" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                    @error('commission_collecteur') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    <p class="text-xs text-gray-500 mt-1">Pourcentage prélevé sur chaque cotisation collectée</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Devise</label>
                    <select class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-transparent bg-gray-50" disabled>
                        <option>FCFA (Franc CFA)</option>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Devise fixe pour le marché ouest-africain</p>
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit" class="px-6 py-3 bg-emerald-600 text-white rounded-full font-semibold hover:bg-emerald-700 transition-colors" wire:loading.attr="disabled">
                    <span wire:loading.remove>Enregistrer les modifications</span>
                    <span wire:loading>Enregistrement...</span>
                </button>
            </div>
        </form>
    </div>
</div>