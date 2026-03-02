<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion - anemos</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;900&family=Playfair+Display:wght@700;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        * { font-family: 'Inter', sans-serif; }
        .font-display { font-family: 'Playfair Display', serif; }
    </style>
</head>
<body class="bg-gray-50">
<div class="min-h-screen flex">

    <!-- Section gauche - Formulaire -->
    <div class="flex-1 flex items-center justify-center px-6 py-12">
        <div class="w-full max-w-md">
            <!-- Logo -->
            <a href="/" class="flex items-center gap-3 mb-12">
                <div class="w-12 h-12 rounded-xl overflow-hidden flex items-center justify-center group-hover:scale-110 transition-transform shadow-lg">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo Anemos" class="w-full h-full object-cover">
                </div>
                <div>
                    <span class="text-gray-900 font-black text-2xl">ANE</span>
                    <span class="text-violet-600 font-black text-2xl">MOS</span>
                </div>
            </a>

            <!-- Titre -->
            <div class="mb-10">
                <h1 class="font-display text-4xl font-black text-gray-900 mb-3">
                    Bon retour !
                </h1>
                <p class="text-gray-600 text-lg">
                    Connectez-vous pour continuer votre aventure trail.
                </p>
            </div>

            <!-- Erreurs -->
            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-600 text-red-800 px-4 py-3 rounded mb-6">
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Formulaire -->
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-sm font-bold text-gray-900 mb-2">Email</label>
                    <input type="email"
                           name="email"
                           value="{{ old('email') }}"
                           required
                           autofocus
                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-violet-600 focus:ring-2 focus:ring-violet-100 outline-none transition-all text-gray-900 font-medium"
                           placeholder="votre@email.com">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-900 mb-2">Mot de passe</label>
                    <input type="password"
                           name="password"
                           required
                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-violet-600 focus:ring-2 focus:ring-violet-100 outline-none transition-all text-gray-900 font-medium"
                           placeholder="••••••••">
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center">
                        <input type="checkbox"
                               name="remember"
                               class="w-5 h-5 text-violet-600 border-gray-300 rounded focus:ring-violet-600">
                        <span class="ml-2 text-sm font-semibold text-gray-700">Se souvenir de moi</span>
                    </label>
                    <a href="#" class="text-sm font-bold text-violet-600 hover:text-violet-700">
                        Mot de passe oublié ?
                    </a>
                </div>

                <button type="submit"
                        class="w-full bg-violet-600 hover:bg-violet-700 text-white font-bold py-4 rounded-xl transition-all transform hover:scale-105 shadow-lg uppercase tracking-wider">
                    Se connecter
                </button>
            </form>

            <!-- Lien inscription -->
            <p class="mt-8 text-center text-gray-600">
                Pas encore de compte ?
                <a href="{{ route('register') }}" class="font-bold text-violet-600 hover:text-violet-700">
                    Créer un compte
                </a>
            </p>
        </div>
    </div>

    <!-- Section droite - Image -->
    <div class="hidden lg:block lg:flex-1 relative">
        <img src="https://images.unsplash.com/photo-1551632811-561732d1e306?w=1920&q=80"
             alt="Trail running"
             class="absolute inset-0 w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-r from-gray-900/70 to-gray-900/30"></div>
        <div class="absolute inset-0 flex items-center justify-center text-white p-12">
            <div class="max-w-lg">
                <h2 class="font-display text-5xl font-black mb-6 leading-tight">
                    RENFORCEZ VOTRE DÉTERMINATION
                </h2>
                <p class="text-xl text-white/90 leading-relaxed">
                    Plans d'entraînement intelligents pour ultra-trails, backyard ultras et courses verticales.
                </p>
            </div>
        </div>
    </div>
</div>
</body>
</html>
