@extends('layouts.app')
@section('title', 'Nouvelle Course')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8">
            <a href="{{ route('races.index') }}" class="text-moss hover:text-auburn transition-colors inline-flex items-center gap-2 mb-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Retour aux courses
            </a>
            <h1 class="text-4xl font-serif font-bold text-moss mb-2">Créer une course</h1>
            <p class="text-gray-600">Définissez votre prochain objectif trail</p>
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

            <form method="POST" action="{{ route('races.store') }}" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-moss mb-2">Nom de la course *</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               placeholder="Ex: Trail de la Tour Sans Venin"
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-olivine focus:ring-2 focus:ring-olivine/20 outline-none transition-all">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-moss mb-2">Date de la course *</label>
                        <input type="date" name="race_date" value="{{ old('race_date') }}" required
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-olivine outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-moss mb-2">Lieu</label>
                        <input type="text" name="location" value="{{ old('location') }}"
                               placeholder="Ex: Saint-Martin-d'Hères, Isère"
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-olivine outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-moss mb-2">Distance (km) *</label>
                        <input type="number" step="0.1" name="distance" value="{{ old('distance', 42) }}" required
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-olivine outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-moss mb-2">Dénivelé positif (m) *</label>
                        <input type="number" name="elevation_gain" value="{{ old('elevation_gain', 2400) }}" required
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-olivine outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-moss mb-2">Type de parcours *</label>
                        <select name="type" required
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-olivine outline-none">
                            <option value="classique" {{ old('type') === 'classique' ? 'selected' : '' }}>Classique (A → B)</option>
                            <option value="boucle" {{ old('type') === 'boucle' ? 'selected' : '' }}>Boucle</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-moss mb-2">Priorité *</label>
                        <select name="priority" required
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-olivine outline-none">
                            <option value="A" {{ old('priority') === 'A' ? 'selected' : '' }}>A - Objectif principal</option>
                            <option value="B" {{ old('priority', 'B') === 'B' ? 'selected' : '' }}>B - Objectif secondaire</option>
                            <option value="C" {{ old('priority') === 'C' ? 'selected' : '' }}>C - Entraînement</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-moss mb-2">Temps objectif (optionnel)</label>
                        <input type="time" name="target_time" value="{{ old('target_time') }}"
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-olivine outline-none">
                        <p class="text-xs text-gray-500 mt-1">Format : HH:MM</p>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-moss mb-2">Description</label>
                        <textarea name="description" rows="4"
                                  placeholder="Notes personnelles, spécificités du parcours..."
                                  class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-olivine outline-none resize-none">{{ old('description') }}</textarea>
                    </div>
                </div>

                <div class="flex gap-4 pt-6 border-t">
                    <button type="submit"
                            class="flex-1 bg-violet-700 hover:bg-violet-800 text-white font-bold py-4 rounded-xl transition-all duration-300 transform hover:scale-[1.02] shadow-lg">
                        Créer la course
                    </button>
                    <a href="{{ route('races.index') }}"
                       class="px-8 py-4 border-2 border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-all">
                        Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
