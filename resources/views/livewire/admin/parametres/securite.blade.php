<div class="space-y-6">
    {{-- Authentification --}}
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Authentification et sécurité</h3>
        
        @if (session()->has('message'))
            <div class="mb-4 p-4 bg-green-50 text-green-800 rounded-xl">
                {{ session('message') }}
            </div>
        @endif

        <div class="space-y-4">
            {{-- 2FA --}}
            <div class="flex items-center justify-between py-3 border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">📱</span>
                    <div>
                        <p class="font-semibold text-gray-900">Authentification à deux facteurs</p>
                        <p class="text-sm text-gray-500">Sécurité renforcée pour votre compte</p>
                    </div>
                </div>
                <button wire:click="toggleTwoFactor" class="px-4 py-2 rounded-full text-sm font-semibold transition-colors {{ $two_factor_enabled ? 'bg-green-100 text-green-700' : 'bg-red-600 text-white hover:bg-red-700' }}">
                    {{ $two_factor_enabled ? '✓ Activé' : 'Activer' }}
                </button>
            </div>

            {{-- Sessions actives --}}
            <div class="flex items-center justify-between py-3 border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">💻</span>
                    <div>
                        <p class="font-semibold text-gray-900">Sessions actives</p>
                        <p class="text-sm text-gray-500">3 appareils connectés</p>
                    </div>
                </div>
                <button class="text-red-600 font-semibold text-sm hover:text-red-700">
                    Gérer
                </button>
            </div>

            {{-- HTTPS --}}
            <div class="flex items-center justify-between py-3">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">🔐</span>
                    <div>
                        <p class="font-semibold text-gray-900">Connexion sécurisée HTTPS</p>
                        <p class="text-sm text-green-600 font-medium">✓ Activé</p>
                    </div>
                </div>
                <button wire:click="toggleHttps" class="w-12 h-6 rounded-full transition-colors relative {{ $https_enabled ? 'bg-green-500' : 'bg-gray-300' }}">
                    <div class="absolute top-1 w-4 h-4 bg-white rounded-full transition-transform {{ $https_enabled ? 'right-1' : 'left-1' }}"></div>
                </button>
            </div>
        </div>
    </div>

    {{-- Historique connexion --}}
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Historique de connexion</h3>
        <div class="space-y-3">
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                <div class="flex items-center gap-3">
                    <div class="text-2xl">💻</div>
                    <div>
                        <p class="font-semibold text-gray-900">Chrome sur Windows</p>
                        <p class="text-sm text-gray-500">Lomé, Togo • Il y a 2 minutes</p>
                    </div>
                </div>
                <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">
                    Actuelle
                </span>
            </div>

            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                <div class="flex items-center gap-3">
                    <div class="text-2xl">💻</div>
                    <div>
                        <p class="font-semibold text-gray-900">Safari sur iPhone</p>
                        <p class="text-sm text-gray-500">Lomé, Togo • Il y a 3 heures</p>
                    </div>
                </div>
                <button class="text-red-600 text-sm font-semibold hover:text-red-700">
                    Déconnecter
                </button>
            </div>

            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                <div class="flex items-center gap-3">
                    <div class="text-2xl">💻</div>
                    <div>
                        <p class="font-semibold text-gray-900">Firefox sur Mac</p>
                        <p class="text-sm text-gray-500">Accra, Ghana • Il y a 2 jours</p>
                    </div>
                </div>
                <button class="text-red-600 text-sm font-semibold hover:text-red-700">
                    Déconnecter
                </button>
            </div>
        </div>
    </div>
</div>