<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Connexion - {{ param_entreprise('nom') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Animation au focus des inputs */
        .input-animated {
            transition: all 0.3s ease;
        }
        .input-animated:focus {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(139, 92, 246, 0.2);
        }
    </style>
</head>
<body class="font-sans antialiased">
    <!-- Background avec image d'argent -->
    <div class="min-h-screen flex items-center justify-center relative py-8 px-4">
        
        <!-- Image de fond avec overlay -->
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1579621970563-ebec7560ff3e?q=80&w=2071&auto=format&fit=crop" 
                 alt="Background" 
                 class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-900/70 via-purple-900/70 to-pink-900/70"></div>
        </div>

        <!-- Card de connexion COMPACTE -->
        <div class="relative z-10 w-full max-w-2xl bg-white/95 backdrop-blur-sm rounded-2xl shadow-2xl overflow-hidden flex">
            
            <!-- PARTIE GAUCHE : Formulaire -->
            <div class="w-full md:w-1/2 p-6">
                
                <!-- Logo et titre -->
                <div class="text-center mb-4">
                    <div class="flex justify-center mb-2">
                        <div class="bg-gradient-to-br from-blue-500 to-purple-600 text-white rounded-full w-12 h-12 flex items-center justify-center text-xl shadow-lg">
                            💰
                        </div>
                    </div>
                    <h2 class="text-lg font-bold text-gray-900 mb-0.5">
                        {{ param_entreprise('nom') }}
                    </h2>
                    <p class="text-xs text-gray-600">
                        Système de gestion professionnel
                    </p>
                </div>

                <!-- Formulaire de connexion -->
                <form method="POST" action="{{ route('login') }}" class="space-y-3">
                    @csrf

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-xs font-medium text-gray-700 mb-1">
                            Adresse email
                        </label>
                        <input id="email" 
                               type="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required 
                               autofocus 
                               autocomplete="username"
                               class="input-animated w-full px-4 py-2.5 text-sm bg-gray-50 border-2 border-gray-300 rounded-full focus:ring-2 focus:ring-purple-300 focus:border-purple-400 focus:bg-white transition-all @error('email') border-red-500 @enderror"
                               placeholder="votre@email.com">
                        @error('email')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-xs font-medium text-gray-700 mb-1">
                            Mot de passe
                        </label>
                        <input id="password" 
                               type="password" 
                               name="password" 
                               required 
                               autocomplete="current-password"
                               class="input-animated w-full px-4 py-2.5 text-sm bg-gray-50 border-2 border-gray-300 rounded-full focus:ring-2 focus:ring-purple-300 focus:border-purple-400 focus:bg-white transition-all @error('password') border-red-500 @enderror"
                               placeholder="••••••••">
                        @error('password')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Mot de passe oublié (centré) -->
                    <div class="text-center">
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" 
                               class="text-xs text-gray-600 hover:text-purple-600 transition">
                                Mot de passe oublié ?
                            </a>
                        @endif
                    </div>

                    <!-- Bouton de connexion (violet doux) -->
                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white font-semibold py-2.5 px-4 rounded-full shadow-lg transform transition hover:scale-[1.02] hover:shadow-xl text-sm">
                        🔐 Se connecter
                    </button>
                </form>

                <!-- Footer -->
                <div class="text-center text-xs text-gray-500 mt-4">
                    <p>© 2025 {{ param_entreprise('nom') }}</p>
                    <p class="mt-0.5">Développé par <strong class="text-gray-700">KOLI Saïdatou-Agbandjala</strong></p>
                </div>
            </div>

            <!-- PARTIE DROITE : Image pot de pièces avec plante -->
            <div class="hidden md:block md:w-1/2 relative">
                <img src="https://images.unsplash.com/photo-1579621970795-87facc2f976d?q=80&w=1000&auto=format&fit=crop" 
                     alt="Épargne croissance" 
                     class="absolute inset-0 w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-600/10 to-purple-600/10"></div>
            </div>

        </div>
    </div>
</body>
</html>