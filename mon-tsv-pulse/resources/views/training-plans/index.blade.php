@extends('layouts.app')
@section('title', 'Mes Plans')

@section('content')
    <div class="max-w-7xl mx-auto px-6 lg:px-8 py-12">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-12">
            <div>
                <h1 class="font-display text-5xl font-black text-gray-900 mb-2 leading-tight">
                    Mes Plans <span class="text-violet-600">d'Entraînement</span>
                </h1>
                <p class="text-lg text-gray-600 font-medium">Suivez votre progression et atteignez vos objectifs de course.</p>
            </div>

            @if(!$plans->isEmpty())
                <a href="{{ route('races.index') }}" class="bg-violet-600 hover:bg-violet-700 text-white font-bold px-8 py-4 rounded-2xl transition-all transform hover:scale-105 shadow-lg shadow-violet-200 uppercase tracking-wider text-sm">
                    + Nouveau Plan
                </a>
            @endif
        </div>

        @if($plans->isEmpty())
            <div class="bg-white rounded-3xl p-16 text-center shadow-xl border-2 border-dashed border-gray-200">
                <div class="w-24 h-24 bg-violet-50 rounded-full flex items-center justify-center mx-auto mb-8">
                    <svg class="w-12 h-12 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <h3 class="text-3xl font-black text-gray-900 mb-4">Aucun plan actif</h3>
                <p class="text-xl text-gray-600 mb-10 max-w-md mx-auto">Générez un plan d'entraînement personnalisé directement depuis la fiche d'une de vos courses.</p>
                <a href="{{ route('races.index') }}" class="inline-block bg-gray-900 hover:bg-black text-white px-10 py-4 rounded-2xl font-black transition-all shadow-xl uppercase tracking-widest">
                    Voir mes courses
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($plans as $plan)
                    <div class="group relative bg-white rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100 overflow-hidden flex flex-col">

                        <div class="h-3 w-full {{ $plan->status === 'actif' ? 'bg-violet-600' : 'bg-gray-300' }}"></div>

                        <div class="p-8 flex-1">
                            <div class="flex justify-between items-start mb-6">
                                <span class="px-3 py-1 {{ $plan->status === 'actif' ? 'bg-violet-100 text-violet-700' : 'bg-gray-100 text-gray-600' }} rounded-lg text-xs font-black uppercase tracking-tighter">
                                    {{ $plan->status }}
                                </span>
                                @if($plan->status === 'actif')
                                    <span class="text-2xl font-black text-violet-600">
                                        {{ number_format($plan->getProgressPercentage(), 0) }}%
                                    </span>
                                @endif
                            </div>

                            <h3 class="text-2xl font-black text-gray-900 group-hover:text-violet-600 transition-colors mb-2 leading-tight">
                                {{ $plan->name }}
                            </h3>

                            <p class="text-gray-500 font-bold text-sm mb-6 flex items-center gap-2">
                                <svg class="w-4 h-4 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                </svg>
                                {{ $plan->race?->name ?? 'Course non définie' }}
                            </p>

                            @if($plan->status === 'actif')
                                <div class="w-full bg-gray-100 rounded-full h-2 mb-8">
                                    <div class="bg-violet-600 h-2 rounded-full transition-all duration-1000" style="width: {{ $plan->getProgressPercentage() }}%"></div>
                                </div>
                            @endif

                            <div class="grid grid-cols-2 gap-4 mb-8">
                                <div class="bg-gray-50 rounded-2xl p-4">
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Durée</p>
                                    <p class="text-lg font-black text-gray-900">{{ $plan->total_weeks }} <span class="text-xs">sem.</span></p>
                                </div>
                                <div class="bg-gray-50 rounded-2xl p-4">
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Séances</p>
                                    <p class="text-lg font-black text-gray-900">{{ $plan->trainingSessions()->count() }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="px-8 pb-8">
                            <a href="{{ route('training-plans.show', $plan) }}"
                               class="flex items-center justify-center gap-3 w-full bg-gray-900 hover:bg-violet-600 text-white font-black py-4 rounded-2xl transition-all shadow-lg group-hover:shadow-violet-200">
                                OUVRIR LE PLAN
                                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
