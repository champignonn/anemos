@extends('layouts.app')
@section('title', $vlog->title)

@section('content')
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-6">
            <a href="{{ route('vlogs.index') }}" class="text-moss hover:text-auburn inline-flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Retour aux vlogs
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Contenu principal -->
            <div class="lg:col-span-2 space-y-8">

                <!-- Header -->
                <div class="bg-white rounded-2xl p-8 shadow-lg">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-4">
                            <a href="{{ route('profile.show', $vlog->user) }}" class="flex items-center gap-3 group">
                                <div class="w-12 h-12 bg-auburn rounded-full flex items-center justify-center text-white font-bold text-lg">
                                    {{ substr($vlog->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-bold text-moss group-hover:text-auburn transition-colors">
                                        {{ $vlog->user->name }}
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        {{ $vlog->published_at->diffForHumans() }}
                                    </p>
                                </div>
                            </a>
                        </div>

                        @if($vlog->user_id === auth()->id())
                            <div class="flex gap-2">
                                <a href="{{ route('vlogs.edit', $vlog) }}"
                                   class="p-2 text-gray-600 hover:text-moss transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('vlogs.destroy', $vlog) }}"
                                      onsubmit="return confirm('Supprimer ce vlog ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-gray-600 hover:text-auburn transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>

                    <h1 class="text-3xl font-serif font-bold text-moss mb-4">{{ $vlog->title }}</h1>

                    @if($vlog->content)
                        <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $vlog->content }}</p>
                    @endif

                    <!-- Tags -->
                    @if($vlog->tags)
                        <div class="flex flex-wrap gap-2 mt-4">
                            @foreach($vlog->tags as $tag)
                                <span class="px-3 py-1 bg-olivine/10 text-olivine rounded-full text-sm font-semibold">
                        #{{ $tag }}
                    </span>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Photos -->
                @if($vlog->photo_paths && count($vlog->photo_paths) > 0)
                    <div class="bg-white rounded-2xl p-6 shadow-lg">
                        <h3 class="text-xl font-bold text-moss mb-4">Photos</h3>
                        <div class="grid grid-cols-2 gap-4">
                            @foreach($vlog->photo_paths as $photo)
                                <img src="{{ Storage::url($photo) }}"
                                     alt="Photo vlog"
                                     class="w-full h-64 object-cover rounded-xl cursor-pointer hover:opacity-90 transition-opacity"
                                     onclick="openLightbox('{{ Storage::url($photo) }}')">
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Vidéo -->
                @if($vlog->video_url)
                    <div class="bg-white rounded-2xl p-6 shadow-lg">
                        <h3 class="text-xl font-bold text-moss mb-4">Vidéo</h3>
                        <div class="aspect-video bg-gray-100 rounded-xl overflow-hidden">
                            @if(str_contains($vlog->video_url, 'youtube.com') || str_contains($vlog->video_url, 'youtu.be'))
                                @php
                                    preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $vlog->video_url, $matches);
                                    $videoId = $matches[1] ?? null;
                                @endphp
                                @if($videoId)
                                    <iframe class="w-full h-full"
                                            src="https://www.youtube.com/embed/{{ $videoId }}"
                                            frameborder="0"
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                            allowfullscreen>
                                    </iframe>
                                @endif
                            @else
                                <a href="{{ $vlog->video_url }}" target="_blank"
                                   class="flex items-center justify-center h-full text-auburn hover:text-auburn/80">
                                    <svg class="w-20 h-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </a>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Actions -->
                <div class="bg-white rounded-2xl p-6 shadow-lg">
                    <div class="flex items-center gap-6">
                        <form method="POST" action="{{ route('vlogs.like', $vlog) }}">
                            @csrf
                            <button type="submit" class="flex items-center gap-2 transition-transform hover:scale-105 active:scale-95">
                                <svg class="w-8 h-8 transition-colors duration-300
                    {{ $vlog->likes->contains(auth()->id()) ? 'text-violet-600 fill-current' : 'text-gray-400 hover:text-auburn' }}"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                                <span class="font-bold text-lg {{ $vlog->likes->contains(auth()->id()) ? 'text-violet-600' : 'text-gray-600' }}">
                    {{ $vlog->likes_count }}
                </span>
                            </button>
                        </form>

                        <div class="flex items-center gap-2 text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <span class="font-semibold">{{ $vlog->views_count }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">

                <!-- Séance associée -->
                @if($vlog->trainingSession)
                    <div class="bg-white rounded-2xl p-6 shadow-lg">
                        <h4 class="font-bold text-moss mb-4">Séance associée</h4>
                        <a href="{{ route('training-sessions.show', $vlog->trainingSession) }}"
                           class="block p-4 border-2 border-gray-200 rounded-xl hover:border-olivine transition-all">
                            <p class="font-bold text-moss mb-2">{{ $vlog->trainingSession->title }}</p>
                            <div class="text-sm text-gray-600 space-y-1">
                                <p>📅 {{ $vlog->trainingSession->scheduled_date->format('d/m/Y') }}</p>
                                <p>🏃 {{ $vlog->trainingSession->actual_distance }}km</p>
                                <p>⛰️ D+{{ $vlog->trainingSession->actual_elevation }}m</p>
                                <p>⏱️ {{ $vlog->trainingSession->actual_duration_minutes }}min</p>
                            </div>
                        </a>
                    </div>
                @endif

                <!-- Humeur -->
                @if($vlog->mood)
                    <div class="bg-white rounded-2xl p-6 shadow-lg">
                        <h4 class="font-bold text-moss mb-3">Humeur</h4>
                        <div class="flex items-center gap-3">
                    <span class="text-4xl">
                        @switch($vlog->mood)
                            @case('excellent') 😄 @break
                            @case('bien') 😊 @break
                            @case('moyen') 😐 @break
                            @case('difficile') 😓 @break
                            @case('terrible') 😫 @break
                        @endswitch
                    </span>
                            <span class="text-lg font-semibold text-gray-700">
                        {{ ucfirst($vlog->mood) }}
                    </span>
                        </div>
                    </div>
                @endif

                <!-- Visibilité -->
                <div class="bg-white rounded-2xl p-6 shadow-lg">
                    <h4 class="font-bold text-moss mb-3">Visibilité</h4>
                    <p class="text-gray-700">
                        @if($vlog->visibility === 'public')
                            🌍 Public - Visible par tous
                        @elseif($vlog->visibility === 'followers')
                            👥 Followers - Visible par vos abonnés
                        @else
                            🔒 Privé - Visible par vous uniquement
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Lightbox photos -->
    <div id="lightbox" class="hidden fixed inset-0 bg-black/90 z-50 flex items-center justify-center p-4" onclick="closeLightbox()">
        <img id="lightboxImage" src="" class="max-w-full max-h-full object-contain">
        <button onclick="closeLightbox()" class="absolute top-4 right-4 text-white hover:text-gray-300">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <script>
        function openLightbox(src) {
            document.getElementById('lightboxImage').src = src;
            document.getElementById('lightbox').classList.remove('hidden');
        }

        function closeLightbox() {
            document.getElementById('lightbox').classList.add('hidden');
        }
    </script>
@endsection
