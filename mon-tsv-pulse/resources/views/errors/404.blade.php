@extends('layouts.support')
@section('title', '404 - Sentier Perdu')

@section('content')
    <div class="max-w-4xl mx-auto px-6 text-center">
        <div class="relative inline-block mb-8">
            <h1 class="font-display text-[10rem] md:text-[15rem] leading-none text-white opacity-10 font-black">404</h1>
            <div class="absolute inset-0 flex items-center justify-center">
                <span class="text-4xl md:text-6xl font-black text-violet-500 uppercase tracking-tighter">Hors-Piste</span>
            </div>
        </div>

        <h2 class="text-3xl font-bold text-white mb-6 uppercase">Oups ! Vous avez quitté le balisage.</h2>
        <p class="text-gray-400 text-lg mb-12 max-w-lg mx-auto">
            La page que vous cherchez a probablement pris un raccourci ou n'a jamais existé sur ce sommet.
        </p>

        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ url('/') }}" class="bg-violet-600 hover:bg-violet-700 text-white font-black px-8 py-4 rounded-2xl transition-all transform hover:-translate-y-1 shadow-xl shadow-violet-500/20">
                RETOUR AU CAMP DE BASE
            </a>
            <a href="{{ route('support.contact') }}" class="bg-white/10 hover:bg-white/20 text-white font-black px-8 py-4 rounded-2xl transition-all backdrop-blur-md">
                SIGNALER UNE ERREUR
            </a>
        </div>
    </div>
@endsection
