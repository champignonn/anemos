@extends('layouts.app')
@section('title', 'Modifier mon profil')

@section('content')
    <div class="max-w-4xl mx-auto px-6 lg:px-8 py-12">
        <div class="mb-8">
            <a href="{{ route('profile.show', auth()->user()) }}" class="text-gray-900 hover:text-violet-600 inline-flex items-center gap-2 mb-4 font-semibold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Retour au profil
            </a>
            <h1 class="text-4xl font-black text-gray-900 mb-2">Modifier mon profil</h1>
        </div>

        <div class="space-y-8">

            <!-- Informations personnelles -->
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <h3 class="text-2xl font-black text-gray-900 mb-6">Informations personnelles</h3>

                @if ($errors->any())
                    <div class="bg-red-50 border-l-4 border-red-600 text-red-800 px-4 py-3 rounded mb-6">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Avatar -->
                    <div class="flex items-center gap-6">
                        @if($user->avatar_path)
                            <img src="{{ Storage::url($user->avatar_path) }}"
                                 alt="{{ $user->name }}"
                                 class="w-24 h-24 rounded-full object-cover ring-4 ring-violet-100">
                        @else
                            <div class="w-24 h-24 rounded-full bg-violet-600 flex items-center justify-center text-white text-3xl font-black ring-4 ring-violet-100">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                        @endif
                        <div class="flex-1">
                            <label class="block text-sm font-bold text-gray-900 mb-2">Photo de profil</label>
                            <input type="file" name="avatar" accept="image/*"
                                   class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:border-violet-600 outline-none">
                            <p class="text-xs text-gray-500 mt-1">Max 2MB - JPG, PNG</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-2">Nom *</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-violet-600 focus:ring-2 focus:ring-violet-100 outline-none transition-all">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-2">Email *</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-violet-600 focus:ring-2 focus:ring-violet-100 outline-none transition-all">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-2">Biographie</label>
                        <textarea name="bio" rows="4" maxlength="500"
                                  placeholder="Parlez-nous de vous..."
                                  class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-violet-600 focus:ring-2 focus:ring-violet-100 outline-none resize-none">{{ old('bio', $user->bio) }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">Max 500 caractères</p>
                    </div>

                    <button type="submit"
                            class="w-full bg-violet-600 hover:bg-violet-700 text-white font-bold py-4 rounded-xl transition-all transform hover:scale-105 shadow-lg uppercase tracking-wider">
                        Enregistrer les modifications
                    </button>
                </form>
            </div>

            <!-- Métriques sportives -->
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <h3 class="text-2xl font-black text-gray-900 mb-6">Métriques sportives</h3>

                <form method="POST" action="{{ route('profile.update') }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="name" value="{{ $user->name }}">
                    <input type="hidden" name="email" value="{{ $user->email }}">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-2">VMA (km/h)</label>
                            <input type="number" step="0.1" name="vma" value="{{ old('vma', $user->vma) }}"
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-violet-600 focus:ring-2 focus:ring-violet-100 outline-none transition-all">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-2">Allure EF (min/km)</label>
                            <input type="number" step="0.1" name="endurance_pace" value="{{ old('endurance_pace', $user->endurance_pace) }}"
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-violet-600 focus:ring-2 focus:ring-violet-100 outline-none transition-all">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-2">Allure Seuil (min/km)</label>
                            <input type="number" step="0.1" name="threshold_pace" value="{{ old('threshold_pace', $user->threshold_pace) }}"
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-violet-600 focus:ring-2 focus:ring-violet-100 outline-none transition-all">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-2">Poids (kg)</label>
                            <input type="number" name="weight" value="{{ old('weight', $user->weight) }}"
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-violet-600 focus:ring-2 focus:ring-violet-100 outline-none transition-all">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-2">FC Max (bpm)</label>
                            <input type="number" name="max_heart_rate" value="{{ old('max_heart_rate', $user->max_heart_rate) }}"
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-violet-600 focus:ring-2 focus:ring-violet-100 outline-none transition-all">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-2">FC Repos (bpm)</label>
                            <input type="number" name="resting_heart_rate" value="{{ old('resting_heart_rate', $user->resting_heart_rate) }}"
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-violet-600 focus:ring-2 focus:ring-violet-100 outline-none transition-all">
                        </div>
                    </div>

                    <button type="submit"
                            class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-4 rounded-xl transition-all transform hover:scale-105 shadow-lg uppercase tracking-wider">
                        Mettre à jour mes métriques
                    </button>
                </form>
            </div>

            <!-- Changer mot de passe -->
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <h3 class="text-2xl font-black text-gray-900 mb-6">Changer mon mot de passe</h3>

                <form method="POST" action="{{ route('profile.update-password') }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-2">Mot de passe actuel *</label>
                        <input type="password" name="current_password" required
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-violet-600 focus:ring-2 focus:ring-violet-100 outline-none transition-all">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-2">Nouveau mot de passe *</label>
                        <input type="password" name="password" required
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-violet-600 focus:ring-2 focus:ring-violet-100 outline-none transition-all">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-2">Confirmer le nouveau mot de passe *</label>
                        <input type="password" name="password_confirmation" required
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-violet-600 focus:ring-2 focus:ring-violet-100 outline-none transition-all">
                    </div>

                    <button type="submit"
                            class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-4 rounded-xl transition-all transform hover:scale-105 shadow-lg uppercase tracking-wider">
                        Changer mon mot de passe
                    </button>
                </form>
            </div>

        </div>
    </div>
@endsection
