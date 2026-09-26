<div class="space-y-6">
    {{-- Thème --}}
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Thème</h3>
        <div class="grid grid-cols-3 gap-4">
            <button wire:click="$set('theme', 'clair')" class="p-6 rounded-2xl border-2 transition-all {{ $theme === 'clair' ? 'border-purple-600 bg-purple-50' : 'border-gray-200 hover:border-purple-300' }}">
                <div class="text-4xl mb-2">☀️</div>
                <p class="font-semibold text-gray-900">Clair</p>
            </button>

            <button wire:click="$set('theme', 'sombre')" class="p-6 rounded-2xl border-2 transition-all {{ $theme === 'sombre' ? 'border-purple-600 bg-purple-50' : 'border-gray-200 hover:border-purple-300' }}">
                <div class="text-4xl mb-2">🌙</div>
                <p class="font-semibold text-gray-900">Sombre</p>
            </button>

            <button wire:click="$set('theme', 'auto')" class="p-6 rounded-2xl border-2 transition-all {{ $theme === 'auto' ? 'border-purple-600 bg-purple-50' : 'border-gray-200 hover:border-purple-300' }}">
                <div class="text-4xl mb-2">🌓</div>
                <p class="font-semibold text-gray-900">Auto</p>
            </button>
        </div>
    </div>

    {{-- Langue --}}
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Langue</h3>
        <select wire:model="langue" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-purple-500 focus:border-transparent">
            <option value="fr">🇫🇷 Français</option>
            <option value="en">🇬🇧 English</option>
            <option value="es">🇪🇸 Español</option>
        </select>
    </div>

    {{-- Couleur principale --}}
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Couleur principale</h3>
        <div class="grid grid-cols-8 gap-3">
            <button class="w-12 h-12 rounded-xl bg-violet-600 hover:scale-110 transition-transform"></button>
            <button class="w-12 h-12 rounded-xl bg-purple-600 hover:scale-110 transition-transform"></button>
            <button class="w-12 h-12 rounded-xl bg-blue-600 hover:scale-110 transition-transform"></button>
            <button class="w-12 h-12 rounded-xl bg-emerald-600 hover:scale-110 transition-transform"></button>
            <button class="w-12 h-12 rounded-xl bg-amber-600 hover:scale-110 transition-transform"></button>
            <button class="w-12 h-12 rounded-xl bg-rose-600 hover:scale-110 transition-transform"></button>
            <button class="w-12 h-12 rounded-xl bg-red-600 hover:scale-110 transition-transform"></button>
            <button class="w-12 h-12 rounded-xl bg-cyan-600 hover:scale-110 transition-transform"></button>
        </div>
    </div>
</div>