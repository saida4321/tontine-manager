<div class="space-y-6">
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Préférences de notifications</h3>
        
        <div class="space-y-4">
            {{-- Nouvelles cotisations --}}
            <div class="flex items-center justify-between py-3 border-b border-gray-100">
                <div>
                    <p class="font-semibold text-gray-900">Nouvelles cotisations</p>
                    <p class="text-sm text-gray-500">Recevoir une alerte pour chaque cotisation</p>
                </div>
                <button wire:click="$toggle('notif_cotisations')" class="w-12 h-6 rounded-full transition-colors relative {{ $notif_cotisations ? 'bg-amber-500' : 'bg-gray-300' }}">
                    <div class="absolute top-1 w-4 h-4 bg-white rounded-full transition-transform {{ $notif_cotisations ? 'right-1' : 'left-1' }}"></div>
                </button>
            </div>

            {{-- Demandes de retrait --}}
            <div class="flex items-center justify-between py-3 border-b border-gray-100">
                <div>
                    <p class="font-semibold text-gray-900">Demandes de retrait</p>
                    <p class="text-sm text-gray-500">Notification des nouvelles demandes</p>
                </div>
                <button wire:click="$toggle('notif_retraits')" class="w-12 h-6 rounded-full transition-colors relative {{ $notif_retraits ? 'bg-amber-500' : 'bg-gray-300' }}">
                    <div class="absolute top-1 w-4 h-4 bg-white rounded-full transition-transform {{ $notif_retraits ? 'right-1' : 'left-1' }}"></div>
                </button>
            </div>

            {{-- Retraits approuvés --}}
            <div class="flex items-center justify-between py-3 border-b border-gray-100">
                <div>
                    <p class="font-semibold text-gray-900">Retraits approuvés</p>
                    <p class="text-sm text-gray-500">Confirmation des retraits validés</p>
                </div>
                <button wire:click="$toggle('notif_retraits_approuves')" class="w-12 h-6 rounded-full transition-colors relative {{ $notif_retraits_approuves ? 'bg-amber-500' : 'bg-gray-300' }}">
                    <div class="absolute top-1 w-4 h-4 bg-white rounded-full transition-transform {{ $notif_retraits_approuves ? 'right-1' : 'left-1' }}"></div>
                </button>
            </div>

            {{-- Nouveaux clients --}}
            <div class="flex items-center justify-between py-3 border-b border-gray-100">
                <div>
                    <p class="font-semibold text-gray-900">Nouveaux clients</p>
                    <p class="text-sm text-gray-500">Alerte lors de l'inscription d'un client</p>
                </div>
                <button wire:click="$toggle('notif_clients')" class="w-12 h-6 rounded-full transition-colors relative {{ $notif_clients ? 'bg-amber-500' : 'bg-gray-300' }}">
                    <div class="absolute top-1 w-4 h-4 bg-white rounded-full transition-transform {{ $notif_clients ? 'right-1' : 'left-1' }}"></div>
                </button>
            </div>

            {{-- Rapports hebdomadaires --}}
            <div class="flex items-center justify-between py-3 border-b border-gray-100">
                <div>
                    <p class="font-semibold text-gray-900">Rapports hebdomadaires</p>
                    <p class="text-sm text-gray-500">Résumé envoyé chaque lundi</p>
                </div>
                <button wire:click="$toggle('notif_rapports')" class="w-12 h-6 rounded-full transition-colors relative {{ $notif_rapports ? 'bg-amber-500' : 'bg-gray-300' }}">
                    <div class="absolute top-1 w-4 h-4 bg-white rounded-full transition-transform {{ $notif_rapports ? 'right-1' : 'left-1' }}"></div>
                </button>
            </div>

            {{-- Alertes de sécurité --}}
            <div class="flex items-center justify-between py-3">
                <div>
                    <p class="font-semibold text-gray-900">Alertes de sécurité</p>
                    <p class="text-sm text-gray-500">Connexions suspectes et tentatives</p>
                </div>
                <button wire:click="$toggle('notif_securite')" class="w-12 h-6 rounded-full transition-colors relative {{ $notif_securite ? 'bg-amber-500' : 'bg-gray-300' }}">
                    <div class="absolute top-1 w-4 h-4 bg-white rounded-full transition-transform {{ $notif_securite ? 'right-1' : 'left-1' }}"></div>
                </button>
            </div>
        </div>
    </div>
</div>