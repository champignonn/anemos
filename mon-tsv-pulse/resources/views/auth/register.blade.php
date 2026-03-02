<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inscription - anemos</title>
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
        <div class="w-full max-w-xl">
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
                    Commencez l'aventure
                </h1>
                <p class="text-gray-600 text-lg">
                    Créez votre compte et transformez votre entraînement trail.
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
            <form method="POST" action="{{ route('register') }}" class="space-y-6">
                @csrf

                <!-- Infos de base -->
                <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100">
                    <h3 class="font-black text-gray-900 mb-4 text-lg">Informations de base</h3>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-2">Nom complet *</label>
                            <input type="text"
                                   name="name"
                                   value="{{ old('name') }}"
                                   required
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-violet-600 focus:ring-2 focus:ring-violet-100 outline-none transition-all"
                                   placeholder="John Doe">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-2">Email *</label>
                            <input type="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   required
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-violet-600 focus:ring-2 focus:ring-violet-100 outline-none transition-all"
                                   placeholder="john@example.com">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-900 mb-2">Mot de passe *</label>
                                <input type="password"
                                       name="password"
                                       required
                                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-violet-600 focus:ring-2 focus:ring-violet-100 outline-none transition-all"
                                       placeholder="••••••••">
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-900 mb-2">Confirmer *</label>
                                <input type="password"
                                       name="password_confirmation"
                                       required
                                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-violet-600 focus:ring-2 focus:ring-violet-100 outline-none transition-all"
                                       placeholder="••••••••">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Métriques (optionnel) -->
                <div class="bg-cyan-50 p-6 rounded-2xl border border-cyan-200">
                    <h3 class="font-black text-gray-900 mb-2 text-lg">Métriques sportives</h3>
                    <p class="text-sm text-gray-600 mb-4">(Optionnel - pour des plans personnalisés)</p>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-2">VMA (km/h)</label>
                            <input type="number"
                                   step="0.1"
                                   name="vma"
                                   value="{{ old('vma') }}"
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-cyan-600 focus:ring-2 focus:ring-cyan-100 outline-none transition-all"
                                   placeholder="14.0">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-2">Allure EF (min/km)</label>
                            <input type="number"
                                   step="0.1"
                                   name="endurance_pace"
                                   value="{{ old('endurance_pace') }}"
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-cyan-600 focus:ring-2 focus:ring-cyan-100 outline-none transition-all"
                                   placeholder="6.0">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-2">FC Max (bpm)</label>
                            <input type="number"
                                   name="max_heart_rate"
                                   value="{{ old('max_heart_rate') }}"
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-cyan-600 focus:ring-2 focus:ring-cyan-100 outline-none transition-all"
                                   placeholder="180">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-2">Poids (kg)</label>
                            <input type="number"
                                   name="weight"
                                   value="{{ old('weight') }}"
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-cyan-600 focus:ring-2 focus:ring-cyan-100 outline-none transition-all"
                                   placeholder="70">
                        </div>
                    </div>
                </div>

                <button type="submit"
                        class="w-full bg-violet-600 hover:bg-violet-700 text-white font-bold py-4 rounded-xl transition-all transform hover:scale-105 shadow-lg uppercase tracking-wider">
                    Créer mon compte
                </button>
            </form>

            <!-- Lien connexion -->
            <p class="mt-8 text-center text-gray-600">
                Déjà un compte ?
                <a href="{{ route('login') }}" class="font-bold text-violet-600 hover:text-violet-700">
                    Se connecter
                </a>
            </p>
        </div>
    </div>

    <!-- Section droite - Image -->
    <div class="hidden lg:block lg:flex-1 relative">
        <img src="https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1920&q=80"
             alt="Trail running"
             class="absolute inset-0 w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-r from-gray-900/70 to-gray-900/30"></div>
        <div class="absolute inset-0 flex items-center justify-center text-white p-12">
            <div class="max-w-lg">
                <h2 class="font-display text-5xl font-black mb-6 leading-tight">
                    SENTEZ-VOUS VIVANT À CHAQUE PAS
                </h2>
                <div class="space-y-4 text-lg">
                    <div class="flex items-center gap-3">
                        <svg class="w-6 h-6 text-violet-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>Plans d'entraînement intelligents</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <svg class="w-6 h-6 text-violet-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>Synchronisation Strava</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <svg class="w-6 h-6 text-violet-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>Communauté engagée</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
