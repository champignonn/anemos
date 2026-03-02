@extends('layouts.support')
@section('title', 'Questions Fréquentes')

@section('content')
    <div class="max-w-4xl mx-auto px-6">
        <div class="text-center mb-16">
            <h1 class="font-display text-5xl md:text-6xl text-white mb-6">FAQ</h1>
            <p class="text-violet-300 text-xl font-medium uppercase tracking-widest">Réponses à vos sommets</p>
        </div>

        <div class="space-y-4" x-data="{ active: null }">
            <div class="glass-card rounded-2xl overflow-hidden border border-white/20">
                <button @click="active !== 1 ? active = 1 : active = null" class="w-full px-8 py-6 text-left flex justify-between items-center group">
                    <span class="text-lg font-bold text-gray-900 group-hover:text-violet-600 transition">Comment fonctionne la synchronisation Strava ?</span>
                    <svg class="w-6 h-6 text-violet-600 transition-transform" :class="active === 1 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="active === 1" x-collapse class="px-8 pb-6 text-gray-600 leading-relaxed">
                    Une fois connecté, Anemos récupère automatiquement vos activités "Course à pied" et "Trail". Nos algorithmes analysent votre allure, votre dénivelé positif et votre fréquence cardiaque pour ajuster votre plan de la semaine suivante.
                </div>
            </div>

            <div class="glass-card rounded-2xl overflow-hidden border border-white/20">
                <button @click="active !== 2 ? active = 2 : active = null" class="w-full px-8 py-6 text-left flex justify-between items-center group">
                    <span class="text-lg font-bold text-gray-900 group-hover:text-violet-600 transition">Puis-je changer d'objectif en cours de route ?</span>
                    <svg class="w-6 h-6 text-violet-600 transition-transform" :class="active === 2 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="active === 2" x-collapse class="px-8 pb-6 text-gray-600 leading-relaxed">
                    Oui. Si vous décidez de passer d'un 40km à un 80km, modifiez simplement votre course dans l'onglet "Objectifs". Le plan d'entraînement sera recalculé instantanément pour s'adapter à la nouvelle charge.
                </div>
            </div>
        </div>
    </div>
@endsection
