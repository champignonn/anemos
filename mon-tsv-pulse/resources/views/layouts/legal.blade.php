<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - anemos</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .legal-content h2 { border-left: 4px solid #8B5CF6; padding-left: 1rem; margin-top: 2.5rem; }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 antialiased">
<nav class="bg-white/80 backdrop-blur-md sticky top-0 z-50 border-b border-slate-200">
    <div class="max-w-5xl mx-auto px-6 h-20 flex justify-between items-center">
        <a href="/" class="flex items-center gap-3 group">
            <div class="w-10 h-10 bg-violet-600 rounded-xl flex items-center justify-center text-white font-black shadow-lg shadow-violet-200 transition-transform group-hover:scale-105">T</div>
            <span class="font-black text-xl tracking-tight">Ane<span class="text-violet-600">mos</span></span>
        </a>
        <a href="/" class="text-sm font-bold text-slate-500 hover:text-violet-600 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7 m0 0l7-7 m-7 7h18"/></svg>
            Retour
        </a>
    </div>
</nav>

<main class="max-w-3xl mx-auto px-6 py-20">
    <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/60 p-8 md:p-16 legal-content">
        @yield('content')
    </div>
</main>

<footer class="py-12 text-center text-sm text-slate-400 font-medium">
    <p>© 2026 TSV-Pulse. Plateforme d'entraînement Ultra-Trail.</p>
</footer>

<div x-data="{ showCookie: !localStorage.getItem('cookieConsent') }"
     x-show="showCookie"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 translate-y-10"
     x-transition:enter-end="opacity-100 translate-y-0"
     class="fixed bottom-6 left-6 right-6 md:left-auto md:right-10 md:w-96 z-[100]">
    <div class="bg-gray-900 rounded-2xl p-6 shadow-2xl border border-white/10 text-white">
        <div class="flex items-start gap-4">
            <div class="bg-violet-600 p-2 rounded-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <h3 class="font-bold text-lg">Respect de la vie privée</h3>
                <p class="text-gray-400 text-sm mt-2 leading-relaxed">
                    Anemos utilise des cookies pour assurer le bon fonctionnement du dashboard et analyser votre progression.
                </p>
                <div class="mt-6 flex flex-col gap-2">
                    <button @click="localStorage.setItem('cookieConsent', true); showCookie = false"
                            class="w-full bg-violet-600 hover:bg-violet-700 text-white font-bold py-2 rounded-xl transition-all">
                        Accepter
                    </button>
                    <a href="{{ route('legal.cookies') }}" class="text-center text-xs text-gray-500 hover:text-white transition-colors">
                        En savoir plus
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
