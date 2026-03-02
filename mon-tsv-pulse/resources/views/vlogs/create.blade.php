@extends('layouts.app')
@section('title', 'Nouveau Vlog')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8">
            <a href="{{ route('vlogs.index') }}" class="text-moss hover:text-auburn inline-flex items-center gap-2 mb-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Retour aux vlogs
            </a>
            <h1 class="text-4xl font-serif font-bold text-moss mb-2">Créer un vlog</h1>
            <p class="text-gray-600">Documentez votre sortie avec photos et vidéos</p>
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

            <form method="POST" action="{{ route('vlogs.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-sm font-bold text-moss mb-2">Séance associée *</label>
                    <select name="training_session_id" required
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-olivine outline-none">
                        <option value="">Choisir une séance...</option>
                        @foreach($sessions as $session)
                            <option value="{{ $session->id }}">
                                {{ $session->scheduled_date->format('d/m/Y') }} - {{ $session->title }}
                                ({{ $session->actual_distance }}km, D+{{ $session->actual_elevation }}m)
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-moss mb-2">Titre *</label>
                    <input type="text" name="title" value="{{ old('title') }}" required
                           placeholder="Ex: Sortie longue dans les Alpes"
                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-olivine outline-none">
                </div>

                <div>
                    <label class="block text-sm font-bold text-moss mb-2">Contenu</label>
                    <textarea name="content" rows="6"
                              placeholder="Racontez votre expérience..."
                              class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-olivine outline-none">{{ old('content') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-bold text-moss mb-2">Photos (max 10MB chacune)</label>
                    <input type="file" name="photos[]" multiple accept="image/*"
                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-olivine outline-none">
                    <p class="text-xs text-gray-500 mt-1">Vous pouvez sélectionner plusieurs photos</p>
                </div>

                <div>
                    <label class="block text-sm font-bold text-moss mb-2">Vidéo YouTube/Vimeo (URL)</label>
                    <input type="url" name="video_url" value="{{ old('video_url') }}"
                           placeholder="https://youtube.com/watch?v=..."
                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-olivine outline-none">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-moss mb-2">Humeur</label>
                        <select name="mood"
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-olivine outline-none">
                            <option value="">Pas d'humeur</option>
                            <option value="excellent">Excellent</option>
                            <option value="bien">Bien</option>
                            <option value="moyen">Moyen</option>
                            <option value="difficile">Difficile</option>
                            <option value="terrible">Terrible</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-moss mb-2">Visibilité *</label>
                        <select name="visibility" required
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-olivine outline-none">
                            <option value="public">🌍 Public - Tout le monde</option>
                            <option value="followers">👥 Followers - Mes abonnés</option>
                            <option value="private">🔒 Privé - Seulement moi</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-moss mb-2">Tags (séparés par des virgules)</label>
                    <input type="text" name="tags" value="{{ old('tags') }}"
                           placeholder="trail, montagne, ultra"
                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-olivine outline-none">
                </div>

                <div class="flex gap-4 pt-6 border-t">
                    <button type="submit"
                            class="flex-1 bg-violet-400 hover:bg-violet-700 text-white font-bold py-4 rounded-xl transition-all">
                        Publier le vlog
                    </button>
                    <a href="{{ route('vlogs.index') }}"
                       class="px-8 py-4 border-2 border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50">
                        Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
