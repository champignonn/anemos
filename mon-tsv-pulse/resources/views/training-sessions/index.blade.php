@extends('layouts.app')
@section('title', 'Mes Séances')

@section('content')
    <div class="max-w-7xl mx-auto px-6 lg:px-8 py-12">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-12">
            <div>
                <h1 class="text-5xl font-black text-gray-900 mb-3 tracking-tight">Mes Séances ️</h1>
                <p class="text-xl text-gray-600">Votre progression vers les sommets.</p>
                
        <form action="{{ route('training-sessions.index') }}" method="GET" id="filterForm" class="mb-10">
            <div class="flex flex-wrap gap-4">
                <div class="relative min-w-[200px]">
                    <select name="status" onchange="this.form.submit()"
                            class="w-full appearance-none px-6 py-4 bg-white border-2 border-gray-100 focus:border-violet-500 rounded-2xl outline-none font-bold text-gray-700 shadow-sm transition-all cursor-pointer">
                        <option value="">Tous les statuts</option>
                        <option value="réalisé" {{ request('status') == 'réalisé' ? 'selected' : '' }}>Réalisées ✓</option>
                        <option value="prévu" {{ request('status') == 'prévu' ? 'selected' : '' }}>À venir ⏳</option>
                        <option value="manqué" {{ request('status') == 'manqué' ? 'selected' : '' }}>Manquées ✗</option>
                    </select>
                </div>

                <div class="relative min-w-[200px]">
                    <select name="type" onchange="this.form.submit()"
                            class="w-full appearance-none px-6 py-4 bg-white border-2 border-gray-100 focus:border-violet-500 rounded-2xl outline-none font-bold text-gray-700 shadow-sm transition-all cursor-pointer">
                        <option value="">Tous les types</option>
                        <option value="VMA" {{ request('type') == 'VMA' ? 'selected' : '' }}>VMA / Seuil</option>
                        <option value="Sortie Longue" {{ request('type') == 'Sortie Longue' ? 'selected' : '' }}>Sortie Longue</option>
                        <option value="Trail Technique" {{ request('type') == 'Trail Technique' ? 'selected' : '' }}>Trail Technique</option>
                    </select>
                </div>

                @if(request()->hasAny(['status', 'type']))
                    <a href="{{ route('training-sessions.index') }}" class="px-6 py-4 text-violet-600 font-bold hover:underline flex items-center">
                        Réinitialiser
                    </a>
                @endif
            </div>
        </form>

        <div class="space-y-6">
            @forelse($sessions as $session)
                <div class="group relative bg-white rounded-3xl p-6 shadow-sm border border-gray-100 hover:shadow-xl transition-all duration-300">
                    <div class="absolute left-0 top-6 bottom-6 w-1.5 rounded-r-full {{ $session->status === 'réalisé' ? 'bg-emerald-500' : 'bg-violet-500' }}"></div>

                    <div class="flex flex-col lg:flex-row lg:items-center gap-6 pl-4">

                        <div class="flex items-center gap-4 min-w-[140px]">
                            <div class="w-14 h-14 rounded-2xl flex flex-col items-center justify-center bg-gray-50 group-hover:bg-violet-50 transition-colors">
                                <span class="text-xs font-black text-gray-400 uppercase leading-none">{{ $session->scheduled_date->translatedFormat('M') }}</span>
                                <span class="text-xl font-black text-gray-900 leading-tight">{{ $session->scheduled_date->format('d') }}</span>
                            </div>
                            <div class="lg:hidden">
                                <span class="text-sm font-black uppercase tracking-widest text-violet-600">{{ $session->type }}</span>
                            </div>
                        </div>

                        <div class="flex-1">
                            <div class="hidden lg:flex items-center gap-3 mb-1">
                                <span class="text-[10px] font-black uppercase tracking-[0.2em] text-violet-500">{{ $session->type }}</span>
                                @if($session->status === 'réalisé')
                                    <span class="px-2 py-0.5 bg-emerald-100 text-emerald-700 text-[10px] font-black rounded-full uppercase">Réalisée</span>
                                @endif
                            </div>
                            <h3 class="text-2xl font-black text-gray-900 group-hover:text-violet-600 transition-colors">
                                {{ $session->title }}
                            </h3>
                        </div>

                        <div class="flex items-center gap-8 px-6 py-3 bg-gray-50 rounded-2xl">
                            <div class="text-center">
                                <p class="text-[10px] font-black text-gray-400 uppercase">Km</p>
                                <p class="text-lg font-black text-gray-900">{{ $session->status === 'réalisé' ? $session->actual_distance : $session->planned_distance }}</p>
                            </div>
                            <div class="text-center border-x border-gray-200 px-6">
                                <p class="text-[10px] font-black text-gray-400 uppercase">D+</p>
                                <p class="text-lg font-black text-gray-900">{{ $session->status === 'réalisé' ? $session->actual_elevation : $session->planned_elevation }}</p>
                            </div>
                            <div class="text-center">
                                <p class="text-[10px] font-black text-gray-400 uppercase">Min</p>
                                <p class="text-lg font-black text-gray-900">{{ $session->status === 'réalisé' ? $session->actual_duration_minutes : $session->duration_minutes }}</p>
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <a href="{{ route('training-sessions.show', $session) }}"
                               class="p-4 bg-gray-900 text-white rounded-2xl hover:bg-black transition-all">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-20 bg-gray-50 rounded-3xl border-2 border-dashed border-gray-200">
                    <p class="text-gray-500 font-bold">Aucune séance ne correspond à vos filtres.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-10">
            {{ $sessions->appends(request()->query())->links() }}
        </div>
    </div>
@endsection
