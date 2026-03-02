@extends('layouts.app')
@section('title', $race->name)

@section('content')
    <div class="max-w-7xl mx-auto px-6 lg:px-8 py-12">

        <!-- Retour -->
        <a href="{{ route('races.index') }}" class="inline-flex items-center gap-2 text-gray-900 hover:text-violet-600 font-semibold mb-8 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Retour aux courses
        </a>

        <!-- Hero Section -->
        <div class="relative bg-gradient-to-br from-gray-900 to-gray-800 rounded-3xl overflow-hidden mb-8 shadow-2xl">
            <div class="absolute inset-0 opacity-20">
                <img src="https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1920&q=80"
                     class="w-full h-full object-cover">
            </div>

            <div class="relative px-8 md:px-12 py-12">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-8">
                    <div class="flex-1">
                        <!-- Badges -->
                        <div class="flex flex-wrap gap-3 mb-6">
                            @php
                                $priorityConfig = [
                                    'A' => ['bg' => 'bg-violet-600', 'text' => 'Priorité A'],
                                    'B' => ['bg' => 'bg-cyan-600', 'text' => 'Priorité B'],
                                    'C' => ['bg' => 'bg-gray-600', 'text' => 'Priorité C'],
                                ];
                                $typeConfig = [
                                    'trail' => ['icon' => '🏔️', 'label' => 'Trail'],
                                    'ultra' => ['icon' => '🏃‍♂️', 'label' => 'Ultra'],
                                    'vertical' => ['icon' => '⬆️', 'label' => 'Vertical'],
                                    'backyard' => ['icon' => '🔄', 'label' => 'Backyard'],
                                    'boucle' => ['icon' => '🔁', 'label' => 'Boucle'],
                                    'route' => ['icon' => '🛣️', 'label' => 'Route'],
                                ];
                            @endphp
                            <span class="px-4 py-2 {{ $priorityConfig[$race->priority]['bg'] }} text-white rounded-full text-sm font-black uppercase shadow-lg">
                            {{ $priorityConfig[$race->priority]['text'] }}
                        </span>
                            <span class="px-4 py-2 bg-white/20 backdrop-blur-sm text-white rounded-full text-sm font-black uppercase border border-white/30">
                            {{ $typeConfig[$race->type]['icon'] ?? '🏃' }} {{ $typeConfig[$race->type]['label'] ?? ucfirst($race->type) }}
                        </span>
                        </div>

                        <!-- Titre -->
                        <h1 class="font-display text-5xl md:text-6xl font-black text-white mb-6 leading-tight">
                            {{ $race->name }}
                        </h1>

                        <!-- Infos principales -->
                        <div class="flex flex-wrap gap-6 text-white/90 text-lg">
                            <div class="flex items-center gap-2">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span class="font-semibold">{{ $race->race_date->format('d F Y') }}</span>
                            </div>
                            @if($race->location)
                                <div class="flex items-center gap-2">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    </svg>
                                    <span class="font-semibold">{{ $race->location }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Compte à rebours -->
                    <div id="countdown"
                         data-date="{{ $race->race_date->toIso8601String() }}"
                         class="bg-white/10 backdrop-blur-lg rounded-3xl px-10 py-8 text-center border-2 border-white/20 shadow-2xl">
                        <div id="days" class="text-7xl font-black text-white mb-3">--</div>
                        <p class="text-xl font-bold text-white/90 uppercase tracking-wider">Jours restants</p>
                    </div>

                    <script>
                        document.addEventListener('DOMContentLoaded', () => {
                            const countdownElement = document.getElementById('countdown');
                            const targetDate = new Date(countdownElement.getAttribute('data-date')).getTime();
                            const daysDisplay = document.getElementById('days');

                            function updateCountdown() {
                                const now = new Date().getTime();
                                const distance = targetDate - now;

                                if (distance < 0) {
                                    daysDisplay.innerText = "0";
                                    return;
                                }

                                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                                daysDisplay.innerText = days;
                            }

                            updateCountdown();
                            setInterval(updateCountdown, 60000); // Mise à jour toutes les minutes
                        });
                    </script>
                </div>
            </div>
        </div>

        <!-- Stats principales -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-12">
            <div class="bg-white rounded-2xl p-6 shadow-lg border-2 border-violet-100 hover:border-violet-600 transition-all">
                <div class="flex items-center gap-4 mb-3">
                    <div class="w-12 h-12 bg-violet-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Distance</p>
                        <p class="text-3xl font-black text-gray-900">{{ $race->distance }}<span class="text-xl">km</span></p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 shadow-lg border-2 border-cyan-100 hover:border-cyan-600 transition-all">
                <div class="flex items-center gap-4 mb-3">
                    <div class="w-12 h-12 bg-cyan-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Dénivelé</p>
                        <p class="text-3xl font-black text-gray-900">{{ $race->elevation_gain }}<span class="text-xl">m</span></p>
                    </div>
                </div>
            </div>

            @if($race->target_time)
                <div class="bg-white rounded-2xl p-6 shadow-lg border-2 border-green-100 hover:border-green-600 transition-all">
                    <div class="flex items-center gap-4 mb-3">
                        <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Objectif</p>
                            <p class="text-3xl font-black text-gray-900">{{ $race->target_time->format('H:i') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="bg-white rounded-2xl p-6 shadow-lg border-2 border-gray-100 hover:border-gray-600 transition-all">
                <div class="flex items-center gap-4 mb-3">
                    <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Difficulté</p>
                        <p class="text-3xl font-black text-gray-900">{{ round($race->elevation_gain / $race->distance) }}<span class="text-xl">m/km</span></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Colonne principale -->
            <div class="lg:col-span-2 space-y-8">

                <!-- Description -->
                @if($race->description)
                    <div class="bg-white rounded-2xl p-8 shadow-lg">
                        <h2 class="text-2xl font-black text-gray-900 mb-4 flex items-center gap-3">
                            <svg class="w-6 h-6 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                            </svg>
                            Description
                        </h2>
                        <p class="text-gray-700 leading-relaxed text-lg">{{ $race->description }}</p>
                    </div>
                @endif

                <!-- Plan d'entraînement -->
                @if($race->trainingPlans->isNotEmpty())
                    <div class="bg-white rounded-2xl p-8 shadow-lg">
                        <h2 class="text-2xl font-black text-gray-900 mb-6 flex items-center gap-3">
                            <svg class="w-6 h-6 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            Plan d'entraînement actif
                        </h2>
                        @foreach($race->trainingPlans as $plan)
                            <a href="{{ route('training-plans.show', $plan) }}"
                               class="block p-6 bg-gradient-to-r from-violet-50 to-cyan-50 rounded-xl border-2 border-violet-200 hover:border-violet-600 transition-all group">
                                <div class="flex justify-between items-center">
                                    <div class="flex-1">
                                        <h3 class="text-xl font-black text-gray-900 group-hover:text-violet-600 mb-2">
                                            {{ $plan->name }}
                                        </h3>
                                        <p class="text-gray-600 font-semibold">
                                            {{ $plan->total_weeks }} semaines • {{ $plan->trainingSessions->count() }} séances
                                        </p>
                                    </div>
                                    <svg class="w-8 h-8 text-violet-600 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                    </svg>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="bg-gradient-to-br from-violet-50 to-cyan-50 rounded-2xl p-8 shadow-lg border-2 border-violet-200">
                        <div class="text-center">
                            <div class="w-20 h-20 bg-violet-100 rounded-full flex items-center justify-center mx-auto mb-6">
                                <svg class="w-10 h-10 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-black text-gray-900 mb-3">Prêt à commencer l'entraînement ?</h3>
                            <p class="text-gray-600 mb-6 text-lg">
                                Générez un plan d'entraînement personnalisé pour atteindre vos objectifs.
                            </p>
                            <form method="POST" action="{{ route('training-plans.generate', $race) }}" class="max-w-md mx-auto">
                                @csrf
                                <div class="mb-4">
                                    <label class="block text-sm font-bold text-gray-900 mb-2">Semaines avant la course</label>
                                    <input type="number" name="weeks" value="12" min="4" max="24" required
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-violet-600 focus:ring-2 focus:ring-violet-100 outline-none text-center text-2xl font-bold">
                                </div>
                                <button type="submit"
                                        class="w-full bg-violet-600 hover:bg-violet-700 text-white font-bold py-4 rounded-xl transition-all transform hover:scale-105 shadow-lg uppercase tracking-wider">
                                    Générer mon plan
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">

                <!-- Actions -->
                <div class="bg-white rounded-2xl p-6 shadow-lg">
                    <h3 class="text-lg font-black text-gray-900 mb-4">Actions</h3>
                    <div class="space-y-3">
                        <a href="{{ route('races.edit', $race) }}"
                           class="block w-full bg-violet-600 hover:bg-violet-700 text-white font-bold py-3 rounded-xl transition-all text-center">
                            ✏️ Modifier
                        </a>
                        <form method="POST" action="{{ route('races.destroy', $race) }}"
                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette course ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded-xl transition-all">
                                🗑️ Supprimer
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Météo estimée -->
                <div class="bg-gradient-to-br from-cyan-500 to-blue-600 rounded-2xl p-6 shadow-lg text-white">
                    <h3 class="text-lg font-black mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"/>
                        </svg>
                        Météo prévisionnelle
                    </h3>
                    <p class="text-white/90 text-sm mb-2">
                        Pensez à vérifier la météo quelques jours avant la course !
                    </p>
                    <a href="https://www.meteofrance.com" target="_blank"
                       class="inline-flex items-center gap-2 bg-white/20 hover:bg-white/30 px-4 py-2 rounded-lg font-semibold text-sm transition-all">
                        Voir la météo
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                    </a>
                </div>

                <!-- Conseils -->
                <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl p-6 shadow-lg text-white">
                    <h3 class="text-lg font-black mb-3">💡 Conseils</h3>
                    <ul class="space-y-2 text-sm text-white/90">
                        <li class="flex items-start gap-2">
                            <span class="font-bold">•</span>
                            <span>Préparez votre équipement à l'avance</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="font-bold">•</span>
                            <span>Repérez le parcours si possible</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="font-bold">•</span>
                            <span>Hydratez-vous bien la veille</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="font-bold">•</span>
                            <span>Arrivez 1h avant le départ</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
