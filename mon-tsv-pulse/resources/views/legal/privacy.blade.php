@extends('layouts.legal')
@section('title', 'Politique de Confidentialité')

@section('content')
    <h1 class="text-4xl font-black mb-8">Politique de Confidentialité</h1>

    <h2 class="text-xl font-bold mt-8 mb-4 uppercase tracking-wider text-violet-600">1. Collecte des données</h2>
    <p>Nous collectons les informations suivantes lors de votre inscription :</p>
    <ul class="list-disc ml-6 space-y-2">
        <li>Nom, prénom et adresse email.</li>
        <li>Données d'entraînement provenant de <strong>Strava</strong> (si vous choisissez de connecter votre compte).</li>
    </ul>

    <h2 class="text-xl font-bold mt-8 mb-4 uppercase tracking-wider text-violet-600">2. Utilisation des données Strava</h2>
    <p>En connectant votre compte Strava, Anemos accède à vos activités (distance, dénivelé, durée). Ces données sont utilisées exclusivement pour :</p>
    <ul class="list-disc ml-6 space-y-2">
        <li>Générer des graphiques de progression personnalisés.</li>
        <li>Adapter vos plans d'entraînement à votre niveau réel.</li>
        <li>Afficher vos statistiques hebdomadaires.</li>
    </ul>

    <h2 class="text-xl font-bold mt-8 mb-4 uppercase tracking-wider text-violet-600">3. Conservation et Sécurité</h2>
    <p>Vos données sont conservées tant que votre compte est actif. Vous pouvez déconnecter Strava ou supprimer votre compte à tout moment depuis votre profil.</p>
@endsection
