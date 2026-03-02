@extends('layouts.app')
@section('title', 'Modifier la séance')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8">
            <a href="{{ route('training-sessions.show', $trainingSession) }}" class="text-moss hover:text-auburn inline-flex items-center gap-2 mb-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Retour à la séance
            </a>
            <h1 class="text-4xl font-serif font-bold text-moss mb-2">Modifier la séance</h1>
        </div>

        <div class="bg-white rounded-2xl shadow-2xl p-8">
            @if ($errors->any())
                <div class="bg-auburn/10 border-l-4 border-auburn text-auburn px-4 py-3 rounded mb-6">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('training-sessions.update', $trainingSession) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-bold text-moss mb-2">Titre *</label>
                    <input type="text" name="title" value="{{ old('title', $trainingSession->title) }}" required
                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-olivine outline-none">
                </div>

                <div>
                    <label class="block text-sm font-bold text-moss mb-2">Description</label>
                    <textarea name="description" rows="3"
                              class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-olivine outline-none">{{ old('description', $trainingSession->description) }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-moss mb-2">Date *</label>
                        <input type="date" name="scheduled_date" value="{{ old('scheduled_date', $trainingSession->scheduled_date->format('Y-m-d')) }}" required
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-olivine outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-moss mb-2">Type *</label>
                        <select name="type" required
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-olivine outline-none">
                            @foreach(['VMA', 'Seuil', 'Tempo', 'Sortie Longue', 'Endurance Fondamentale', 'Fartlek', 'Côtes', 'Trail Technique', 'Récupération', 'Repos'] as $type)
                                <option value="{{ $type }}" {{ old('type', $trainingSession->type) === $type ? 'selected' : '' }}>
                                    {{ $type }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-moss mb-2">Distance planifiée (km)</label>
                        <input type="number" step="0.1" name="planned_distance" value="{{ old('planned_distance', $trainingSession->planned_distance) }}"
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-olivine outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-moss mb-2">D+ planifié (m)</label>
                        <input type="number" name="planned_elevation" value="{{ old('planned_elevation', $trainingSession->planned_elevation) }}"
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-olivine outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-moss mb-2">Durée estimée (min)</label>
                        <input type="number" name="duration_minutes" value="{{ old('duration_minutes', $trainingSession->duration_minutes) }}"
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-olivine outline-none">
                    </div>
                </div>

                <div class="flex gap-4 pt-6 border-t">
                    <button type="submit"
                            class="flex-1 bg-violet-700 hover:bg-violet-800 text-white font-bold py-4 rounded-xl transition-all">
                        Enregistrer les modifications
                    </button>
                    <a href="{{ route('training-sessions.show', $trainingSession) }}"
                       class="px-8 py-4 border-2 border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-all">
                        Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
