<!-- SIDEBAR VIOLET PROFESSIONNEL - COHÉRENT AVEC LOGIN -->
<aside id="sidebar"
    class="fixed inset-y-0 left-0 z-50 w-64 bg-gradient-to-b from-violet-950 to-slate-900 text-white transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out shadow-2xl flex flex-col">

    <!-- Logo -->
    <div class="flex items-center gap-3 px-6 py-5 border-b border-violet-800/30 flex-shrink-0">
        <div
            class="bg-gradient-to-br from-violet-500 to-violet-600 rounded-xl w-10 h-10 flex items-center justify-center text-2xl shadow-lg">
            💰
        </div>
        <div>
            <livewire:header-entreprise />
            <p class="text-xs text-violet-200">Gestion Professionnelle</p>
        </div>
    </div>

    <!-- Navigation (scrollable) -->
    <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">

        @php
            use App\Types\Role;
            $userRole = auth()->user()->role;
        @endphp

        <!-- MENU ADMINISTRATEUR -->
        @if ($userRole === Role::Admin)
            <a href="{{ route('admin.dashboard') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200
                      {{ request()->routeIs('admin.dashboard')
                          ? 'bg-violet-500 text-white shadow-lg shadow-violet-500/50'
                          : 'text-violet-200 hover:bg-violet-900/50 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span class="font-medium">Dashboard</span>
            </a>

            <a href="{{ route('admin.cabinets.index') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200
                      {{ request()->routeIs('admin.cabinets.*')
                          ? 'bg-violet-500 text-white shadow-lg shadow-violet-500/50'
                          : 'text-violet-200 hover:bg-violet-900/50 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                <span class="font-medium">Cabinets</span>
            </a>

            <a href="{{ route('admin.users.index') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200
                      {{ request()->routeIs('admin.users.*')
                          ? 'bg-violet-500 text-white shadow-lg shadow-violet-500/50'
                          : 'text-violet-200 hover:bg-violet-900/50 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <span class="font-medium">Utilisateurs</span>
            </a>

            <a href="{{ route('admin.clients.index') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200
                      {{ request()->routeIs('admin.clients.*')
                          ? 'bg-violet-500 text-white shadow-lg shadow-violet-500/50'
                          : 'text-violet-200 hover:bg-violet-900/50 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span class="font-medium">Clients</span>
            </a>

            <a href="{{ route('admin.cotisations.index') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200
                      {{ request()->routeIs('admin.cotisations.*')
                          ? 'bg-violet-500 text-white shadow-lg shadow-violet-500/50'
                          : 'text-violet-200 hover:bg-violet-900/50 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span class="font-medium">Cotisations</span>
            </a>

            <a href="{{ route('admin.retraits.index') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200
                      {{ request()->routeIs('admin.retraits.*')
                          ? 'bg-violet-500 text-white shadow-lg shadow-violet-500/50'
                          : 'text-violet-200 hover:bg-violet-900/50 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="font-medium">Retraits</span>
            </a>

            <a href="{{ route('admin.rapports.index') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200
          {{ request()->routeIs('admin.rapports.*')
              ? 'bg-violet-500 text-white shadow-lg shadow-violet-500/50'
              : 'text-violet-200 hover:bg-violet-900/50 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                <span class="font-medium">Rapports</span>
            </a>

            <a href="{{ route('admin.parametres.index') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 text-violet-200 hover:bg-violet-900/50 hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span class="font-medium">Paramètres</span>
            </a>
        @endif

    </nav>

    <!-- User Profile (TOUJOURS VISIBLE EN BAS) -->
    <div class="px-4 py-4 border-t border-violet-800/30 bg-violet-950/50 flex-shrink-0">
        <div class="flex items-center gap-3">
            <!-- Profile cliquable -->
            <a href="{{ route('profile.edit') }}"
                class="flex items-center gap-3 flex-1 hover:bg-violet-900/50 p-2 rounded-lg transition">
                <div
                    class="w-10 h-10 rounded-full bg-gradient-to-br from-violet-500 to-violet-600 flex items-center justify-center font-bold text-sm shadow-lg">
                    {{ strtoupper(substr(auth()->user()->nom, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-white truncate">{{ auth()->user()->nom }}</p>
                    <p class="text-xs text-violet-300 truncate">{{ auth()->user()->role_name }}</p>
                </div>
            </a>

            <!-- Bouton déconnexion -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="text-violet-300 hover:text-white transition p-2 hover:bg-violet-900/50 rounded-lg"
                    title="Déconnexion">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                </button>
            </form>
        </div>
    </div>

</aside>

<!-- Overlay pour mobile -->
<div id="sidebarOverlay" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-40 hidden lg:hidden"></div>

<!-- Bouton burger pour mobile -->
<button id="sidebarToggle"
    class="lg:hidden fixed top-4 left-4 z-50 p-2 bg-gradient-to-br from-violet-500 to-violet-600 text-white rounded-lg shadow-lg hover:shadow-violet-500/50 transition">
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
    </svg>
</button>

<!-- Script pour toggle sidebar sur mobile -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', () => {
                sidebar.classList.toggle('-translate-x-full');
                sidebarOverlay.classList.toggle('hidden');
            });
        }

        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', () => {
                sidebar.classList.add('-translate-x-full');
                sidebarOverlay.classList.add('hidden');
            });
        }
    });
</script>
