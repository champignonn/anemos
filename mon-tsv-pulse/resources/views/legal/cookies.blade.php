@extends('layouts.legal')
@section('title', 'Gestion des Cookies')

@section('content')
    <h1 class="text-4xl font-black mb-8">Utilisation des Cookies</h1>

    <h2 class="text-xl font-bold mt-8 mb-4 uppercase tracking-wider text-violet-600">Qu'est-ce qu'un cookie ?</h2>
    <p>Un cookie est un petit fichier texte déposé sur votre ordinateur lors de votre visite.</p>

    <h2 class="text-xl font-bold mt-8 mb-4 uppercase tracking-wider text-violet-600">Cookies utilisés par Anemos</h2>
    <ul class="list-disc ml-6 space-y-4">
        <li><strong>Session & Authentification :</strong> Permettent de vous reconnaître et de maintenir votre connexion ouverte.</li>
        <li><strong>Sécurité (CSRF) :</strong> Protègent vos formulaires contre les attaques malveillantes.</li>
        <li><strong>Préférences :</strong> Mémorisent par exemple votre choix d'affichage sur le graphique (Distance ou Dénivelé).</li>
    </ul>

    <p class="mt-8 italic text-slate-500 italic">Anemos n'utilise aucun cookie publicitaire ou de tracking tiers (comme Google Ads).</p>
@endsection
