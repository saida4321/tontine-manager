<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mot de passe oublié - Tontine Manager</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    
    <!-- Background avec image (TOUTE la page) -->
    <div class="min-h-screen flex items-center justify-center relative py-8 px-4">
        
        <!-- Image de fond avec overlay -->
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1579621970563-ebec7560ff3e?q=80&w=2071&auto=format&fit=crop" 
                 alt="Background" 
                 class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-900/70 via-purple-900/70 to-pink-900/70"></div>
        </div>

        <!-- Card COMPACTE -->
        <div class="relative z-10 w-full max-w-2xl bg-white/95 backdrop-blur-sm rounded-2xl shadow-2xl overflow-hidden flex">
            
            <!-- PARTIE GAUCHE : Formulaire -->
            <div class="w-full md:w-1/2 p-6">
                
                <!-- Logo avec initiale G (violet plus doux) -->
                <div class="text-center mb-4">
                    <div class="flex justify-center mb-2">
                        <div class="bg-gradient-to-br from-purple-400 to-purple-600 text-white rounded-full w-16 h-16 flex items-center justify-center text-3xl font-bold shadow-lg">
                            G
                        </div>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900 mb-1">
                        Mot de passe oublié
                    </h2>
                    <p class="text-xs text-gray-600 leading-relaxed px-2">
                        Vous avez oublié votre mot de passe ?<br>
                        Aucun problème. Indiquez-nous<br>
                        simplement votre adresse e-mail et nous<br>
                        vous enverrons un lien de réinitialisation de<br>
                        mot de passe qui vous permettra d'en<br>
                        choisir un nouveau.
                    </p>
                </div>

                <!-- Message de session -->
                @if (session('status'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-3 py-2 rounded-full text-xs mb-3">
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Formulaire -->
                <form method="POST" action="{{ route('password.email') }}" class="space-y-3">
                    @csrf

                    <!-- Email avec bordure fine -->
                    <div>
                        <label for="email" class="block text-xs font-medium text-gray-700 mb-1">
                            Adresse Email
                        </label>
                        <input id="email" 
                               type="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required 
                               autofocus
                               class="w-full px-4 py-2.5 text-sm bg-white border border-gray-300 rounded-full focus:ring-2 focus:ring-purple-300 focus:border-purple-400 transition-all @error('email') border-red-500 @enderror"
                               placeholder="votre@email.com">
                        @error('email')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Bouton Réinitialiser (violet plus doux) -->
                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white font-semibold py-2.5 px-4 rounded-full shadow-lg transform transition hover:scale-[1.02] text-sm">
                        Réinitialiser
                    </button>
                </form>

                <!-- Lien retour connexion -->
                <div class="text-center mt-3">
                    <a href="{{ route('login') }}" 
                       class="text-xs text-gray-600 hover:text-purple-600 transition underline">
                        Se connecter
                    </a>
                </div>

            </div>

            <!-- PARTIE DROITE : Image + 2 Boutons -->
            <div class="hidden md:flex md:w-1/2 relative items-center justify-center">
                
                <!-- Image de fond (pot de pièces avec plante) -->
                <div class="absolute inset-0">
                    <img src="https://images.unsplash.com/photo-1579621970795-87facc2f976d?q=80&w=1000&auto=format&fit=crop" 
                         alt="Épargne croissance" 
                         class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-br from-black/10 via-transparent to-black/10"></div>
                </div>

                <!-- 2 Boutons RONDS -->
                <div class="relative z-10 space-y-3 px-8 w-full">
                    
                    <!-- Bouton Email -->
                    <a href="{{ route('login') }}" 
                       class="flex items-center justify-center gap-2 bg-white/95 backdrop-blur-sm hover:bg-white text-gray-700 font-medium py-2.5 px-4 rounded-full shadow-lg transition transform hover:scale-105 border border-gray-200 text-sm">
                        <svg class="w-4 h-4 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                        </svg>
                        <span>Se connecter avec Email</span>
                    </a>

                    <!-- Bouton Google -->
                    <button type="button" 
                            class="flex items-center justify-center gap-2 bg-white/95 backdrop-blur-sm hover:bg-white text-gray-700 font-medium py-2.5 px-4 rounded-full shadow-lg transition transform hover:scale-105 border border-gray-200 text-sm">
                        <svg class="w-4 h-4" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M5.26620003,9.76452941 C6.19878754,6.93863203 8.85444915,4.90909091 12,4.90909091 C13.6909091,4.90909091 15.2181818,5.50909091 16.4181818,6.49090909 L19.9090909,3 C17.7818182,1.14545455 15.0545455,0 12,0 C7.27006974,0 3.1977497,2.69829785 1.23999023,6.65002441 L5.26620003,9.76452941 Z"/>
                            <path fill="#34A853" d="M16.0407269,18.0125889 C14.9509167,18.7163016 13.5660892,19.0909091 12,19.0909091 C8.86648613,19.0909091 6.21911939,17.076871 5.27698177,14.2678769 L1.23746264,17.3349879 C3.19279051,21.2936293 7.26500293,24 12,24 C14.9328362,24 17.7353462,22.9573905 19.834192,20.9995801 L16.0407269,18.0125889 Z"/>
                            <path fill="#4285F4" d="M19.834192,20.9995801 C22.0291676,18.9520994 23.4545455,15.903663 23.4545455,12 C23.4545455,11.2909091 23.3454545,10.5818182 23.1818182,9.90909091 L12,9.90909091 L12,14.4545455 L18.4363636,14.4545455 C18.1187732,16.013626 17.2662994,17.2212117 16.0407269,18.0125889 L19.834192,20.9995801 Z"/>
                            <path fill="#FBBC05" d="M5.27698177,14.2678769 C5.03832634,13.556323 4.90909091,12.7937589 4.90909091,12 C4.90909091,11.2182781 5.03443647,10.4668121 5.26620003,9.76452941 L1.23999023,6.65002441 C0.43658717,8.26043162 0,10.0753848 0,12 C0,13.9195484 0.444780743,15.7301709 1.23746264,17.3349879 L5.27698177,14.2678769 Z"/>
                        </svg>
                        <span>Se connecter avec Google</span>
                    </button>

                </div>

            </div>

        </div>
    </div>
</body>
</html>