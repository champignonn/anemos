@extends('layouts.app')
@section('title', $user->name)

@section('content')
    <div class="max-w-7xl mx-auto px-6 lg:px-8 py-12">

        <!-- Header profil -->
        <div class="relative bg-gradient-to-br from-gray-900 to-gray-800 rounded-2xl p-8 md:p-12 text-white mb-8 shadow-2xl overflow-hidden">
            <div class="absolute inset-0 opacity-10"
                 style="background-image: url('https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1920&q=80'); background-size: cover;"></div>

            <div class="relative flex flex-col md:flex-row items-start md:items-center gap-6">
                <!-- Avatar -->
                <div class="relative">
                    @if($user->avatar_path)
                        <img src="{{ Storage::url($user->avatar_path) }}"
                             alt="{{ $user->name }}"
                             class="w-32 h-32 rounded-full object-cover border-4 border-white shadow-xl">
                    @else
                        <div class="w-32 h-32 rounded-full bg-violet-600 flex items-center justify-center text-5xl font-bold border-4 border-white shadow-xl">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                    @endif
                </div>

                <!-- Infos -->
                <div class="flex-1">
                    <h1 class="text-4xl font-black mb-2">{{ $user->name }}</h1>
                    @if($user->bio)
                        <p class="text-white/90 mb-4 text-lg">{{ $user->bio }}</p>
                    @endif

                    <!-- Stats -->
                    <div class="flex flex-wrap gap-6">
                        <div>
                            <p class="text-3xl font-black">{{ number_format($stats['total_distance'], 0) }}</p>
                            <p class="text-white/80 text-sm">Kilomètres</p>
                        </div>
                        <div>
                            <p class="text-3xl font-black">{{ number_format($stats['total_elevation'], 0) }}</p>
                            <p class="text-white/80 text-sm">D+ cumulé</p>
                        </div>
                        <div>
                            <p class="text-3xl font-black">{{ $stats['total_sessions'] }}</p>
                            <p class="text-white/80 text-sm">Séances</p>
                        </div>
                        <div>
                            <a href="{{ route('profile.followers', $user) }}" class="hover:opacity-80">
                                <p class="text-3xl font-black">{{ $stats['followers'] }}</p>
                                <p class="text-white/80 text-sm">Followers</p>
                            </a>
                        </div>
                        <div>
                            <a href="{{ route('profile.following', $user) }}" class="hover:opacity-80">
                                <p class="text-3xl font-black">{{ $stats['following'] }}</p>
                                <p class="text-white/80 text-sm">Suivis</p>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex flex-col gap-3">
                    @if($user->id === auth()->id())
                        <a href="{{ route('profile.edit') }}"
                           class="bg-white text-gray-900 font-bold px-6 py-3 rounded-xl hover:bg-gray-100 transition-all text-center shadow-lg">
                            Modifier mon profil
                        </a>
                    @else
                        @if($isFollowing)
                            <form method="POST" action="{{ route('profile.unfollow', $user) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="w-full bg-white/20 backdrop-blur-sm text-white font-bold px-6 py-3 rounded-xl hover:bg-white/30 transition-all border border-white/30">
                                    Ne plus suivre
                                </button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('profile.follow', $user) }}">
                                @csrf
                                <button type="submit"
                                        class="w-full bg-violet-600 hover:bg-violet-700 text-white font-bold px-6 py-3 rounded-xl transition-all shadow-lg">
                                    Suivre
                                </button>
                            </form>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        <!-- Métriques sportives -->
        @if($user->vma || $user->max_heart_rate)
            <div class="bg-white rounded-2xl p-8 shadow-lg mb-8">
                <h3 class="text-2xl font-black text-gray-900 mb-6">Métriques</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    @if($user->vma)
                        <div class="bg-violet-50 rounded-xl p-4 border border-violet-200">
                            <p class="text-sm text-gray-600 mb-1">VMA</p>
                            <p class="text-2xl font-black text-gray-900">{{ $user->vma }} km/h</p>
                        </div>
                    @endif
                    @if($user->endurance_pace)
                        <div class="bg-cyan-50 rounded-xl p-4 border border-cyan-200">
                            <p class="text-sm text-gray-600 mb-1">Allure EF</p>
                            <p class="text-2xl font-black text-gray-900">{{ $user->endurance_pace }} min/km</p>
                        </div>
                    @endif
                    @if($user->max_heart_rate)
                        <div class="bg-green-50 rounded-xl p-4 border border-green-200">
                            <p class="text-sm text-gray-600 mb-1">FC Max</p>
                            <p class="text-2xl font-black text-gray-900">{{ $user->max_heart_rate }} bpm</p>
                        </div>
                    @endif
                    @if($user->weight)
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                            <p class="text-sm text-gray-600 mb-1">Poids</p>
                            <p class="text-2xl font-black text-gray-900">{{ $user->weight }} kg</p>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Vlogs récents -->
        @if($recentVlogs->isNotEmpty())
            <div class="mb-8">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-black text-gray-900">Vlogs récents</h3>
                    @if($user->id === auth()->id())
                        <a href="{{ route('vlogs.index') }}" class="text-violet-600 font-bold hover:underline">
                            Voir tous les vlogs →
                        </a>
                    @endif
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($recentVlogs as $vlog)
                        <a href="{{ route('vlogs.show', $vlog) }}"
                           class="group bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all transform hover:-translate-y-2">
                            <div class="relative h-48 bg-gradient-to-br from-gray-900 to-gray-700 overflow-hidden">
                                @if($vlog->photo_paths && count($vlog->photo_paths) > 0)
                                    <img src="{{ Storage::url($vlog->photo_paths[0]) }}"
                                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <svg class="w-20 h-20 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="p-4">
                                <h4 class="font-black text-gray-900 group-hover:text-violet-600 transition-colors mb-2">
                                    {{ $vlog->title }}
                                </h4>
                                <p class="text-sm text-gray-600">
                                    {{ $vlog->published_at->diffForHumans() }}
                                </p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Prochaines courses -->
        @if($user->id === auth()->id() && $user->races->where('race_date', '>', now())->isNotEmpty())
            <div>
                <h3 class="text-2xl font-black text-gray-900 mb-6">Prochaines courses</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($user->races->where('race_date', '>', now())->take(4) as $race)
                        <a href="{{ route('races.show', $race) }}"
                           class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-2xl transition-all group border-l-4 border-violet-600">
                            <h4 class="text-xl font-black text-gray-900 group-hover:text-violet-600 mb-2">
                                {{ $race->name }}
                            </h4>
                            <div class="flex gap-4 text-sm text-gray-600">
                                <span>📅 {{ $race->race_date->format('d/m/Y') }}</span>
                                <span>🏃 {{ $race->distance }}km</span>
                                <span>⛰️ D+{{ $race->elevation_gain }}m</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endsection
