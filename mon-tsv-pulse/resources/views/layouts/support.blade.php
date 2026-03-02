<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - TSV-Pulse Support</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&family=Playfair+Display:wght@900&display=swap" rel="stylesheet">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-display { font-family: 'Playfair Display', serif; }
        .glass-card { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); }
    </style>
</head>
<body class="bg-gray-900 antialiased">
<div class="fixed inset-0 z-0">
    <img src="https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?w=1920&q=80" class="w-full h-full object-cover opacity-40">
    <div class="absolute inset-0 bg-gradient-to-b from-gray-900 via-transparent to-gray-900"></div>
</div>

<div class="relative z-10 min-h-screen flex flex-col">
    <nav class="py-6 px-8">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <a href="/" class="flex items-center gap-3">
                <div class="w-10 h-10 bg-violet-600 rounded-xl flex items-center justify-center text-white font-black shadow-lg">T</div>
                <span class="text-white font-black text-xl tracking-tight">Ane<span class="text-violet-600">mos</span></span>
            </a>
            <a href="/dashboard" class="bg-white/10 hover:bg-white/20 text-white px-5 py-2 rounded-xl transition font-bold text-sm backdrop-blur-md">Mon Dashboard</a>
        </div>
    </nav>

    <main class="flex-grow py-12">
        @yield('content')
    </main>

    <footer class="py-8 text-center text-white/40 text-sm font-medium">
        <p>© 2026 Anemos - Dépasser ses limites, ensemble.</p>
    </footer>
</div>
</body>
</html>
