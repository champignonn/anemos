@extends('layouts.support')
@section('title', 'Documentation & Guide')

@section('content')
    <div class="max-w-5xl mx-auto px-6">
        <div class="text-center mb-16">
            <h1 class="font-display text-5xl md:text-7xl text-white mb-4">GUIDE D'EXPÉDITION</h1>
            <p class="text-violet-400 font-bold tracking-[0.3em] uppercase">Maîtrisez votre préparation</p>
        </div>

        <div class="grid gap-12">
            <div class="glass-card rounded-3xl p-8 md:p-12 border border-white/20 flex flex-col md:flex-row gap-8 items-center">
                <div class="flex-1">
                    <div class="text-violet-600 font-black text-5xl mb-4">01</div>
                    <h2 class="text-3xl font-black text-gray-900 mb-4 uppercase">Configurez votre objectif</h2>
                    <p class="text-gray-600 text-lg leading-relaxed">
                        Tout commence par une cible. Dans votre profil, renseignez votre prochaine course, la distance et le dénivelé positif. Notre algorithme segmente votre préparation en 4  phases : Base, Développement, Spécifique et Affûtage.
                    </p>
                </div>
                <div class="w-full md:w-1/3 bg-gray-100 rounded-2xl h-48 flex items-center justify-center">
                    <svg class="w-20 h-20 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 20l-5.447-2.724A2 2 0 013 15.488V5.512a2 2 0 011.553-1.954L9 2l6 3 5.447-2.724A2 2 0 0121 4.224v9.976a2 2 0 01-1.553 1.954L15 19l-6 1z"/></svg>
                </div>
            </div>

            <div class="glass-card rounded-3xl p-8 md:p-12 border border-white/20 flex flex-col md:flex-row-reverse gap-8 items-center">
                <div class="flex-1 text-right">
                    <div class="text-cyan-500 font-black text-5xl mb-4">02</div>
                    <h2 class="text-3xl font-black text-gray-900 mb-4 uppercase">Synchronisez vos outils</h2>
                    <p class="text-gray-600 text-lg leading-relaxed">
                        Connectez votre compte Strava pour importer vos activités. Anemos analyse la charge d'entraînement via la formule du TRIMP (Training Impulse) pour s'assurer que vous ne dépassez pas vos limites et éviter la blessure.
                    </p>
                </div>
                <div class="w-full md:w-1/3 bg-gray-100 rounded-2xl h-48 flex items-center justify-center">
                    <svg class="w-20 h-20 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
            </div>

            <div class="glass-card rounded-3xl p-8 md:p-12 border border-white/20 flex flex-col md:flex-row gap-8 items-center">
                <div class="flex-1">
                    <div class="text-violet-600 font-black text-5xl mb-4">03</div>
                    <h2 class="text-3xl font-black text-gray-900 mb-4 uppercase">Suivez le plan intelligent</h2>
                    <p class="text-gray-600 text-lg leading-relaxed">
                        Chaque lundi, votre dashboard se met à jour. Si vous avez manqué une séance ou si vous avez été plus performant que prévu, le plan s'adapte. Les séances sont classées par intensité (EIE, Seuil, Sortie Longue).
                    </p>
                </div>
                <div class="w-full md:w-1/3 bg-gray-100 rounded-2xl h-48 flex items-center justify-center text-center px-4">
                    <div class="space-y-2">
                        <div class="h-2 w-24 bg-violet-200 rounded"></div>
                        <div class="h-2 w-32 bg-violet-400 rounded"></div>
                        <div class="h-2 w-20 bg-violet-600 rounded"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-20 text-center">
            <a href="{{ route('register') }}" class="inline-block bg-white text-gray-900 font-black px-10 py-5 rounded-2xl hover:bg-violet-600 hover:text-white transition-all transform hover:scale-105 shadow-2xl">
                PRÊT À GRIMPER ? CRÉEZ VOTRE PLAN
            </a>
        </div>
    </div>
@endsection
