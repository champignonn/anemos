@extends('layouts.app')
@section('content')
    <div class="max-w-7xl mx-auto px-4 py-8">
        <h1 class="text-4xl font-bold mb-8 text-moss">Mes prochaines séances</h1>

        @foreach($sessions as $session)
            <div class="bg-white rounded-xl p-6 mb-4 shadow-lg" style="border-left: 4px solid {{ $session->intensity_color }}">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-xl font-bold text-moss">{{ $session->title }}</h3>
                        <p class="text-gray-600 mt-1">{{ $session->description }}</p>
                        <div class="flex gap-4 mt-3 text-sm">
                            <span class="font-semibold">📅 {{ $session->scheduled_date->format('d/m/Y') }}</span>
                            <span>🏃 {{ $session->planned_distance }}km</span>
                            <span>⛰️ D+{{ $session->planned_elevation }}m</span>
                            <span>⏱️ {{ $session->duration_minutes }}min</span>
                            <span class="px-3 py-1 rounded-full text-white" style="background-color: {{ $session->intensity_color }}">{{ $session->type }}</span>
                        </div>
                        @if($session->workout_structure)
                            <div class="mt-3 text-sm text-gray-700">
                                <strong>Structure :</strong>
                                {{ $session->workout_structure['warmup'] ?? '' }} →
                                {{ $session->workout_structure['main'] ?? '' }} →
                                {{ $session->workout_structure['cooldown'] ?? '' }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
