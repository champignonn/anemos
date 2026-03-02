@extends('layouts.app')
@section('title', 'Découvrir des courses')

@section('content')
    <div class="min-h-screen bg-gray-50">

        <!-- Hero Section -->
        <div class="relative bg-gradient-to-br from-gray-900 to-gray-800 overflow-hidden">
            <div class="absolute inset-0 opacity-20">
                <img src="https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1920&q=80"
                     class="w-full h-full object-cover">
            </div>

            <div class="relative max-w-7xl mx-auto px-6 lg:px-8 py-20">
                <div class="text-center text-white">
                    <h1 class="font-display text-5xl md:text-6xl font-black mb-6 leading-tight">
                        Découvrez votre<br>
                        <span class="text-violet-500">prochaine aventure</span>
                    </h1>
                    <p class="text-xl text-white/90 mb-8 max-w-2xl mx-auto">
                        Explorez des centaines de courses trail à travers le monde. Trouvez celle qui vous correspond.
                    </p>

                    <!-- Quick stats -->
                    <div class="flex flex-wrap justify-center gap-8 mb-8">
                        <div>
                            <p class="text-4xl font-black">{{ $stats['total_races'] }}+</p>
                            <p class="text-white/80">Courses disponibles</p>
                        </div>
                        <div>
                            <p class="text-4xl font-black">{{ $stats['total_runners'] }}+</p>
                            <p class="text-white/80">Traileurs</p>
                        </div>
                        <div>
                            <p class="text-4xl font-black">{{ $stats['countries'] }}+</p>
                            <p class="text-white/80">Pays</p>
                        </div>
                    </div>

                    <a href="#courses"
                       class="inline-flex items-center gap-3 bg-violet-600 hover:bg-violet-700 text-white font-bold px-8 py-4 rounded-xl transition-all transform hover:scale-105 shadow-lg uppercase tracking-wider">
                        Explorer les courses
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-6 lg:px-8 py-12">

            <!-- Mes prochaines courses (si connecté) -->
            @auth
                @if($userRaces->isNotEmpty())
                    <div class="mb-12">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-3xl font-black text-gray-900">Mes prochaines courses</h2>
                            <a href="{{ route('races.index') }}" class="text-violet-600 font-bold hover:underline">
                                Voir toutes mes courses →
                            </a>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            @foreach($userRaces as $race)
                                <a href="{{ route('races.show', $race) }}"
                                   class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-2xl transition-all transform hover:-translate-y-1 border-l-4 border-violet-600">
                                    <h3 class="text-xl font-black text-gray-900 mb-2">{{ $race->name }}</h3>
                                    <p class="text-sm text-gray-600 mb-3">
                                        📅 {{ $race->race_date->format('d/m/Y') }} •
                                        Dans {{ $race->days_until_race }} jours
                                    </p>
                                    <div class="flex gap-3 text-sm">
                        <span class="px-3 py-1 bg-violet-100 text-violet-800 rounded-full font-bold">
                            {{ $race->distance }}km
                        </span>
                                        <span class="px-3 py-1 bg-cyan-100 text-cyan-800 rounded-full font-bold">
                            D+ {{ $race->elevation_gain }}m
                        </span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endauth

            <!-- Suggestions à proximité -->
            <div class="mb-12 bg-gradient-to-r from-cyan-500 to-violet-600 rounded-2xl p-8 text-white">
                <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                    <div>
                        <h3 class="text-2xl font-black mb-2">🗺️ Trouvez des courses près de chez vous</h3>
                        <p class="text-white/90">Découvrez les trails à proximité de votre localisation</p>
                    </div>
                    <a href="{{ route('home.nearby') }}"
                       class="bg-white text-gray-900 font-bold px-8 py-4 rounded-xl hover:bg-gray-100 transition-all shadow-lg whitespace-nowrap">
                        Voir les courses à proximité
                    </a>
                </div>
            </div>

            <!-- Filtres -->
            <div id="courses" class="bg-white rounded-2xl p-6 shadow-lg mb-8">
                <form method="GET" action="{{ route('home') }}" class="grid grid-cols-1 md:grid-cols-6 gap-4">

                    <!-- Type -->
                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-2">Type</label>
                        <select name="type" class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:border-violet-600 outline-none">
                            <option value="">Tous</option>
                            <option value="trail" {{ request('type') === 'trail' ? 'selected' : '' }}>Trail</option>
                            <option value="vertical" {{ request('type') === 'vertical' ? 'selected' : '' }}>Vertical</option>
                            <option value="ultra" {{ request('type') === 'ultra' ? 'selected' : '' }}>Ultra</option>
                            <option value="backyard" {{ request('type') === 'backyard' ? 'selected' : '' }}>Backyard</option>
                        </select>
                    </div>

                    <!-- Difficulté -->
                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-2">Niveau</label>
                        <select name="difficulty" class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:border-violet-600 outline-none">
                            <option value="">Tous</option>
                            <option value="débutant" {{ request('difficulty') === 'débutant' ? 'selected' : '' }}>Débutant</option>
                            <option value="intermédiaire" {{ request('difficulty') === 'intermédiaire' ? 'selected' : '' }}>Intermédiaire</option>
                            <option value="expert" {{ request('difficulty') === 'expert' ? 'selected' : '' }}>Expert</option>
                        </select>
                    </div>

                    <!-- Ville -->
                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-2">Ville</label>
                        <input type="text" name="city" value="{{ request('city') }}"
                               placeholder="Ex: Lyon"
                               class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:border-violet-600 outline-none">
                    </div>

                    <!-- Distance Min -->
                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-2">Dist. min</label>
                        <input type="number" name="distance_min" value="{{ request('distance_min') }}"
                               placeholder="km"
                               class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:border-violet-600 outline-none">
                    </div>

                    <!-- Distance Max -->
                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-2">Dist. max</label>
                        <input type="number" name="distance_max" value="{{ request('distance_max') }}"
                               placeholder="km"
                               class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:border-violet-600 outline-none">
                    </div>

                    <!-- Bouton -->
                    <div class="flex items-end">
                        <button type="submit"
                                class="w-full bg-violet-600 hover:bg-violet-700 text-white font-bold py-2 rounded-lg transition-all">
                            Filtrer
                        </button>
                    </div>
                </form>

                <!-- Tri -->
                <div class="mt-4 pt-4 border-t flex items-center gap-4">
                    <span class="text-sm font-bold text-gray-900">Trier par:</span>
                    <div class="flex gap-2">
                        <a href="{{ route('home', array_merge(request()->all(), ['sort' => 'date'])) }}"
                           class="px-4 py-2 rounded-lg text-sm font-semibold {{ request('sort', 'date') === 'date' ? 'bg-violet-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            Date
                        </a>
                        <a href="{{ route('home', array_merge(request()->all(), ['sort' => 'distance'])) }}"
                           class="px-4 py-2 rounded-lg text-sm font-semibold {{ request('sort') === 'distance' ? 'bg-violet-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            Distance
                        </a>
                        <a href="{{ route('home', array_merge(request()->all(), ['sort' => 'elevation'])) }}"
                           class="px-4 py-2 rounded-lg text-sm font-semibold {{ request('sort') === 'elevation' ? 'bg-violet-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            Dénivelé
                        </a>
                        <a href="{{ route('home', array_merge(request()->all(), ['sort' => 'name'])) }}"
                           class="px-4 py-2 rounded-lg text-sm font-semibold {{ request('sort') === 'name' ? 'bg-violet-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            Nom
                        </a>
                    </div>
                </div>
            </div>

            <!-- Liste des courses -->
            @if($publicRaces->isEmpty())
                <div class="bg-white rounded-2xl p-16 text-center shadow-lg">
                    <svg class="w-24 h-24 mx-auto text-gray-300 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <h3 class="text-3xl font-black text-gray-900 mb-4">Aucune course trouvée</h3>
                    <p class="text-xl text-gray-600 mb-8">
                        Essayez d'ajuster vos filtres pour trouver d'autres courses.
                    </p>
                    <a href="{{ route('home') }}" class="inline-block bg-violet-600 hover:bg-violet-700 text-white font-bold px-8 py-4 rounded-xl">
                        Réinitialiser les filtres
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                    @foreach($publicRaces as $race)
                        <div class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all transform hover:-translate-y-2">

                            <!-- Image -->
                            <div class="relative h-48 bg-gradient-to-br from-gray-900 to-gray-700">
                                @if($race->image_url)
                                    <img src="{{ $race->image_url }}" class="w-full h-full object-cover opacity-80">
                                @else
                                    <img src="https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800&q=80"
                                         class="w-full h-full object-cover opacity-40">
                                @endif

                                <!-- Badges -->
                                <div class="absolute top-4 right-4 flex flex-col gap-2">
                        <span class="px-3 py-1 bg-{{ $race->difficulty_badge_color }}-600 text-white rounded-full text-xs font-black uppercase">
                            {{ $race->difficulty_level }}
                        </span>
                                    @if($race->is_verified)
                                        <span class="px-3 py-1 bg-green-600 text-white rounded-full text-xs font-black">
                            ✓ Vérifié
                        </span>
                                    @endif
                                </div>

                                <!-- Countdown -->
                                <div class="absolute bottom-4 left-4 text-white">
                                    <p class="text-sm font-semibold opacity-90">Dans</p>
                                    <p class="text-3xl font-black">{{ $race->days_until_race }} jours</p>
                                </div>
                            </div>

                            <!-- Contenu -->
                            <div class="p-6">
                                <div class="flex items-start justify-between mb-3">
                                    <h3 class="text-2xl font-black text-gray-900 flex-1">
                                        {{ $race->name }}
                                    </h3>
                                    @auth
                                        <form method="POST" action="{{ route('home.mark-interested', $race) }}">
                                            @csrf
                                            <button type="submit" class="text-2xl">
                                                {{ auth()->user()->interestedInRaces()->where('public_race_id', $race->id)->exists() ? '⭐' : '☆' }}
                                            </button>
                                        </form>
                                    @endauth
                                </div>

                                <div class="flex items-center gap-2 text-sm text-gray-600 mb-3">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    {{ $race->race_date->format('d F Y') }}
                                </div>

                                <div class="flex items-center gap-2 text-sm text-gray-600 mb-4">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    </svg>
                                    {{ $race->city }}, {{ $race->country }}
                                </div>

                                <div class="grid grid-cols-2 gap-3 mb-4">
                                    <div class="bg-violet-50 rounded-xl p-3 border border-violet-200">
                                        <p class="text-xs text-gray-600">Distance</p>
                                        <p class="text-xl font-black text-gray-900">{{ $race->distance }} km</p>
                                    </div>
                                    <div class="bg-cyan-50 rounded-xl p-3 border border-cyan-200">
                                        <p class="text-xs text-gray-600">Dénivelé</p>
                                        <p class="text-xl font-black text-gray-900">D+ {{ $race->elevation_gain }}m</p>
                                    </div>
                                </div>

                                @if($race->price)
                                    <p class="text-sm text-gray-600 mb-4">
                                        💰 {{ number_format($race->price, 0) }}€
                                    </p>
                                @endif

                                <!-- Actions -->
                                <div class="flex gap-3">
                                    @auth
                                        <form method="POST" action="{{ route('home.add-to-races', $race) }}" class="flex-1">
                                            @csrf
                                            <button type="submit"
                                                    class="w-full bg-violet-600 hover:bg-violet-700 text-white font-bold py-3 rounded-xl transition-all">
                                                Ajouter à mes courses
                                            </button>
                                        </form>
                                    @else
                                        <a href="{{ route('login') }}"
                                           class="flex-1 block text-center bg-violet-600 hover:bg-violet-700 text-white font-bold py-3 rounded-xl transition-all">
                                            Connexion requise
                                        </a>
                                    @endauth

                                    @if($race->website_url)
                                        <a href="{{ $race->website_url }}" target="_blank"
                                           class="px-4 py-3 bg-gray-100 hover:bg-gray-200 rounded-xl transition-all">
                                            <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                            </svg>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $publicRaces->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
