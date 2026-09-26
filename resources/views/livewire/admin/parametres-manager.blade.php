<div class="min-h-screen bg-gray-50">
    @php
        $currentSection = collect($sections)->firstWhere('id', $ongletActif);
        $gradientClass = match($currentSection['color'] ?? 'violet') {
            'violet' => 'from-violet-600 to-violet-700',
            'emerald' => 'from-emerald-600 to-emerald-700',
            'rose' => 'from-rose-600 to-rose-700',
            'amber' => 'from-amber-600 to-amber-700',
            'purple' => 'from-purple-600 to-purple-700',
            'cyan' => 'from-cyan-600 to-cyan-700',
            'red' => 'from-red-600 to-red-700',
            default => 'from-violet-600 to-violet-700'
        };
    @endphp

    {{-- Header avec dégradé dynamique --}}
    <div class="bg-gradient-to-br {{ $gradientClass }} text-white px-6 py-12">
        <div class="max-w-6xl mx-auto">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center text-3xl">
                    {{ $currentSection['icon'] ?? '🏢' }}
                </div>
                <div>
                    <h1 class="text-3xl font-bold">{{ $currentSection['title'] ?? 'Entreprise' }}</h1>
                    <p class="text-white/80 text-sm mt-1">{{ $currentSection['desc'] ?? '' }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Navigation + Contenu --}}
    <div class="max-w-6xl mx-auto px-6 -mt-6">
        {{-- Grille de navigation --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            @foreach($sections as $section)
                @php
                    $ringClass = match($section['color']) {
                        'violet' => 'ring-violet-500',
                        'emerald' => 'ring-emerald-500',
                        'rose' => 'ring-rose-500',
                        'amber' => 'ring-amber-500',
                        'purple' => 'ring-purple-500',
                        'cyan' => 'ring-cyan-500',
                        'red' => 'ring-red-500',
                        default => 'ring-violet-500'
                    };
                @endphp
                
                <button
                    wire:click="$set('ongletActif', '{{ $section['id'] }}')"
                    wire:loading.attr="disabled"
                    class="bg-white rounded-2xl p-4 text-left transition-all duration-200 {{ $ongletActif === $section['id'] ? 'ring-2 ' . $ringClass . ' shadow-lg scale-[1.02]' : 'shadow-sm hover:shadow-md' }} disabled:opacity-50"
                >
                    <div class="text-3xl mb-2">{{ $section['icon'] }}</div>
                    <p class="font-semibold text-gray-900 text-sm">{{ $section['title'] }}</p>
                </button>
            @endforeach
        </div>

        {{-- Contenu dynamique avec lazy loading --}}
        <div class="pb-12" wire:loading.class="opacity-50">
            @if($ongletActif === 'entreprise')
                @include('livewire.admin.parametres.entreprise')
            @elseif($ongletActif === 'financier')
                @include('livewire.admin.parametres.financier')
            @elseif($ongletActif === 'email')
                @include('livewire.admin.parametres.email')
            @elseif($ongletActif === 'notifications')
                @include('livewire.admin.parametres.notifications')
            @elseif($ongletActif === 'apparence')
                @include('livewire.admin.parametres.apparence')
            @elseif($ongletActif === 'export')
                @include('livewire.admin.parametres.export')
            @elseif($ongletActif === 'securite')
                @include('livewire.admin.parametres.securite')
            @endif
        </div>
    </div>
</div>