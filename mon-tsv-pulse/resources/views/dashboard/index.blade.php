@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
    <div class="min-h-screen bg-gray-50">

        <!-- Hero Section avec prochaine course -->
        @if($nextRace)
            <div class="relative bg-gray-900 overflow-hidden">
                <div class="absolute inset-0">
                    <img src="https://images.unsplash.com/photo-1551632811-561732d1e306?w=1920&q=80"
                         alt="Trail running"
                         class="w-full h-full object-cover opacity-30">
                </div>

                <div class="relative max-w-7xl mx-auto px-6 lg:px-8 py-16">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-center">
                        <!-- Info course -->
                        <div class="lg:col-span-2 text-white animate-fade-in-up">
                            <div class="inline-block px-4 py-2 bg-violet-600/20 backdrop-blur-sm rounded-full mb-4 border border-violet-600/30">
                                <span class="text-sm font-bold uppercase tracking-wider">🏔️ Prochaine Course</span>
                            </div>

                            <h1 class="font-display text-5xl md:text-6xl font-black mb-4 leading-tight">
                                {{ $nextRace->name }}
                            </h1>

                            <div class="flex flex-wrap gap-4 mb-6 text-lg">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    {{ $nextRace->race_date->format('d F Y') }}
                                </div>
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    </svg>
                                    {{ $nextRace->location ?? 'Lieu à définir' }}
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4 max-w-md">
                                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                                    <p class="text-sm text-gray-300 mb-1">Distance</p>
                                    <p class="text-3xl font-black">{{ $nextRace->distance }} km</p>
                                </div>
                                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                                    <p class="text-sm text-gray-300 mb-1">Dénivelé</p>
                                    <p class="text-3xl font-black">D+ {{ $nextRace->elevation_gain }}m</p>
                                </div>
                            </div>
                        </div>

                        <!-- Compte à rebours -->
                        <div class="bg-white rounded-2xl p-8 text-center shadow-2xl animate-fade-in-up delay-200 border border-gray-100">
                            <div class="relative inline-flex items-center justify-center">
                                <div class="text-6xl font-black text-slate-900 z-10"
                                     x-data="countdown('{{ $nextRace->race_date->toIso8601String() }}')"
                                     x-text="days">
                                </div>
                            </div>
                            <p class="text-sm font-black text-slate-400 uppercase tracking-[0.2em] mt-4">
                                Jours avant départ
                            </p>

                            @if($nextRace->target_time)
                                <div class="mt-6 pt-6 border-t border-slate-100">
                                    <div class="flex items-center justify-center gap-2 text-violet-600 mb-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span class="text-[10px] font-black uppercase tracking-widest">Objectif</span>
                                    </div>
                                    <p class="text-2xl font-black text-slate-900">{{ $nextRace->target_time->format('H:i') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="max-w-7xl mx-auto px-6 lg:px-8 py-12">

            <!-- Stats cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                @php
                    $stats = [
                        ['icon' => 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6', 'value' => number_format($statistics['total_distance'], 0), 'label' => 'Kilomètres', 'color' => 'violet', 'bg' => 'violet-100'],
                        ['icon' => 'M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z', 'value' => number_format($statistics['total_elevation'], 0) . 'm', 'label' => 'Dénivelé', 'color' => 'cyan', 'bg' => 'cyan-100'],
                        ['icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'value' => $statistics['completion_rate'] . '%', 'label' => 'Réussite', 'color' => 'green', 'bg' => 'green-100'],
                        ['icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'value' => $statistics['total_sessions'], 'label' => 'Séances', 'color' => 'gray', 'bg' => 'gray-100'],
                    ];
                @endphp

                @foreach($stats as $index => $stat)
                    <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-2xl transition-all transform hover:-translate-y-1 border border-gray-100 animate-fade-in-up" style="animation-delay: {{ $index * 0.1 }}s; opacity: 0;">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-14 h-14 bg-{{ $stat['bg'] }} rounded-xl flex items-center justify-center">
                                <svg class="w-7 h-7 text-{{ $stat['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $stat['icon'] }}"/>
                                </svg>
                            </div>
                            <div class="text-right">
                                <div class="text-4xl font-black text-gray-900">{{ $stat['value'] }}</div>
                            </div>
                        </div>
                        <h3 class="text-sm font-bold text-gray-600 uppercase tracking-wide">{{ $stat['label'] }}</h3>
                    </div>
                @endforeach
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <!-- Colonne principale -->
                <div class="lg:col-span-2 space-y-8">

                    <!-- Graphique progression -->
                    <div class="bg-white rounded-2xl p-8 shadow-lg">
                        <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-8 gap-4">
                            <div>
                                <h2 class="text-3xl font-black text-gray-900 mb-2">Progression</h2>
                                <div class="flex items-center gap-2">
                                    <button onclick="changeWeek(-1)" class="p-1 hover:bg-gray-100 rounded-full transition-colors">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                                    </button>
                                    <span id="currentWeekRange" class="text-sm font-bold text-gray-600 min-w-[150px] text-center italic">Cette semaine</span>
                                    <button onclick="changeWeek(1)" class="p-1 hover:bg-gray-100 rounded-full transition-colors">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    </button>
                                </div>
                            </div>
                            <select id="chartType" class="px-4 py-2 border-2 border-gray-100 rounded-xl font-bold text-sm outline-none bg-white">
                                <option value="combined">📊 Combiné</option>
                                <option value="distance">🏃 Distance</option>
                                <option value="elevation">⛰️ Dénivelé</option>
                            </select>
                        </div>

                        <div class="bg-gray-50 rounded-xl p-4">
                            <div style="position: relative; height: 300px;"> <canvas id="progressChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Prochaines séances -->
                    @if($upcomingSessions && $upcomingSessions->isNotEmpty())
                        <div class="bg-white rounded-2xl p-8 shadow-lg animate-fade-in-up delay-300">
                            <div class="flex justify-between items-center mb-8">
                                <div>
                                    <h2 class="text-3xl font-black text-gray-900 mb-2">Prochaines séances</h2>
                                    <p class="text-gray-600">Vos entraînements à venir</p>
                                </div>
                                @if($activeTrainingPlan)
                                    <a href="{{ route('training-plans.show', $activeTrainingPlan) }}"
                                       class="text-violet-600 font-bold hover:underline text-sm flex items-center gap-2">
                                        Voir le plan
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                @endif
                            </div>
                            <div class="space-y-4">
                                @foreach($upcomingSessions->take(5) as $session)
                                    <a href="{{ route('training-sessions.show', $session) }}"
                                       class="block p-5 rounded-2xl transition-all hover:shadow-lg border-l-4 hover:-translate-y-1"
                                       style="border-color: {{ $session->intensity_color }}; background: linear-gradient(135deg, {{ $session->intensity_color }}08 0%, {{ $session->intensity_color }}15 100%);">
                                        <div class="flex justify-between items-center">
                                            <div class="flex-1">
                                                <div class="flex items-center gap-3 mb-2">
                                        <span class="px-3 py-1 rounded-lg text-xs font-black text-white"
                                              style="background: {{ $session->intensity_color }}">
                                            {{ $session->type }}
                                        </span>
                                                    <span class="text-sm text-gray-500 font-semibold">
                                            📅 {{ $session->scheduled_date->format('d/m') }}
                                        </span>
                                                </div>
                                                <h5 class="font-black text-gray-900 mb-2 text-lg">
                                                    {{ $session->title }}
                                                </h5>
                                                <div class="flex gap-4 text-sm text-gray-600 font-semibold">
                                                    <span>🏃 {{ $session->planned_distance }}km</span>
                                                    <span>⛰️ D+{{ $session->planned_elevation }}m</span>
                                                    <span>⏱️ {{ $session->duration_minutes }}min</span>
                                                </div>
                                            </div>
                                            <svg class="w-8 h-8 text-gray-400 group-hover:text-violet-600 transition-colors"
                                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                            </svg>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="space-y-8">

                    <!-- Cette semaine -->
                    <div class="relative overflow-hidden rounded-2xl shadow-lg animate-fade-in-up delay-100">
                        <div class="absolute inset-0 bg-gradient-to-br from-gray-900 to-gray-800"></div>
                        <div class="absolute inset-0 opacity-10"
                             style="background-image: url('https://images.unsplash.com/photo-1519904981063-b0cf448d479e?w=800&q=80'); background-size: cover;"></div>
                        <div class="relative p-8 text-white">
                            <div class="flex items-center gap-3 mb-6">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <h3 class="text-2xl font-black">Cette semaine</h3>
                            </div>
                            <div class="space-y-4">
                                <div class="flex justify-between items-center bg-white/10 backdrop-blur-sm rounded-xl p-4">
                                    <span class="font-semibold">Séances</span>
                                    <span class="text-3xl font-black">{{ $weeklyStats['sessions_count'] }}</span>
                                </div>
                                <div class="flex justify-between items-center bg-white/10 backdrop-blur-sm rounded-xl p-4">
                                    <span class="font-semibold">Distance</span>
                                    <span class="text-3xl font-black">{{ number_format($weeklyStats['distance'], 0) }}km</span>
                                </div>
                                <div class="flex justify-between items-center bg-white/10 backdrop-blur-sm rounded-xl p-4">
                                    <span class="font-semibold">D+</span>
                                    <span class="text-3xl font-black">{{ number_format($weeklyStats['elevation'], 0) }}m</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Plan actif -->
                    @if($activeTrainingPlan)
                        <div class="bg-white rounded-2xl p-6 shadow-lg animate-fade-in-up delay-200">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-black text-gray-900">Plan actif</h3>
                            </div>
                            <p class="text-gray-900 font-bold mb-4 text-lg">{{ $activeTrainingPlan->name }}</p>

                            <div class="mb-4">
                                <div class="flex justify-between text-sm font-semibold text-gray-600 mb-2">
                                    <span>Semaine {{ $activeTrainingPlan->getCurrentWeekNumber() }}/{{ $activeTrainingPlan->total_weeks }}</span>
                                    <span class="text-violet-600">{{ number_format($activeTrainingPlan->getProgressPercentage(), 0) }}%</span>
                                </div>
                                <div class="relative w-full bg-gray-200 rounded-full h-4 overflow-hidden">
                                    <div class="absolute inset-0 bg-gradient-to-r from-violet-600 to-cyan-600 h-full rounded-full transition-all duration-1000"
                                         style="width: {{ $activeTrainingPlan->getProgressPercentage() }}%"></div>
                                </div>
                            </div>

                            <div class="flex items-center gap-2 text-sm mb-4">
                        <span class="px-3 py-1 bg-gray-900 text-white rounded-lg font-bold">
                            Phase: {{ $activeTrainingPlan->getCurrentPhase() }}
                        </span>
                            </div>

                            <a href="{{ route('training-plans.show', $activeTrainingPlan) }}"
                               class="block w-full bg-gradient-to-r from-violet-600 to-violet-700 hover:from-violet-700 hover:to-violet-800 text-white text-center font-bold py-3 rounded-xl transition-all transform hover:scale-105 shadow-lg">
                                Voir le plan
                            </a>
                        </div>
                    @endif

                    <!-- Actions rapides -->
                    <div class="bg-white rounded-2xl p-6 shadow-lg animate-fade-in-up delay-300">
                        <h4 class="font-black text-gray-900 mb-4 text-lg flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            Actions rapides
                        </h4>
                        <div class="space-y-3">
                            <a href="{{ route('races.create') }}"
                               class="flex items-center gap-3 p-4 rounded-xl hover:bg-violet-50 transition-all border-2 border-transparent hover:border-violet-600">
                                <div class="w-10 h-10 bg-violet-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                </div>
                                <span class="font-bold text-gray-700">Nouvelle course</span>
                            </a>
                            <a href="{{ route('vlogs.create') }}"
                               class="flex items-center gap-3 p-4 rounded-xl hover:bg-cyan-50 transition-all border-2 border-transparent hover:border-cyan-600">
                                <div class="w-10 h-10 bg-cyan-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <span class="font-bold text-gray-700">Créer un vlog</span>
                            </a>
                            <a href="{{ route('vlogs.feed') }}"
                               class="flex items-center gap-3 p-4 rounded-xl hover:bg-green-50 transition-all border-2 border-transparent hover:border-green-600">
                                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                </div>
                                <span class="font-bold text-gray-700">Timeline</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            let progressChart = null;
            let currentOffset = 0;
            let rawData = [];

            function initChart(data) {
                const ctx = document.getElementById('progressChart').getContext('2d');
                const chartType = document.getElementById('chartType').value;
                if (progressChart) progressChart.destroy();

                const datasets = [];
                if (chartType === 'combined' || chartType === 'distance') {
                    datasets.push({
                        label: 'Distance (km)',
                        data: data.map(d => d.distance),
                        backgroundColor: '#7c3aed',
                        borderRadius: 8,
                        yAxisID: 'y',
                        barThickness: 10,
                    });
                }
                if (chartType === 'combined' || chartType === 'elevation') {
                    datasets.push({
                        label: 'Dénivelé (m)',
                        data: data.map(d => d.elevation),
                        backgroundColor: '#06b6d4',
                        borderRadius: 8,
                        yAxisID: 'y1',
                        barThickness: 10,
                    });
                }

                progressChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: data.map(d => d.label), // Lundi, Mardi, Mercredi...
                        datasets: datasets
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            x: { grid: { display: false }, ticks: { font: { weight: 'bold' } } },
                            y: { beginAtZero: true, position: 'left' },
                            y1: { display: chartType !== 'distance', position: 'right', grid: { drawOnChartArea: false } }
                        }
                    }
                });
            }

            function loadChartData(offset = 0) {
                // On envoie l'offset à ton contrôleur
                fetch(`{{ route('dashboard.chart-data') }}?week_offset=${offset}`)
                    .then(res => res.json())
                    .then(data => {
                        rawData = data;
                        initChart(data);
                        updateWeekLabel(offset);
                    });
            }

            function changeWeek(direction) {
                currentOffset += direction;
                loadChartData(currentOffset);
            }

            function updateWeekLabel(offset) {
                const label = document.getElementById('currentWeekRange');
                if (offset === 0) label.innerText = "Cette semaine";
                else if (offset === -1) label.innerText = "Semaine dernière";
                else label.innerText = `Il y a ${Math.abs(offset)} semaines`;
            }

            document.getElementById('chartType').addEventListener('change', () => initChart(rawData));
            document.addEventListener('DOMContentLoaded', () => loadChartData(0));
        </script>
    @endpush
@endsection
