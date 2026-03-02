@extends('layouts.app')
@section('title', 'Modifier le vlog')

@section('content')
    <div class="max-w-4xl mx-auto px-6 lg:px-8 py-12">

        <!-- Retour -->
        <a href="{{ route('vlogs.show', $vlog) }}" class="inline-flex items-center gap-2 text-gray-900 hover:text-violet-600 font-semibold mb-8 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Retour au vlog
        </a>

        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-black text-gray-900 mb-2">Modifier mon vlog</h1>
            <p class="text-gray-600 text-lg">Mettez à jour votre vlog d'entraînement</p>
        </div>

        <!-- Formulaire -->
        <div class="bg-white rounded-2xl shadow-lg p-8">

            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-600 text-red-800 px-4 py-3 rounded mb-6">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('vlogs.update', $vlog) }}" enctype="multipart/form-data" class="space-y-8">
                @csrf
                @method('PUT')

                <!-- Séance associée -->
                <div>
                    <label class="block text-sm font-bold text-gray-900 mb-2">Séance associée</label>
                    <select name="training_session_id" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-violet-600 focus:ring-2 focus:ring-violet-100 outline-none">
                        <option value="">Aucune séance</option>
                        @foreach($sessions as $session)
                            <option value="{{ $session->id }}" {{ $vlog->training_session_id == $session->id ? 'selected' : '' }}>
                                {{ $session->title }} - {{ $session->scheduled_date->format('d/m/Y') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Titre -->
                <div>
                    <label class="block text-sm font-bold text-gray-900 mb-2">Titre *</label>
                    <input type="text"
                           name="title"
                           value="{{ old('title', $vlog->title) }}"
                           required
                           maxlength="255"
                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-violet-600 focus:ring-2 focus:ring-violet-100 outline-none"
                           placeholder="Ex: Super sortie longue en montagne">
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-bold text-gray-900 mb-2">Description</label>
                    <textarea name="content"
                              rows="6"
                              class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-violet-600 focus:ring-2 focus:ring-violet-100 outline-none resize-none"
                              placeholder="Racontez votre sortie, vos sensations, les conditions...">{{ old('content', $vlog->content) }}</textarea>
                </div>

                <!-- Photos existantes -->
                @if($vlog->photo_paths && count($vlog->photo_paths) > 0)
                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-3">Photos actuelles</label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                            @foreach($vlog->photo_paths as $index => $photo)
                                <div class="relative group">
                                    <img src="{{ Storage::url($photo) }}"
                                         alt="Photo {{ $index + 1 }}"
                                         class="w-full h-32 object-cover rounded-lg">
                                    <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg flex items-center justify-center">
                                        <label class="cursor-pointer">
                                            <input type="checkbox"
                                                   name="delete_photos[]"
                                                   value="{{ $index }}"
                                                   class="w-5 h-5">
                                            <span class="ml-2 text-white text-sm font-bold">Supprimer</span>
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <p class="text-sm text-gray-500">Cochez les photos à supprimer</p>
                    </div>
                @endif

                <!-- Ajouter de nouvelles photos -->
                <div>
                    <label class="block text-sm font-bold text-gray-900 mb-2">Ajouter des photos</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-violet-600 transition-colors">
                        <input type="file"
                               name="photos[]"
                               multiple
                               accept="image/*"
                               class="hidden"
                               id="photos">
                        <label for="photos" class="cursor-pointer">
                            <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p class="text-gray-600 font-semibold">Cliquez pour ajouter des photos</p>
                            <p class="text-sm text-gray-500 mt-1">JPG, PNG - Max 10MB par photo</p>
                        </label>
                    </div>
                </div>

                <!-- Vidéo YouTube/Vimeo -->
                <div>
                    <label class="block text-sm font-bold text-gray-900 mb-2">URL Vidéo (YouTube ou Vimeo)</label>
                    <input type="url"
                           name="video_url"
                           value="{{ old('video_url', $vlog->video_url) }}"
                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-violet-600 focus:ring-2 focus:ring-violet-100 outline-none"
                           placeholder="https://www.youtube.com/watch?v=...">
                    <p class="text-sm text-gray-500 mt-1">Collez l'URL complète de votre vidéo</p>
                </div>

                <!-- Lieu -->
                <div>
                    <label class="block text-sm font-bold text-gray-900 mb-2">Lieu</label>
                    <input type="text"
                           name="location"
                           value="{{ old('location', $vlog->location) }}"
                           maxlength="255"
                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-violet-600 focus:ring-2 focus:ring-violet-100 outline-none"
                           placeholder="Ex: Vercors, Grenoble">
                </div>

                <!-- Mood -->
                <div>
                    <label class="block text-sm font-bold text-gray-900 mb-2">Humeur</label>
                    <select name="mood" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-violet-600 focus:ring-2 focus:ring-violet-100 outline-none">
                        <option value="">Non définie</option>
                        <option value="excellent" {{ old('mood', $vlog->mood) === 'excellent' ? 'selected' : '' }}>😁 Excellent</option>
                        <option value="bien" {{ old('mood', $vlog->mood) === 'bien' ? 'selected' : '' }}>😊 Bien</option>
                        <option value="moyen" {{ old('mood', $vlog->mood) === 'moyen' ? 'selected' : '' }}>😐 Moyen</option>
                        <option value="difficile" {{ old('mood', $vlog->mood) === 'difficile' ? 'selected' : '' }}>😓 Difficile</option>
                        <option value="terrible" {{ old('mood', $vlog->mood) === 'terrible' ? 'selected' : '' }}>😩 Terrible</option>
                    </select>
                </div>

                <!-- Tags -->
                <div>
                    <label class="block text-sm font-bold text-gray-900 mb-2">Tags</label>
                    <input type="text"
                           name="tags"
                           value="{{ old('tags', is_array($vlog->tags) ? implode(', ', $vlog->tags) : '') }}"
                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-violet-600 focus:ring-2 focus:ring-violet-100 outline-none"
                           placeholder="trail, montagne, ultra...">
                    <p class="text-sm text-gray-500 mt-1">Séparez les tags par des virgules</p>
                </div>

                <!-- Visibilité -->
                <div>
                    <label class="block text-sm font-bold text-gray-900 mb-2">Visibilité</label>
                    <select name="visibility" required class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-violet-600 focus:ring-2 focus:ring-violet-100 outline-none">
                        <option value="public" {{ old('visibility', $vlog->visibility) === 'public' ? 'selected' : '' }}>🌍 Public - Visible par tous</option>
                        <option value="followers" {{ old('visibility', $vlog->visibility) === 'followers' ? 'selected' : '' }}>👥 Followers - Visible par mes abonnés</option>
                        <option value="private" {{ old('visibility', $vlog->visibility) === 'private' ? 'selected' : '' }}>🔒 Privé - Visible uniquement par moi</option>
                    </select>
                </div>

                <!-- Boutons -->
                <div class="flex gap-4 pt-6">
                    <button type="submit"
                            class="flex-1 bg-violet-600 hover:bg-violet-700 text-white font-bold py-4 rounded-xl transition-all transform hover:scale-105 shadow-lg uppercase tracking-wider">
                        💾 Enregistrer les modifications
                    </button>
                    <a href="{{ route('vlogs.show', $vlog) }}"
                       class="px-8 py-4 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-xl transition-all">
                        Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Preview des nouvelles photos
        document.getElementById('photos').addEventListener('change', function(e) {
            const files = Array.from(e.target.files);
            if (files.length > 0) {
                alert(`${files.length} photo(s) sélectionnée(s)`);
            }
        });
    </script>
@endsection
