@extends('layouts.app')
@section('title', 'Mes Courses')

@section('content')
    <div class="max-w-7xl mx-auto px-6 lg:px-8 py-12">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-12">
            <div>
                <h1 class="text-5xl font-black text-gray-900 mb-3 tracking-tighter">Mes Courses</h1>
                <p class="text-xl text-gray-600 font-medium">Gérez vos objectifs et suivez votre préparation.</p>
            </div>
            <a href="{{ route('races.create') }}"
               class="inline-flex items-center gap-3 bg-violet-600 hover:bg-violet-700 text-white font-black px-8 py-4 rounded-2xl transition-all transform hover:scale-105 shadow-[0_10px_20px_rgba(124,58,237,0.3)] uppercase tracking-wider text-sm">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                Nouvelle course
            </a>
        </div>

        @if($races->isEmpty())
            <div class="text-center py-24 bg-white rounded-[2.5rem] border-2 border-dashed border-gray-200">
                <h3 class="text-3xl font-black text-gray-900 mb-4">Aucune course programmée</h3>
                <p class="text-gray-500 mb-8 max-w-md mx-auto">Créez votre premier objectif pour lancer la machine.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($races as $race)
                    @php
                        // Correction mathématique pour éviter les décimales
                        $days = now()->startOfDay()->diffInDays($race->race_date->startOfDay(), false);
                    @endphp

                    <a href="{{ route('races.show', $race) }}"
                       class="group bg-white rounded-[2rem] overflow-hidden shadow-sm border border-gray-100 hover:shadow-2xl hover:border-violet-100 transition-all duration-300">

                        <div class="relative h-48 bg-gray-900 overflow-hidden">
                            <img src="https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800&q=80"
                                 class="w-full h-full object-cover opacity-50 group-hover:scale-105 transition-transform duration-700">

                            <div class="absolute top-4 right-4">
                            <span class="px-4 py-1.5 {{ $race->priority == 'A' ? 'bg-violet-600' : ($race->priority == 'B' ? 'bg-cyan-600' : 'bg-gray-500') }} text-white rounded-full text-[10px] font-black uppercase tracking-widest shadow-lg">
                                Priorité {{ $race->priority }}
                            </span>
                            </div>

                            <div class="absolute bottom-6 left-6 text-white">
                                <p class="text-[10px] font-black uppercase tracking-widest opacity-80 mb-0.5">Dans</p>
                                <p class="text-3xl font-black tracking-tight">
                                    @if($days < 0) Terminée @else {{ $days }} jours @endif
                                </p>
                            </div>
                        </div>

                        <div class="p-8">
                            <h3 class="text-2xl font-black text-gray-900 mb-4 group-hover:text-violet-600 transition-colors">
                                {{ $race->name }}
                            </h3>

                            <div class="flex items-center gap-6 mb-6">
                                <div class="flex items-center gap-2 text-sm font-bold text-gray-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    {{ $race->race_date->translatedFormat('d M') }}
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-gray-50 rounded-2xl p-4 text-center">
                                    <p class="text-[10px] font-black text-gray-400 uppercase">Distance</p>
                                    <p class="text-lg font-black text-gray-900">{{ $race->distance }}km</p>
                                </div>
                                <div class="bg-gray-50 rounded-2xl p-4 text-center">
                                    <p class="text-[10px] font-black text-gray-400 uppercase">Dénivelé</p>
                                    <p class="text-lg font-black text-gray-900">{{ $race->elevation_gain }}m</p>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
@endsection
