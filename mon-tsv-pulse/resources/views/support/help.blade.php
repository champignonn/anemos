@extends('layouts.support')
@section('title', 'Centre d\'aide')

@section('content')
    <div class="max-w-4xl mx-auto px-6">
        <div class="text-center mb-16">
            <h1 class="font-display text-5xl md:text-6xl text-white mb-6">CENTRE D'AIDE</h1>
            <p class="text-violet-300 text-xl font-medium uppercase tracking-widest">Comment pouvons-nous vous guider ?</p>
        </div>

        <div class="glass-card rounded-3xl p-8 md:p-12 text-center">
            <p class="text-gray-600 text-lg mb-8">
                Vous trouverez la plupart des réponses dans notre documentation ou notre FAQ.
                Si votre problème persiste, n'hésitez pas à contacter notre équipe.
            </p>
            <div class="flex flex-col md:flex-row gap-4 justify-center">
                <a href="{{ route('support.faq') }}" class="bg-violet-600 text-white px-8 py-4 rounded-xl font-bold">Consulter la FAQ</a>
                <a href="{{ route('support.contact') }}" class="bg-gray-900 text-white px-8 py-4 rounded-xl font-bold">Contacter le support</a>
            </div>
        </div>
    </div>
@endsection
