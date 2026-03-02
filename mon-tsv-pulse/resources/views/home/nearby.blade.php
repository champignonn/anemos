@extends('layouts.app')
@section('title', 'Courses à proximité')

@section('content')
    <div class="max-w-7xl mx-auto px-6 lg:px-8 py-12">

        <!-- Header -->
        <div class="mb-12">
            <a href="{{ route('home') }}" class="text-gray-900 hover:text-violet-600 inline-flex items-center gap-2 mb-4 font-semibold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Retour à toutes les courses
            </a>
            <h1 class="text-5xl font-black text-gray-900 mb-4">Courses à proximité 🗺️</h1>
            <p class="text-xl text-gray-600">Découvrez les trails autour de vous</p>
        </div>

        <!-- Configuration recherche -->
        <div class="bg-white rounded-2xl p-8 shadow-lg mb-12">
            <form method="GET" action="{{ route('home.nearby') }}" class="space-y-6">

                <!-- Localisation manuelle -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-2">Latitude</label>
                        <input type="number" step="0.0001" name="lat" value="{{ $lat }}"
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-violet-600 focus:ring-2 focus:ring-violet-100 outline-none">
                        <p class="text-xs text-gray-500 mt-1">Ex: 45.7640 (Lyon)</p>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-2">Longitude</label>
                        <input type="number" step="0.0001" name="lng" value="{{ $lng }}"
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-violet-600 focus:ring-2 focus:ring-violet-100 outline-none">
                        <p class="text-xs text-gray-500 mt-1">Ex: 4.8357 (Lyon)</p>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-2">Rayon (km)</label>
                        <select name="radius" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-violet-600 outline-none">
                            <option value="25" {{ $radius == 25 ? 'selected' : '' }}>25 km</option>
                            <option value="50" {{ $radius == 50 ? 'selected' : '' }}>50 km</option>
                            <option value="100" {{ $radius == 100 ? 'selected' : '' }}>100 km</option>
                            <option value="200" {{ $radius == 200 ? 'selected' : '' }}>200 km</option>
                        </select>
                    </div>
                </div>

                <!-- Villes prédéfinies -->
                <div>
                    <p class="text-sm font-bold text-gray-900 mb-3">Ou choisissez une ville :</p>
                    <div class="flex flex-wrap gap-3">
                        @php
                            $cities = [
                                ['name' => 'Lyon', 'lat' => 45.7640, 'lng' => 4.8357],
                                ['name' => 'Grenoble', 'lat' => 45.1885, 'lng' => 5.7245],
                                ['name' => 'Annecy', 'lat' => 45.8992, 'lng' => 6.1294],
                                ['name' => 'Chamonix', 'lat' => 45.9237, 'lng' => 6.8694],
                                ['name' => 'Gap', 'lat' => 44.5597, 'lng' => 6.0794],
                                ['name' => 'Briançon', 'lat' => 44.8978, 'lng' => 6.6434],
                            ];
                        @endphp
                        @foreach($cities as $city)
                            <a href="{{ route('home.nearby', ['lat' => $city['lat'], 'lng' => $city['lng'], 'radius' => $radius]) }}"
                               class="px-4 py-2 bg-gray-100 hover:bg-violet-100 text-gray-700 hover:text-violet-700 rounded-lg font-semibold transition-all">
                                📍 {{ $city['name'] }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <button type="submit"
                        class="w-full bg-violet-600 hover:bg-violet-700 text-white font-bold py-4 rounded-xl transition-all transform hover:scale-105 shadow-lg uppercase tracking-wider">
                    🔍 Rechercher
                </button>
            </form>
        </div>

        <!-- Résultats -->
        @if($races->isEmpty())
            <div class="bg-white rounded-2xl p-16 text-center shadow-lg">
                <svg class="w-24 h-24 mx-auto text-gray-300 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h3 class="text-3xl font-black text-gray-900 mb-4">Aucune course à proximité</h3>
                <p class="text-xl text-gray-600 mb-8">
                    Essayez d'augmenter le rayon de recherche ou changez de localisation.
                </p>
            </div>
        @else
            <div>
                <h2 class="text-3xl font-black text-gray-900 mb-6">
                    {{ $races->count() }} course(s) trouvée(s) dans un rayon de {{ $radius }}km
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($races as $race)
                        @php
                            $distance = $race->getDistanceFromUser($lat, $lng);
                        @endphp
                        <div class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all transform hover:-translate-y-2">

                            <!-- Image -->
                            <div class="relative h-48 bg-gradient-to-br from-gray-900 to-gray-700">
                                @if($race->image_url)
                                    <img src="{{ $race->image_url }}" class="w-full h-full object-cover opacity-80">
                                @else
                                    <img src="https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800&q=80"
                                         class="w-full h-full object-cover opacity-40">
                                @endif

                                <!-- Distance badge -->
                                <div class="absolute top-4 right-4">
                        <span class="px-4 py-2 bg-violet-600 text-white rounded-full text-sm font-black shadow-lg">
                            📍 {{ $distance }}km
                        </span>
                                </div>

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

                                <span class="inline-block px-3 py-1 bg-{{ $race->difficulty_badge_color }}-100 text-{{ $race->difficulty_badge_color }}-800 rounded-full text-xs font-black uppercase mb-4">
                        {{ $race->difficulty_level }}
                    </span>

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
            </div>
        @endif
    </div>
@endsection
