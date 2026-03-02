@extends('layouts.app')
@section('title', 'Timeline')

@section('content')
    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">

            <div class="flex flex-col md:flex-row justify-between items-end gap-6 mb-12">
                <div>
                    <h1 class="text-5xl font-black text-gray-900 mb-2 tracking-tight">Timeline</h1>
                    <p class="text-xl text-gray-600 font-semibold">Le souffle de la communauté Anemos</p>
                </div>
                <a href="{{ route('vlogs.create') }}"
                   class="bg-gray-900 hover:bg-violet-600 text-white font-black py-4 px-8 rounded-xl shadow-lg transition-all transform hover:scale-105 uppercase tracking-wider">
                    + Nouveau Vlog
                </a>
            </div>

            @if($vlogs->isEmpty())
                <div class="bg-white rounded-2xl p-16 text-center shadow-lg border border-gray-100">
                    <h3 class="text-2xl font-black text-gray-900">Aucun vlog pour le moment</h3>
                    <p class="text-gray-600 mt-2">Soyez le premier à partager votre aventure !</p>
                </div>
            @else
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <div class="lg:col-span-2 space-y-8">
                        @foreach($vlogs as $vlog)
                            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-2xl transition-all">
                                <div class="p-8 border-b border-gray-50 flex items-center justify-between">
                                    <a href="{{ route('profile.show', $vlog->user) }}" class="flex items-center gap-4 group">

                                        @if($vlog->user->avatar_path)
                                            <img src="{{ Storage::url($vlog->user->avatar_path) }}"
                                                 alt="{{ $vlog->user->name }}"
                                                 class="w-14 h-14 rounded-2xl object-cover shadow-md">
                                        @else
                                            <div class="w-14 h-14 bg-violet-600 rounded-2xl flex items-center justify-center text-white font-black text-xl shadow-md">
                                                {{ substr($vlog->user->name, 0, 1) }}
                                            </div>
                                        @endif

                                        <div>
                                            <p class="font-black text-gray-900 text-lg">{{ $vlog->user->name }}</p>
                                            <p class="text-xs font-black text-gray-400 uppercase tracking-widest">{{ $vlog->published_at->diffForHumans() }}</p>
                                        </div>
                                    </a>
                                    @if($vlog->trainingSession)
                                        <span class="px-4 py-1.5 bg-violet-50 text-violet-600 rounded-lg text-xs font-black uppercase tracking-wider">
                                            {{ $vlog->trainingSession->type }}
                                        </span>
                                    @endif
                                </div>

                                <div class="p-8">
                                    <a href="{{ route('vlogs.show', $vlog) }}">
                                        <h3 class="text-3xl font-black text-gray-900 mb-4 hover:text-violet-600 transition-colors">
                                            {{ $vlog->title }}
                                        </h3>
                                    </a>

                                    @if($vlog->content)
                                        <p class="text-gray-700 leading-relaxed text-lg mb-8">{{ Str::limit($vlog->content, 250) }}</p>
                                    @endif

                                    @if($vlog->trainingSession)
                                        <div class="grid grid-cols-3 gap-4 mb-8 bg-gray-50 p-6 rounded-2xl">
                                            <div><p class="text-[10px] text-gray-500 font-black uppercase">Distance</p><p class="text-xl font-black">{{ $vlog->trainingSession->actual_distance }}km</p></div>
                                            <div><p class="text-[10px] text-gray-500 font-black uppercase">Dénivelé</p><p class="text-xl font-black">{{ $vlog->trainingSession->actual_elevation }}m</p></div>
                                            <div><p class="text-[10px] text-gray-500 font-black uppercase">Temps</p><p class="text-xl font-black">{{ $vlog->trainingSession->actual_duration_minutes }}min</p></div>
                                        </div>
                                    @endif

                                    @if($vlog->photo_paths && count($vlog->photo_paths) > 0)
                                        <a href="{{ route('vlogs.show', $vlog) }}" class="block w-full h-80 rounded-2xl overflow-hidden mb-6 shadow-inner group">
                                            <img src="{{ Storage::url($vlog->photo_paths[0]) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                        </a>
                                    @endif

                                    <div class="flex items-center gap-6 pt-6 border-t border-gray-100">
                                        @auth
                                            <form method="POST" action="{{ route('vlogs.like', $vlog) }}">
                                                @csrf
                                                <button type="submit" class="flex items-center gap-2 group">
                                                    <svg class="w-6 h-6 {{ $vlog->likes->contains(auth()->id()) ? 'text-violet-600 fill-current' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                                    </svg>
                                                    <span class="font-black text-gray-900">{{ $vlog->likes_count }}</span>
                                                </button>
                                            </form>
                                        @endauth
                                        <div class="ml-auto">
                                            <a href="{{ route('vlogs.show', $vlog) }}" class="inline-flex items-center gap-2 text-violet-600 font-black hover:text-violet-800 transition-colors">
                                                Lire la suite →
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="space-y-8">
                        <div class="bg-gray-900 rounded-2xl p-8 text-white shadow-xl">
                            <h3 class="font-black text-xl mb-4">Envie de partager ?</h3>
                            <p class="text-gray-400 mb-6 font-medium">Votre dernier entraînement mérite d'être vu par la communauté.</p>
                            <a href="{{ route('vlogs.create') }}" class="block w-full py-3 bg-violet-600 text-center font-black rounded-xl">Poster un Vlog</a>
                        </div>
                    </div>
                </div>
                <div class="mt-12">{{ $vlogs->links() }}</div>
            @endif
        </div>
    </div>
@endsection
