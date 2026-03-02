@extends('layouts.support')
@section('title', 'Contactez le camp de base')

@section('content')
    <div class="max-w-5xl mx-auto px-6">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <div>
                <h1 class="font-display text-6xl text-white mb-6 leading-none">BESOIN D'UN<br><span class="text-violet-500">SHERPA ?</span></h1>
                <p class="text-gray-300 text-lg mb-8 leading-relaxed">
                    Un bug sur votre dashboard ? Une question sur votre plan d'entraînement ? Notre équipe de passionnés est là pour vous aider à atteindre la ligne d'arrivée.
                </p>
                <div class="flex items-center gap-4 text-white">
                    <div class="bg-violet-600 p-3 rounded-xl"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg></div>
                    <span class="font-bold">duvergercha@gmail.fr</span>
                </div>
            </div>

            <div class="glass-card p-8 rounded-3xl shadow-2xl">
                @if(session('success'))
                    <div class="bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-2xl mb-6 flex items-center gap-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span class="font-bold">{{ session('success') }}</span>
                    </div>
                @endif

                <form action="{{ route('support.contact.submit') }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2 tracking-wide uppercase">Sujet de votre message</label>
                        <select name="sujet" class="w-full bg-gray-50 border-2 border-gray-100 rounded-xl px-4 py-3 focus:border-violet-600 outline-none transition appearance-none">
                            <option value="Problème technique">Problème technique</option>
                            <option value="Question sur les plans">Question sur les plans</option>
                            <option value="Partenariat">Partenariat</option>
                            <option value="Autre">Autre</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2 tracking-wide uppercase">Message</label>
                        <textarea name="message" rows="4"
                                  class="w-full bg-gray-50 border-2 border-gray-100 rounded-xl px-4 py-3 focus:border-violet-600 outline-none transition"
                                  placeholder="Décrivez votre besoin en détail..." required></textarea>
                    </div>

                    <button type="submit" class="w-full bg-violet-600 hover:bg-violet-700 text-white font-black py-4 rounded-xl shadow-lg shadow-violet-200 transition-all transform hover:-translate-y-1 flex justify-center items-center gap-2">
                        <span>ENVOYER LE MESSAGE</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </form>
            </div>
    </div>
@endsection
