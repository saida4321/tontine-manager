<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tontine Manager - Accueil</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-blue-500 via-purple-500 to-pink-500 min-h-screen flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-2xl p-12 max-w-md w-full">
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-gray-800 mb-2">💰 Tontine Manager</h1>
            <p class="text-gray-600">Système de gestion de tontine professionnel</p>
        </div>

        <div class="space-y-4">
            @auth
                <a href="{{ route('dashboard') }}" 
                   class="block w-full bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold py-3 px-6 rounded-lg text-center transition transform hover:scale-105 shadow-lg">
                    🏠 Accéder au Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" 
                   class="block w-full bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold py-3 px-6 rounded-lg text-center transition transform hover:scale-105 shadow-lg">
                    🔐 Se connecter
                </a>
                
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" 
                       class="block w-full bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white font-bold py-3 px-6 rounded-lg text-center transition transform hover:scale-105 shadow-lg">
                        📝 S'inscrire
                    </a>
                @endif
            @endauth
        </div>

        <div class="mt-8 text-center text-sm text-gray-500">
            <p>© 2025 Tontine Manager</p>
            <p class="mt-1">Développé par <strong>KOLI Saïdatou-Agbandjala</strong></p>
        </div>
    </div>
</body>
</html>