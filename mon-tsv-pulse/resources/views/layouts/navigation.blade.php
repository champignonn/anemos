<nav class="fixed w-full z-50 bg-gray-900 shadow-2xl border-b border-gray-800" x-data="{ open: false, userMenu: false, notifMenu: false }">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">

            <!-- Logo -->
            <div class="flex items-center gap-8">
                <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                    <div class="w-12 h-12 rounded-xl overflow-hidden flex items-center justify-center group-hover:scale-110 transition-transform shadow-lg">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo Anemos" class="w-full h-full object-cover">
                    </div>
                    <div class="hidden lg:block">
                        <span class="text-white font-black text-xl tracking-tight">Ane</span>
                        <span class="text-violet-600 font-black text-xl">mos</span>
                    </div>
                </a>

                <!-- Navigation principale -->
                <div class="hidden md:flex items-center gap-2">
                    <a href="{{ route('dashboard') }}"
                       class="px-4 py-2 rounded-lg text-white hover:bg-gray-800 transition-all font-semibold flex items-center gap-2 {{ request()->routeIs('dashboard') ? 'bg-gray-800' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Dashboard
                    </a>

                    <a href="{{ route('races.index') }}"
                       class="px-4 py-2 rounded-lg text-white hover:bg-gray-800 transition-all font-semibold flex items-center gap-2 {{ request()->routeIs('races.*') ? 'bg-gray-800' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        Courses
                    </a>

                    <a href="{{ route('training-plans.index') }}"
                       class="px-4 py-2 rounded-lg text-white hover:bg-gray-800 transition-all font-semibold flex items-center gap-2 {{ request()->routeIs('training-plans.*') ? 'bg-gray-800' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        Plans
                    </a>

                    <a href="{{ route('vlogs.feed') }}"
                       class="px-4 py-2 rounded-lg text-white hover:bg-gray-800 transition-all font-semibold flex items-center gap-2 {{ request()->routeIs('vlogs.feed') ? 'bg-gray-800' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        Communauté
                    </a>
                </div>
            </div>

            <div class="flex items-center gap-4">

                @auth
                    @if(auth()->user()->hasStravaConnected())
                        <div class="hidden lg:flex items-center gap-2 px-3 py-2 bg-violet-600/20 rounded-lg border border-violet-600/30">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M15.387 17.944l-2.089-4.116h-3.065L15.387 24l5.15-10.172h-3.066m-7.008-5.599l2.836 5.598h4.172L10.463 0l-7 13.828h4.169"/>
                            </svg>
                            <span class="text-white text-sm font-semibold">Strava</span>
                        </div>
                    @endif
                @endauth
                <!-- Strava Badge -->


                <!-- Notifications -->
                    @auth
                        <div class="relative">
                            <button @click="notifMenu = !notifMenu"
                                    class="relative p-2 text-white hover:bg-gray-800 rounded-lg transition-all">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>

                                {{-- On vérifie le count ici, mais dans le contexte @auth --}}
                                @if(auth()->user()->unreadNotificationsCount() > 0)
                                    <span class="absolute -top-1 -right-1 w-5 h-5 bg-violet-600 text-white text-xs font-bold rounded-full flex items-center justify-center animate-pulse">
                    {{ auth()->user()->unreadNotificationsCount() }}
                </span>
                                @endif
                            </button>

                            <div x-show="notifMenu"
                                 @click.away="notifMenu = false"
                                 x-transition
                                 class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-2xl overflow-hidden"
                                 style="display: none;">
                                <div class="p-4 border-b bg-gray-900 text-white">
                                    <h3 class="font-bold">Notifications</h3>
                                </div>

                                <div class="max-h-96 overflow-y-auto">
                                    @forelse(auth()->user()->notifications()->latest()->limit(5)->get() as $notification)
                                        <a href="{{ route('notifications.read', $notification) }}"
                                           class="block p-4 hover:bg-gray-50 border-b transition-colors {{ $notification->read ? '' : 'bg-violet-50' }}">
                                            <p class="font-semibold text-gray-900 text-sm">{{ $notification->title }}</p>
                                            <p class="text-gray-600 text-xs mt-1">{{ $notification->message }}</p>
                                            <p class="text-gray-400 text-xs mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                        </a>
                                    @empty
                                        <div class="p-8 text-center text-gray-500">
                                            <p class="text-sm">Aucune notification</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
            </div>

            @endauth

                <!-- Menu utilisateur -->
                <!-- Menu utilisateur -->
                <div class="relative">
                    <button @click="userMenu = !userMenu"
                            class="flex items-center gap-3 px-3 py-2 rounded-xl text-white hover:bg-gray-800 transition-all">
                        @if(auth()->user()->avatar_path)
                            <img src="{{ Storage::url(auth()->user()->avatar_path) }}"
                                 class="w-10 h-10 rounded-full object-cover ring-2 ring-violet-600">
                        @else
                            <div class="w-10 h-10 bg-violet-600 rounded-full flex items-center justify-center text-white font-bold ring-2 ring-violet-600">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                        @endif
                        <span class="hidden sm:block font-semibold">{{ auth()->user()->name }}</span>
                        <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': userMenu }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <div x-show="userMenu"
                         @click.away="userMenu = false"
                         x-transition
                         class="absolute right-0 mt-2 w-64 rounded-xl shadow-2xl bg-white overflow-hidden"
                         style="display: none;">
                        <div class="p-4 bg-gray-900 text-white">
                            <p class="font-bold text-lg">{{ auth()->user()->name }}</p>
                            <p class="text-sm text-gray-400">{{ auth()->user()->email }}</p>
                        </div>
                        <div class="py-2">
                            <a href="{{ route('profile.show', auth()->user()) }}"
                               class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                Mon profil
                            </a>
                            <a href="{{ route('profile.edit') }}"
                               class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                Paramètres
                            </a>

                            <div class="border-t my-2"></div>

                            <!-- Section Strava -->
                            @if(!auth()->user()->hasStravaConnected())
                                <a href="{{ route('strava.connect') }}"
                                   class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-orange-50 transition-colors group">
                                    <svg class="w-5 h-5 text-[#FC4C02]" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M15.387 17.944l-2.089-4.116h-3.065L15.387 24l5.15-10.172h-3.066m-7.008-5.599l2.836 5.598h4.172L10.463 0l-7 13.828h4.169"/>
                                    </svg>
                                    <div class="flex-1">
                                        <p class="font-semibold text-gray-900 group-hover:text-[#FC4C02]">Connecter Strava</p>
                                        <p class="text-xs text-gray-500">Synchronisez vos activités</p>
                                    </div>
                                </a>
                            @else
                                <div class="px-4 py-3">
                                    <div class="flex items-center gap-3 mb-2">
                                        <svg class="w-5 h-5 text-[#FC4C02]" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M15.387 17.944l-2.089-4.116h-3.065L15.387 24l5.15-10.172h-3.066m-7.008-5.599l2.836 5.598h4.172L10.463 0l-7 13.828h4.169"/>
                                        </svg>
                                        <div class="flex-1">
                                            <p class="text-xs text-gray-500">Connecté à Strava</p>
                                            <p class="text-sm font-semibold text-[#FC4C02]">✓ Synchronisé</p>
                                        </div>
                                    </div>
                                    <div class="flex gap-2">
                                        <form method="POST" action="{{ route('strava.sync') }}" class="flex-1">
                                            @csrf
                                            <button type="submit"
                                                    class="w-full px-3 py-2 bg-[#FC4C02] hover:bg-[#E34402] text-white text-xs font-bold rounded-lg transition-colors">
                                                Synchroniser
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('strava.disconnect') }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold rounded-lg transition-colors"
                                                    onclick="return confirm('Déconnecter Strava ?')">
                                                ✕
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endif

                            <div class="border-t my-2"></div>

                            <form method="POST" action="{{ route('logout') }}" id="logout-form" class="hidden">
                                @csrf
                            </form>

                            <a href="#"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 font-bold uppercase tracking-widest">
                                Déconnexion
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Menu mobile -->
                <button @click="open = !open"
                        class="md:hidden p-2 rounded-lg text-white hover:bg-gray-800 transition-all">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Menu mobile -->
    <div x-show="open" x-transition class="md:hidden border-t border-gray-800 bg-gray-900" style="display: none;">
        <div class="px-6 py-4 space-y-2">
            <a href="{{ route('dashboard') }}" class="block px-4 py-3 rounded-lg text-white hover:bg-gray-800 transition-all font-semibold">Dashboard</a>
            <a href="{{ route('races.index') }}" class="block px-4 py-3 rounded-lg text-white hover:bg-gray-800 transition-all font-semibold">Courses</a>
            <a href="{{ route('training-plans.index') }}" class="block px-4 py-3 rounded-lg text-white hover:bg-gray-800 transition-all font-semibold">Plans</a>
            <a href="{{ route('vlogs.feed') }}" class="block px-4 py-3 rounded-lg text-white hover:bg-gray-800 transition-all font-semibold">Communauté</a>
        </div>
    </div>
</nav>
