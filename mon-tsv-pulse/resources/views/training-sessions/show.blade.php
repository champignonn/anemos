@extends('layouts.app')
@section('title', $trainingSession->title)

@section('content')
    <div class="max-w-5xl mx-auto px-6 py-12">

        <a href="{{ route('training-sessions.index') }}" class="inline-flex items-center text-gray-400 hover:text-violet-600 font-black uppercase tracking-widest text-xs mb-8 transition-all">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"/></svg>
            Retour aux séances
        </a>

        <div class="relative overflow-hidden bg-gradient-to-br from-violet-600 to-cyan-500 rounded-[2.5rem] p-10 text-white shadow-2xl mb-8">
            <div class="relative z-10">
                <div class="flex flex-wrap items-center gap-3 mb-6">
                <span class="px-4 py-1.5 bg-white/20 backdrop-blur-md rounded-full text-xs font-black uppercase tracking-widest border border-white/20">
                    {{ $trainingSession->type }}
                </span>
                    <span class="px-4 py-1.5 bg-black/20 backdrop-blur-md rounded-full text-xs font-black uppercase tracking-widest border border-white/10">
                    {{ ucfirst($trainingSession->status) }}
                </span>
                </div>
                <h1 class="text-5xl font-black mb-4 tracking-tighter">{{ $trainingSession->title }}</h1>
                <p class="text-xl font-medium text-white/80">{{ $trainingSession->scheduled_date->translatedFormat('d F Y') }}</p>
            </div>
            <div class="absolute -right-10 -bottom-10 opacity-20">
                <svg class="w-64 h-64" fill="currentColor" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-8">

                <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-100">
                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-6">Performances</h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                        @php
                            $stats = $trainingSession->status === 'réalisé'
                                ? ['Distance' => $trainingSession->actual_distance.'km', 'D+' => $trainingSession->actual_elevation.'m', 'Temps' => $trainingSession->actual_duration_minutes.'min']
                                : ['Distance' => $trainingSession->planned_distance.'km', 'D+' => $trainingSession->planned_elevation.'m', 'Temps' => $trainingSession->duration_minutes.'min'];
                        @endphp
                        @foreach($stats as $label => $value)
                            <div>
                                <p class="text-sm font-bold text-gray-500">{{ $label }}</p>
                                <p class="text-3xl font-black text-gray-900">{{ $value }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                @if($trainingSession->workout_structure)
                    <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-100">
                        <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-6">Structure</h3>
                        <div class="space-y-4">
                            @foreach(['warmup' => 'Échauffement', 'main' => 'Corps', 'cooldown' => 'Retour au calme'] as $key => $label)
                                @if(isset($trainingSession->workout_structure[$key]))
                                    <div class="flex gap-4">
                                        <div class="w-2 rounded-full bg-violet-500 shrink-0"></div>
                                        <div>
                                            <p class="text-sm font-black text-violet-600 uppercase">{{ $label }}</p>
                                            <p class="text-gray-700 font-medium">{{ $trainingSession->workout_structure[$key] }}</p>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <div class="space-y-6">
                @if($trainingSession->status === 'prévu')
                    <button onclick="document.getElementById('completeModal').classList.remove('hidden')"
                            class="w-full bg-violet-600 hover:bg-violet-700 text-white font-black py-4 rounded-2xl transition-all shadow-lg hover:shadow-violet-200 uppercase tracking-widest text-sm">
                        Valider la séance
                    </button>
                @endif

                <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-gray-100 space-y-3">
                    <a href="{{ route('training-sessions.edit', $trainingSession) }}" class="block w-full text-center py-3 bg-gray-100 hover:bg-gray-200 font-black rounded-xl transition-all">Modifier</a>
                    <form method="POST" action="{{ route('training-sessions.destroy', $trainingSession) }}" onsubmit="return confirm('Supprimer ?');">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full py-3 text-red-500 font-black rounded-xl hover:bg-red-50 transition-all">Supprimer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="completeModal" class="hidden fixed inset-0 bg-black/40 backdrop-blur-sm z-50 flex items-center justify-center p-6">
        <div class="bg-white rounded-[2.5rem] p-10 max-w-lg w-full shadow-2xl">
            <h3 class="text-3xl font-black mb-6">Valider la séance 🏁</h3>
            <form method="POST" action="{{ route('training-sessions.complete', $trainingSession) }}" class="space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <input type="number" step="0.1" name="actual_distance" placeholder="Distance (km)" value="{{ $trainingSession->planned_distance }}" class="w-full p-4 bg-gray-50 rounded-2xl font-bold">
                    <input type="number" name="actual_elevation" placeholder="D+ (m)" value="{{ $trainingSession->planned_elevation }}" class="w-full p-4 bg-gray-50 rounded-2xl font-bold">
                </div>
                <input type="number" name="actual_duration_minutes" placeholder="Durée (min)" value="{{ $trainingSession->duration_minutes }}" class="w-full p-4 bg-gray-50 rounded-2xl font-bold">
                <textarea name="notes" placeholder="Tes sensations ?" class="w-full p-4 bg-gray-50 rounded-2xl font-bold"></textarea>

                <button type="submit" class="w-full bg-violet-600 text-white font-black py-4 rounded-2xl">Enregistrer</button>
            </form>
        </div>
    </div>
@endsection
