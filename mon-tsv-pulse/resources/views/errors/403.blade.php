@extends('layouts.support')
@section('title', '403 - Zone Privée')

@section('content')
    <div class="max-w-4xl mx-auto px-6 text-center">
        <div class="mb-8 inline-flex p-6 bg-red-500/20 rounded-full border border-red-500/50">
            <svg class="w-16 h-16 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m0 0v2m0-2h2m-2 0H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>

        <h1 class="font-display text-5xl md:text-7xl text-white mb-6 uppercase">ACCÈS<br><span class="text-red-500">RESTREINT</span></h1>

        <p class="text-gray-400 text-lg mb-12 max-w-lg mx-auto leading-relaxed">
            Stop ! Ce sentier est réservé aux organisateurs ou nécessite des droits spécifiques. Vous n'avez pas l'autorisation d'accéder à cette zone.
        </p>

        <a href="{{ url('/dashboard') }}" class="inline-block bg-white text-gray-900 font-black px-10 py-5 rounded-2xl hover:bg-red-500 hover:text-white transition-all transform hover:scale-105 shadow-2xl">
            RETOUR AU DASHBOARD
        </a>
    </div>
@endsection
