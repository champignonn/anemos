@props(['sessions'])

<div class="bg-white rounded-2xl shadow-2xl overflow-hidden" x-data="{ currentWeek: 0 }">
    <!-- Header -->
    <div class="bg-gradient-to-r from-moss to-olivine p-6 text-white">
        <div class="flex justify-between items-center">
            <button @click="currentWeek--" class="p-2 hover:bg-white/20 rounded-lg transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>

            <div class="text-center">
                <h3 class="text-2xl font-bold font-serif">Planning de la semaine</h3>
                <p class="text-paper/80 text-sm mt-1" x-text="getWeekRange()"></p>
            </div>

            <button @click="currentWeek++" class="p-2 hover:bg-white/20 rounded-lg transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Calendrier -->
    <div class="p-6">
        <div class="grid grid-cols-7 gap-2">
            @php
                $daysOfWeek = ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'];
                $today = now();
                $startOfWeek = $today->copy()->startOfWeek();
            @endphp

            @foreach($daysOfWeek as $index => $day)
                @php
                    $currentDay = $startOfWeek->copy()->addDays($index);
                    $daySessions = $sessions->filter(function($session) use ($currentDay) {
                        return $session->scheduled_date->isSameDay($currentDay);
                    });
                    $isToday = $currentDay->isToday();
                @endphp

                <div class="group">
                    <!-- En-tête du jour -->
                    <div class="text-center mb-3 pb-2 border-b-2 {{ $isToday ? 'border-auburn' : 'border-gray-200' }}">
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wide">{{ $day }}</p>
                        <p class="text-2xl font-bold {{ $isToday ? 'text-auburn' : 'text-moss' }} mt-1">
                            {{ $currentDay->format('d') }}
                        </p>
                        @if($isToday)
                            <span class="inline-block px-2 py-0.5 bg-auburn text-white text-xs rounded-full mt-1 animate-pulse">
                            Aujourd'hui
                        </span>
                        @endif
                    </div>

                    <!-- Séances du jour -->
                    <div class="space-y-2 min-h-[100px]">
                        @forelse($daySessions as $session)
                            <a href="{{ route('training-sessions.show', $session) }}"
                               class="block p-3 rounded-xl transition-all duration-300 transform hover:scale-105 hover:shadow-lg border-l-4"
                               style="background: {{ $session->intensity_color }}15; border-color: {{ $session->intensity_color }}">
                                <div class="flex items-start justify-between gap-2">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs font-bold text-moss truncate">{{ $session->type }}</p>
                                        <p class="text-xs text-gray-600 mt-1">
                                            {{ $session->planned_distance }}km
                                        </p>
                                        @if($session->scheduled_time)
                                            <p class="text-xs text-gray-500">
                                                {{ $session->scheduled_time->format('H:i') }}
                                            </p>
                                        @endif
                                    </div>
                                    @if($session->status === 'réalisé')
                                        <svg class="w-4 h-4 text-olivine flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                    @endif
                                </div>
                            </a>
                        @empty
                            <div class="h-20 flex items-center justify-center text-gray-300 group-hover:text-gray-400 transition-colors">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                            </div>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Footer avec stats de la semaine -->
    <div class="bg-gradient-to-r from-paper to-sky/20 px-6 py-4 border-t">
        <div class="flex justify-around text-center">
            <div>
                <p class="text-2xl font-bold text-moss">{{ $sessions->where('scheduled_date', '>=', $startOfWeek)->where('scheduled_date', '<=', $startOfWeek->copy()->endOfWeek())->count() }}</p>
                <p class="text-xs text-gray-600">Séances</p>
            </div>
            <div>
                <p class="text-2xl font-bold text-moss">{{ number_format($sessions->where('scheduled_date', '>=', $startOfWeek)->where('scheduled_date', '<=', $startOfWeek->copy()->endOfWeek())->sum('planned_distance'), 0) }}</p>
                <p class="text-xs text-gray-600">Km prévus</p>
            </div>
            <div>
                <p class="text-2xl font-bold text-moss">{{ number_format($sessions->where('scheduled_date', '>=', $startOfWeek)->where('scheduled_date', '<=', $startOfWeek->copy()->endOfWeek())->sum('planned_elevation'), 0) }}</p>
                <p class="text-xs text-gray-600">D+ prévu</p>
            </div>
        </div>
    </div>
</div>

<script>
    function getWeekRange() {
        const today = new Date();
        const monday = new Date(today);
        monday.setDate(today.getDate() - today.getDay() + 1);
        const sunday = new Date(monday);
        sunday.setDate(monday.getDate() + 6);

        const options = { day: 'numeric', month: 'long' };
        return monday.toLocaleDateString('fr-FR', options) + ' - ' + sunday.toLocaleDateString('fr-FR', options);
    }
</script>
