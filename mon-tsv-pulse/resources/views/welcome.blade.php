<!DOCTYPE html>
<html lang="fr">
<head>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.png') }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Anemos - Plateforme d'Entraînement Ultra-Trail</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;900&family=Playfair+Display:wght@700;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
        .font-display {
            font-family: 'Playfair Display', serif;
        }

        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.5)),
            url('https://images.unsplash.com/photo-1551632811-561732d1e306?w=1920&q=80') center/cover fixed;
            min-height: 100vh;
            position: relative;
            padding-top: 5rem;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        .animate-slide-up {
            animation: slideUp 0.8s ease-out forwards;
        }

        .animate-fade-in {
            animation: fadeIn 1s ease-out forwards;
        }

        .animate-float {
            animation: float 3s ease-in-out infinite;
        }

        .delay-100 { animation-delay: 0.1s; opacity: 0; }
        .delay-200 { animation-delay: 0.2s; opacity: 0; }
        .delay-300 { animation-delay: 0.3s; opacity: 0; }
        .delay-400 { animation-delay: 0.4s; opacity: 0; }

        .btn-primary {
            background: #8B5CF6;
            color: white;
            padding: 1rem 2.5rem;
            border-radius: 0.5rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s;
            display: inline-block;
        }

        .btn-primary:hover {
            background: #7C3AED;
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(139, 92, 246, 0.4);
        }

        .btn-outline {
            border: 2px solid white;
            color: white;
            padding: 1rem 2.5rem;
            border-radius: 0.5rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s;
            display: inline-block;
        }

        .btn-outline:hover {
            background: white;
            color: #1a1a1a;
        }

        .feature-card {
            background: white;
            border-radius: 1rem;
            padding: 2.5rem;
            transition: all 0.3s;
            border: 1px solid #e5e7eb;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .stat-number {
            font-size: 4rem;
            font-weight: 900;
            line-height: 1;
            background: linear-gradient(135deg, #8B5CF6 0%, #00bcd4 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>
<body class="bg-neutral-50">

<!-- Navigation -->
<nav class="fixed w-full z-50 transition-all" x-data="{ scrolled: false, open: false }"
     @scroll.window="scrolled = window.pageYOffset > 50"
     :class="scrolled ? 'bg-gray-900 shadow-2xl' : 'bg-transparent'">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            <!-- Logo -->
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl overflow-hidden flex items-center justify-center group-hover:scale-110 transition-transform shadow-lg">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo Anemos" class="w-full h-full object-cover">
                </div>
                <div>
                    <span class="text-white font-black text-xl tracking-tight">Ane</span>
                    <span class="text-violet-600 font-black text-xl">mos</span>
                </div>
            </div>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center gap-8">
                <a href="#features" class="text-white hover:text-violet-600 transition font-semibold">Fonctionnalités</a>
                <a href="#stats" class="text-white hover:text-violet-600 transition font-semibold">Statistiques</a>
                <a href="#community" class="text-white hover:text-violet-600 transition font-semibold">Communauté</a>
                <a href="{{ route('login') }}" class="text-white hover:text-violet-600 transition font-semibold">Connexion</a>
                <a href="{{ route('register') }}" class="btn-primary">Commencer</a>
            </div>

            <!-- Mobile Menu Button -->
            <button @click="open = !open" class="md:hidden text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div x-show="open" x-transition class="md:hidden bg-gray-900 border-t border-gray-800">
        <div class="px-6 py-4 space-y-3">
            <a href="#features" class="block text-white hover:text-violet-600 py-2">Fonctionnalités</a>
            <a href="#stats" class="block text-white hover:text-violet-600 py-2">Statistiques</a>
            <a href="#community" class="block text-white hover:text-violet-600 py-2">Communauté</a>
            <a href="{{ route('login') }}" class="block text-white hover:text-violet-600 py-2">Connexion</a>
            <a href="{{ route('register') }}" class="block text-center btn-primary mt-4">Commencer</a>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section class="hero-section flex items-center justify-center text-center text-white px-6">
    <div class="max-w-5xl">
        <div class="animate-slide-up">
            <h1 class="font-display text-4xl md:text-8xl font-black mb-6 leading-none">
                RENFORCEZ<br>
                VOTRE<br>
                <span class="text-violet-500">DÉTERMINATION</span>
            </h1>
        </div>

        <p class="text-xl md:text-2xl mb-12 max-w-2xl mx-auto font-light animate-slide-up delay-100">
            La plateforme ultime d'entraînement ultra-trail. Plans intelligents, soutien communautaire et analyses basées sur vos données.
        </p>

        <div class="flex flex-col sm:flex-row gap-4 justify-center animate-slide-up delay-200">
            <a href="{{ route('register') }}" class="btn-primary">Commencer l'aventure</a>
            <a href="#features" class="btn-outline">En savoir plus</a>
        </div>

        <div class="mt-20 animate-slide-up delay-300">
            <p class="text-sm uppercase tracking-widest mb-4 opacity-75">Ils nous font confiance</p>
            <div class="flex flex-wrap justify-center items-center gap-8 opacity-60">
                <span class="text-2xl font-bold">STRAVA</span>
                <span class="text-2xl font-bold">GARMIN</span>
                <span class="text-2xl font-bold">SUUNTO</span>
            </div>
        </div>
    </div>

    <!-- Scroll Indicator -->
    <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2 animate-float">
        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
        </svg>
    </div>
</section>

<!-- Features Section -->
<section id="features" class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="text-center mb-20">
            <h2 class="font-display text-5xl md:text-6xl font-black text-gray-900 mb-6">
                DES RAPPELS EXTERNES<br>
                POUR DE MEILLEURES <span class="text-violet-600">DÉCISIONS</span>
            </h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Plans d'entraînement alimentés par l'IA qui s'adaptent à vos objectifs, votre niveau et votre format de course.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="feature-card">
                <div class="w-16 h-16 bg-violet-100 rounded-2xl flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-black text-gray-900 mb-4">PLANIFICATION INTELLIGENTE</h3>
                <p class="text-gray-600 leading-relaxed">
                    Des algorithmes intelligents détectent votre format de course (backyard, vertical, ultra) et créent des plans d'entraînement spécialisés.
                </p>
            </div>

            <div class="feature-card">
                <div class="w-16 h-16 bg-cyan-100 rounded-2xl flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-black text-gray-900 mb-4">COMMUNAUTÉ</h3>
                <p class="text-gray-600 leading-relaxed">
                    Connectez-vous avec d'autres ultra-trailers. Partagez vos vlogs, suivez des athlètes et restez motivé par la communauté.
                </p>
            </div>

            <div class="feature-card">
                <div class="w-16 h-16 bg-green-100 rounded-2xl flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-black text-gray-900 mb-4">ANALYSE DE DONNÉES</h3>
                <p class="text-gray-600 leading-relaxed">
                    Suivez chaque métrique. Synchronisez avec Strava. Analysez vos progrès avec de beaux graphiques et statistiques.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Visual Section avec image grande -->
<section class="relative h-screen">
    <div class="absolute inset-0">
        <img src="https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1920&q=80"
             alt="Trail running"
             class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-r from-gray-900/80 to-transparent"></div>
    </div>

    <div class="relative h-full flex items-center">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 w-full">
            <div class="max-w-2xl">
                <h2 class="font-display text-6xl md:text-7xl font-black text-white mb-8 leading-none">
                    SENTEZ-VOUS<br>
                    VIVANT À<br>
                    <span class="text-violet-500">CHAQUE PAS</span>
                </h2>
                <p class="text-xl text-white/90 mb-12 leading-relaxed">
                    De votre première sortie trail à la conquête des ultra-marathons. Nous sommes avec vous à chaque étape.
                </p>
                <a href="{{ route('register') }}" class="btn-primary">Rejoindre l'aventure</a>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section id="stats" class="py-24 bg-gray-900 text-white">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-12 text-center">
            <div>
                <div class="stat-number">500+</div>
                <p class="text-xl text-gray-400 mt-4 uppercase tracking-wider">Coureurs Actifs</p>
            </div>
            <div>
                <div class="stat-number">50K+</div>
                <p class="text-xl text-gray-400 mt-4 uppercase tracking-wider">KM Parcourus</p>
            </div>
            <div>
                <div class="stat-number">200+</div>
                <p class="text-xl text-gray-400 mt-4 uppercase tracking-wider">Courses Finies</p>
            </div>
            <div>
                <div class="stat-number">98%</div>
                <p class="text-xl text-gray-400 mt-4 uppercase tracking-wider">Taux de Réussite</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Final -->
<section id="community" class="relative py-32 overflow-hidden">
    <div class="absolute inset-0">
        <img src="https://images.unsplash.com/photo-1682687220742-aba13b6e50ba?w=1920&q=80"
             alt="Mountain summit"
             class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gray-900/70"></div>
    </div>

    <div class="relative max-w-4xl mx-auto text-center px-6">
        <h2 class="font-display text-5xl md:text-7xl font-black text-white mb-8 leading-tight">
            L'AVENTURE<br>
            <span class="text-violet-500">CONTINUE</span>
        </h2>
        <p class="text-2xl text-white/90 mb-12 max-w-2xl mx-auto">
            Rejoignez la communauté ultra-trail la plus dynamique aujourd'hui.
        </p>
        <div class="flex flex-col sm:flex-row gap-6 justify-center">
            <a href="{{ route('register') }}" class="btn-primary text-lg">Créer un compte gratuit</a>
            <a href="{{ route('login') }}" class="btn-outline text-lg">Se connecter</a>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="bg-gray-900 text-white">
    <div class="max-w-7xl mx-auto px-6 lg:px-8 py-16">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
            <!-- Logo & Description -->
            <div class="md:col-span-2">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-12 h-12 rounded-xl overflow-hidden flex items-center justify-center group-hover:scale-110 transition-transform shadow-lg">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo Anemos" class="w-full h-full object-cover">
                    </div>
                    <div>
                        <span class="text-white font-black text-2xl">Ane</span>
                        <span class="text-violet-600 font-black text-2xl">mos</span>
                    </div>
                </div>
                <p class="text-gray-400 leading-relaxed max-w-md">
                    La plateforme d'entraînement ultra-trail qui transforme vos objectifs en réalité grâce aux algorithmes et une communauté passionnée.
                </p>
                <div class="flex gap-4 mt-6">
                    <a href="https://www.linkedin.com/in/charlotte-duverger-a9922a262/"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="w-10 h-10 bg-gray-800 hover:bg-violet-600 rounded-lg flex items-center justify-center transition-all group"
                       title="Suivez-moi sur LinkedIn">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                        </svg>
                    </a>

                    <a href="https://www.instagram.com/cha_.dvgr/"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="w-10 h-10 bg-gray-800 hover:bg-violet-600 rounded-lg flex items-center justify-center transition-all group"
                       title="Suivez-moi sur Instagram">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Liens rapides -->
            <div>
                <h4 class="text-white font-black text-lg mb-6">Plateforme</h4>
                <ul class="space-y-3">
                    <li><a href="#features" class="text-gray-400 hover:text-violet-500 transition-colors">Fonctionnalités</a></li>
                    <li><a href="#stats" class="text-gray-400 hover:text-violet-500 transition-colors">Statistiques</a></li>
                    <li><a href="{{ route('register') }}" class="text-gray-400 hover:text-violet-500 transition-colors">Créer un compte</a></li>
                    <li><a href="{{ route('login') }}" class="text-gray-400 hover:text-violet-500 transition-colors">Se connecter</a></li>
                </ul>
            </div>

            <!-- Support -->
            <div>
                <h4 class="text-white font-black text-lg mb-6">Support</h4>
                <ul class="space-y-3">
                    <li><a href="{{ route('support.help') }}" class="text-gray-400 hover:text-violet-500 transition-colors">Centre d'aide</a></li>
                    <li><a href="{{ route('support.docs') }}" class="text-gray-400 hover:text-violet-500 transition-colors">Documentation</a></li>
                    <li><a href="{{ route('support.contact') }}" class="text-gray-400 hover:text-violet-500 transition-colors">Contact</a></li>
                    <li><a href="{{ route('support.faq') }}" class="text-gray-400 hover:text-violet-500 transition-colors">FAQ</a></li>
                </ul>
            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="pt-8 border-t border-gray-800 flex flex-col md:flex-row justify-between items-center gap-4">
            <p class="text-gray-400 text-sm">
                © 2026 Charlotte Duverger. Tous droits réservés. Fait avec ❤️ pour les ultra-trailers.
            </p>
            <div class="flex gap-6 text-sm">
                <a href="{{ route('legal.privacy') }}" class="text-gray-400 hover:text-violet-500 transition-colors">Confidentialité</a>
                <a href="{{ route('legal.cgu') }}" class="text-gray-400 hover:text-violet-500 transition-colors">CGU</a>
                <a href="{{ route('legal.cookies') }}" class="text-gray-400 hover:text-violet-500 transition-colors">Cookies</a>
            </div>
        </div>
    </div>
</footer>

</body>
</html>
