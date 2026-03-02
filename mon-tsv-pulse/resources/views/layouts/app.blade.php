<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - anemos</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;900&family=Playfair+Display:wght@700;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
        .font-display {
            font-family: 'Playfair Display', serif;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out forwards;
        }

        .animate-slide-in-right {
            animation: slideInRight 0.6s ease-out forwards;
        }

        .animate-float {
            animation: float 3s ease-in-out infinite;
        }

        .delay-100 { animation-delay: 0.1s; opacity: 0; }
        .delay-200 { animation-delay: 0.2s; opacity: 0; }
        .delay-300 { animation-delay: 0.3s; opacity: 0; }
        .delay-400 { animation-delay: 0.4s; opacity: 0; }

        .card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #ff5722;
            border-radius: 5px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #e64a19;
        }
    </style>

    @stack('styles')
</head>
<body class="bg-gray-50">

@include('layouts.navigation')

<!-- Flash Messages -->
@if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
         class="fixed top-24 right-6 z-50 bg-green-600 text-white px-6 py-4 rounded-xl shadow-2xl flex items-center gap-3 animate-fade-in-up">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span class="font-semibold">{{ session('success') }}</span>
        <button @click="show = false" class="ml-4">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
@endif

@if(session('error'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
         class="fixed top-24 right-6 z-50 bg-red-600 text-white px-6 py-4 rounded-xl shadow-2xl flex items-center gap-3 animate-fade-in-up">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span class="font-semibold">{{ session('error') }}</span>
        <button @click="show = false" class="ml-4">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
@endif

<!-- Main Content -->
<main class="min-h-screen pt-20">
    @yield('content')
</main>

@stack('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('countdown', (expiry) => ({
            days: 0,
            init() {
                // Conversion de la date ISO en timestamp
                const endDate = new Date(expiry).getTime();

                const update = () => {
                    const now = new Date().getTime();
                    const diff = endDate - now;

                    // Calcul du nombre de jours
                    this.days = Math.max(0, Math.floor(diff / (1000 * 60 * 60 * 24)));
                };

                // Appel initial et rafraîchissement
                update();
                setInterval(update, 60000); // Mise à jour toutes les minutes
            }
        }));
    });
</script>

<!-- FOOTER GLOBAL -->
<footer class="bg-gray-900 text-white mt-20">
    <div class="max-w-7xl mx-auto px-6 lg:px-8 py-16">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">

            <!-- Logo & Description -->
            <div class="md:col-span-2">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-12 h-12 rounded-xl overflow-hidden flex items-center justify-center group-hover:scale-110 transition-transform shadow-lg">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo Anemos" class="w-full h-full object-cover">
                    </div>
                    <div>
                        <span class="text-white font-black text-2xl">ANE</span>
                        <span class="text-violet-600 font-black text-2xl">MOS</span>
                    </div>
                </div>
                <p class="text-gray-400 leading-relaxed max-w-md mb-6">
                    La plateforme d'entraînement ultra-trail qui transforme vos objectifs en réalité grâce aux algorithmes et une communauté passionnée.
                </p>
                <div class="flex gap-4">
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
                    <li><a href="{{ route('home') }}" class="text-gray-400 hover:text-violet-500 transition-colors">Découvrir des courses</a></li>
                    <li><a href="{{ route('dashboard') }}" class="text-gray-400 hover:text-violet-500 transition-colors">Dashboard</a></li>
                    <li><a href="{{ route('vlogs.feed') }}" class="text-gray-400 hover:text-violet-500 transition-colors">Timeline</a></li>
                    <li><a href="{{ route('profile.show', auth()->user()) }}" class="text-gray-400 hover:text-violet-500 transition-colors">Mon profil</a></li>
                </ul>
            </div>

            <!-- Support -->
            <div>
                <h4 class="text-white font-black text-lg mb-6">Support</h4>
                <ul class="space-y-3">
                    <li><a href="{{ route('support.help')  }}" class="text-gray-400 hover:text-violet-500 transition-colors">Centre d'aide</a></li>
                    <li><a href="{{ route('support.docs')  }}" class="text-gray-400 hover:text-violet-500 transition-colors">Documentation</a></li>
                    <li><a href="{{ route('support.contact')  }}" class="text-gray-400 hover:text-violet-500 transition-colors">Contact</a></li>
                    <li><a href="{{ route('support.faq')  }}" class="text-gray-400 hover:text-violet-500 transition-colors">FAQ</a></li>
                </ul>
            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="pt-8 border-t border-gray-800 flex flex-col md:flex-row justify-between items-center gap-4">
            <p class="text-gray-400 text-sm">
                © 2026 Charlotte Duverger. Tous droits réservés. Fait avec ❤️ pour les ultra-trailers.
            </p>
            <div class="flex gap-6 text-sm">
                <a href="{{ route('legal.privacy')  }}" class="text-gray-400 hover:text-violet-500 transition-colors">Confidentialité</a>
                <a href="{{ route('legal.cgu')  }}" class="text-gray-400 hover:text-violet-500 transition-colors">CGU</a>
                <a href="{{ route('legal.cookies')  }}" class="text-gray-400 hover:text-violet-500 transition-colors">Cookies</a>
            </div>
        </div>
    </div>
</footer>

@stack('scripts')
</body>
</html>
</body>
</html>
