@extends('layouts.app')
@section('title', $trainingPlan->name)

@section('content')
    <div class="max-w-5xl mx-auto px-6 py-12">

        <a href="{{ route('training-plans.index') }}" class="inline-flex items-center text-slate-400 hover:text-violet-600 font-black uppercase tracking-[0.2em] text-[10px] mb-8 transition-all">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"/></svg>
            Retour aux plans
        </a>

        <div class="bg-white border border-slate-100 rounded-[2rem] p-10 mb-8 shadow-sm">
            <div class="flex flex-wrap items-center gap-3 mb-6">
                <span class="px-3 py-1 bg-violet-50 text-violet-600 rounded-lg text-[10px] font-black uppercase tracking-[0.2em]">
                    Plan Actif
                </span>
                <span class="px-3 py-1 bg-slate-100 text-slate-500 rounded-lg text-[10px] font-black uppercase tracking-[0.2em]">
                    {{ $trainingPlan->total_weeks }} Semaines
                </span>
            </div>
            <h1 class="text-5xl font-black text-slate-900 mb-2 tracking-tighter">{{ $trainingPlan->name }}</h1>
            <p class="text-lg font-bold text-slate-400">Objectif : {{ $trainingPlan->race?->name ?? 'Course non définie' }}</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 mb-8">
            <div class="lg:col-span-3 bg-white rounded-[2rem] p-8 shadow-sm border border-slate-100">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Progression globale</h3>
                    <span class="text-2xl font-black text-violet-600">{{ number_format($trainingPlan->getProgressPercentage(), 0) }}%</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-3">
                    <div class="bg-violet-600 h-3 rounded-full transition-all duration-1000" style="width: {{ $trainingPlan->getProgressPercentage() }}%"></div>
                </div>
            </div>
            <div class="bg-violet-600 rounded-[2rem] p-8 flex flex-col justify-center items-center text-white shadow-lg">
                <p class="text-[10px] font-black uppercase tracking-widest opacity-70 mb-1">Phase actuelle</p>
                <p class="text-2xl font-black">{{ ucfirst($trainingPlan->getCurrentPhase()) }}</p>
            </div>
        </div>

        <div class="space-y-12">
            @php
                $sessionsByWeek = $trainingPlan->trainingSessions->groupBy(fn($s) => $s->scheduled_date->startOfWeek()->format('Y-m-d'));
            @endphp

            @foreach($sessionsByWeek as $weekStart => $sessions)
                <div class="space-y-6">
                    <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] flex items-center gap-3">
                        <span class="bg-slate-200 px-3 py-1 rounded-md text-slate-600">Semaine {{ $loop->iteration }}</span>
                        {{ \Carbon\Carbon::parse($weekStart)->translatedFormat('d M') }} – {{ \Carbon\Carbon::parse($weekStart)->addDays(6)->translatedFormat('d M') }}
                    </h4>

                    @foreach($sessions->sortBy('scheduled_date') as $session)
                        <div class="bg-white rounded-3xl p-6 border border-slate-100 flex items-center gap-6 hover:shadow-md transition-all">
                            <div class="w-16 h-16 rounded-2xl bg-slate-50 flex flex-col items-center justify-center shrink-0">
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">{{ $session->scheduled_date->format('M') }}</span>
                                <span class="text-xl font-black text-slate-900">{{ $session->scheduled_date->format('d') }}</span>
                            </div>

                            <div class="flex-1">
                                <span class="text-[9px] font-black text-violet-600 uppercase tracking-widest">{{ $session->type }}</span>
                                <h5 class="text-lg font-black text-slate-900">{{ $session->title }}</h5>
                            </div>

                            <div class="flex gap-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                <div class="text-center"><span>{{ $session->planned_distance }}km</span></div>
                                <div class="text-center"><span>{{ $session->planned_elevation }}m D+</span></div>
                                <div class="text-center"><span>{{ $session->duration_minutes }}min</span></div>
                            </div>

                            <a href="{{ route('training-sessions.show', $session) }}" class="p-3 bg-slate-100 hover:bg-slate-900 hover:text-white rounded-xl transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
@endsection
