@extends('layouts.app')
@section('title', 'Mes Vlogs')

@section('content')
    <div class="max-w-7xl mx-auto px-6 lg:px-8 py-12">

        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-12">
            <div>
                <h1 class="text-5xl font-black text-gray-900 mb-3">Mes Vlogs</h1>
                <p class="text-xl text-gray-600">Partagez vos aventures trail avec la communauté</p>
            </div>
            <a href="{{ route('vlogs.create') }}"
               class="inline-flex items-center gap-3 bg-violet-600 hover:bg-violet-700 text-white font-bold px-8 py-4 rounded-xl transition-all transform hover:scale-105 shadow-lg uppercase tracking-wider">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nouveau vlog
            </a>
        </div>

        @if($vlogs->isEmpty())
            <!-- Empty State -->
            <div class="bg-gradient-to-br from-violet-50 to-cyan-50 rounded-3xl p-16 text-center shadow-xl border-2 border-violet-200">
                <div class="max-w-2xl mx-auto">
                    <div class="w-32 h-32 bg-violet-100 rounded-full flex items-center justify-center mx-auto mb-8 animate-float">
                        <svg class="w-16 h-16 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-4xl font-black text-gray-900 mb-4">Aucun vlog pour le moment</h3>
                    <p class="text-xl text-gray-600 mb-8">
                        Documentez vos sorties avec photos et vidéos !<br>
                        Partagez vos expériences, vos sensations et inspirez la communauté.
                    </p>
                    <a href="{{ route('vlogs.create') }}"
                       class="inline-flex items-center gap-3 bg-violet-600 hover:bg-violet-700 text-white font-bold px-10 py-5 rounded-xl transition-all transform hover:scale-105 shadow-lg text-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Créer mon premier vlog
                    </a>
                </div>
            </div>
        @else
            <!-- Grille des vlogs -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($vlogs as $vlog)
                    <div class="group bg-white rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl transition-all transform hover:-translate-y-2">

                        <!-- Image de couverture -->
                        <div class="relative h-56 overflow-hidden">
                            @if($vlog->photo_paths && count($vlog->photo_paths) > 0)
                                <img src="{{ Storage::url($vlog->photo_paths[0]) }}"
                                     alt="{{ $vlog->title }}"
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-violet-500 to-cyan-500 flex items-center justify-center">
                                    <svg class="w-20 h-20 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            @endif

                            <!-- Overlay gradient -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>

                            <!-- Badges -->
                            <div class="absolute top-4 right-4 flex flex-col gap-2">
                                @if($vlog->visibility === 'private')
                                    <span class="px-3 py-1.5 bg-gray-900/80 backdrop-blur-sm text-white text-xs font-bold rounded-full border border-white/20">
                        🔒 Privé
                    </span>
                                @elseif($vlog->visibility === 'followers')
                                    <span class="px-3 py-1.5 bg-violet-600/80 backdrop-blur-sm text-white text-xs font-bold rounded-full border border-white/20">
                        👥 Followers
                    </span>
                                @else
                                    <span class="px-3 py-1.5 bg-green-600/80 backdrop-blur-sm text-white text-xs font-bold rounded-full border border-white/20">
                        🌍 Public
                    </span>
                                @endif

                                @if($vlog->photo_paths && count($vlog->photo_paths) > 1)
                                    <span class="px-3 py-1.5 bg-black/60 backdrop-blur-sm text-white text-xs font-bold rounded-full">
                        📸 {{ count($vlog->photo_paths) }}
                    </span>
                                @endif
                            </div>

                            <!-- Date -->
                            <div class="absolute bottom-4 left-4">
                    <span class="px-3 py-1.5 bg-black/60 backdrop-blur-sm text-white text-sm font-bold rounded-full">
                        {{ $vlog->published_at->format('d M Y') }}
                    </span>
                            </div>
                        </div>

                        <!-- Contenu -->
                        <div class="p-6">
                            <a href="{{ route('vlogs.show', $vlog) }}">
                                <h3 class="text-2xl font-black text-gray-900 mb-3 group-hover:text-violet-600 transition-colors line-clamp-2">
                                    {{ $vlog->title }}
                                </h3>
                            </a>

                            @if($vlog->trainingSession)
                                <div class="flex items-center gap-3 text-sm text-gray-600 mb-3 font-semibold">
                    <span class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        {{ $vlog->trainingSession->scheduled_date->format('d/m/Y') }}
                    </span>
                                    @if($vlog->trainingSession->actual_distance)
                                        <span class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                        {{ $vlog->trainingSession->actual_distance }}km
                    </span>
                                    @endif
                                </div>
                            @endif

                            @if($vlog->content)
                                <p class="text-gray-700 leading-relaxed mb-4 line-clamp-3">
                                    {{ $vlog->content }}
                                </p>
                            @endif

                            @if($vlog->tags && count($vlog->tags) > 0)
                                <div class="flex flex-wrap gap-2 mb-4">
                                    @foreach(array_slice($vlog->tags, 0, 3) as $tag)
                                        <span class="px-2 py-1 bg-violet-100 text-violet-700 text-xs font-bold rounded-full">
                        #{{ $tag }}
                    </span>
                                    @endforeach
                                </div>
                            @endif

                            <!-- Stats & Mood -->
                            <div class="flex items-center justify-between pt-4 border-t-2 border-gray-100">
                                <div class="flex items-center gap-4">
                        <span class="flex items-center gap-1 text-sm font-semibold text-gray-600">
                            <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                            </svg>
                            {{ $vlog->likes_count }}
                        </span>
                                    <span class="flex items-center gap-1 text-sm font-semibold text-gray-600">
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            {{ $vlog->views_count }}
                        </span>
                                </div>

                                @if($vlog->mood)
                                    <span class="text-2xl">
                        @switch($vlog->mood)
                                            @case('excellent') 😄 @break
                                            @case('bien') 😊 @break
                                            @case('moyen') 😐 @break
                                            @case('difficile') 😓 @break
                                            @case('terrible') 😫 @break
                                        @endswitch
                    </span>
                                @endif
                            </div>

                            <!-- Actions -->
                            <div class="flex gap-3 mt-4">
                                <a href="{{ route('vlogs.show', $vlog) }}"
                                   class="flex-1 bg-violet-600 hover:bg-violet-700 text-white font-bold py-3 rounded-xl transition-all text-center">
                                    Voir le vlog
                                </a>
                                <a href="{{ route('vlogs.edit', $vlog) }}"
                                   class="px-4 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-12">
                {{ $vlogs->links() }}
            </div>
        @endif
    </div>
@endsection
