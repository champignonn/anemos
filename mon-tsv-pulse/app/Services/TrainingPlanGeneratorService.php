<?php

namespace App\Services;

use App\Models\Race;
use App\Models\TrainingPlan;
use App\Models\TrainingSession;
use App\Models\User;
use Carbon\Carbon;

class TrainingPlanGeneratorService
{
    private User $user;
    private Race $race;
    private int $totalWeeks;
    private Carbon $startDate;
    private Carbon $raceDate;
    private string $raceFormat;

    public function __construct(User $user, Race $race, int $totalWeeks = 12)
    {
        $this->user = $user;
        $this->race = $race;
        $this->totalWeeks = $totalWeeks;
        $this->raceDate = Carbon::parse($race->race_date);
        $this->startDate = $this->raceDate->copy()->subWeeks($totalWeeks);
        $this->raceFormat = $this->detectRaceFormat();
    }

    /**
     * Détecte le format de course (backyard, verticale, classique, etc.)
     */
    private function detectRaceFormat(): string
    {
        $name = strtolower($this->race->name);
        $description = strtolower($this->race->description ?? '');
        $denivRatio = $this->race->elevation_gain / $this->race->distance;

        // Backyard Ultra
        if (str_contains($name, 'backyard') || str_contains($description, 'backyard')) {
            return 'backyard';
        }

        // Kilomètre Vertical
        if ($denivRatio > 300 || str_contains($name, 'vertical') || str_contains($name, 'km vertical')) {
            return 'vertical';
        }

        // Ultra long (100km+)
        if ($this->race->distance > 100) {
            return 'ultra_long';
        }

        // Trail technique (D+ élevé)
        if ($denivRatio > 80) {
            return 'technical';
        }

        // Trail classique
        return 'classic';
    }

    public function generate(): TrainingPlan
    {
        $phases = $this->calculatePhases();

        $trainingPlan = TrainingPlan::create([
            'user_id' => $this->user->id,
            'race_id' => $this->race->id,
            'name' => "Préparation {$this->race->name}",
            'description' => $this->generateDescription(),
            'start_date' => $this->startDate,
            'end_date' => $this->raceDate,
            'total_weeks' => $this->totalWeeks,
            'base_phase_weeks' => $phases['base'],
            'build_phase_weeks' => $phases['build'],
            'peak_phase_weeks' => $phases['peak'],
            'taper_weeks' => $phases['taper'],
            'weekly_distance_min' => $this->calculateMinWeeklyDistance(),
            'weekly_distance_max' => $this->calculateMaxWeeklyDistance(),
            'weekly_elevation_min' => (int)($this->race->elevation_gain * 0.3),
            'weekly_elevation_max' => (int)($this->race->elevation_gain * 1.0),
            'sessions_per_week' => $this->getSessionsPerWeek(),
            'status' => 'actif',
            'is_generated' => true,
        ]);

        $this->generateSessions($trainingPlan, $phases);
        return $trainingPlan->fresh(['trainingSessions']);
    }

    private function generateDescription(): string
    {
        return match($this->raceFormat) {
            'backyard' => "Plan spécifique backyard ultra : focus sur l'endurance mentale, la répétition de boucles et la capacité à courir fatigué. Entraînement par blocs de 1h avec récupération minimale.",
            'vertical' => "Plan spécifique kilomètre vertical : focus sur la force en montée, le renforcement musculaire et les séances de dénivelé intense.",
            'ultra_long' => "Plan ultra-distance : volume progressif important, sorties longues de 6-8h, back-to-back weekends, nutrition et gestion de l'effort.",
            'technical' => "Plan trail technique : focus sur le dénivelé, la descente technique, le renforcement et l'adaptation au terrain accidenté.",
            default => "Plan trail classique : périodisation structurée avec développement progressif de l'endurance et de l'intensité.",
        };
    }

    private function calculateMinWeeklyDistance(): int
    {
        return match($this->raceFormat) {
            'backyard' => 30, // Backyard = endurance pure, pas besoin de gros volume
            'vertical' => 20,  // Vertical = qualité > quantité
            'ultra_long' => 60,
            'technical' => 40,
            default => (int)($this->race->distance * 0.5),
        };
    }

    private function calculateMaxWeeklyDistance(): int
    {
        return match($this->raceFormat) {
            'backyard' => 60,
            'vertical' => 40,
            'ultra_long' => 120,
            'technical' => 80,
            default => (int)($this->race->distance * 1.5),
        };
    }

    private function getSessionsPerWeek(): int
    {
        return match($this->raceFormat) {
            'backyard' => 5, // Plus de séances courtes répétées
            'vertical' => 4,
            'ultra_long' => 5,
            default => 4,
        };
    }

    private function calculatePhases(): array
    {
        $taperWeeks = 2;
        $peakWeeks = (int)($this->totalWeeks * 0.25);
        $buildWeeks = (int)($this->totalWeeks * 0.35);
        $baseWeeks = $this->totalWeeks - $taperWeeks - $peakWeeks - $buildWeeks;

        return [
            'base' => max($baseWeeks, 2),
            'build' => max($buildWeeks, 2),
            'peak' => max($peakWeeks, 2),
            'taper' => $taperWeeks
        ];
    }

    private function generateSessions(TrainingPlan $trainingPlan, array $phases): void
    {
        $currentWeek = 1;
        $currentDate = $this->startDate->copy();

        foreach (['base', 'build', 'peak', 'taper'] as $phase) {
            for ($i = 0; $i < $phases[$phase]; $i++) {
                $isRecoveryWeek = $currentWeek % 4 == 0;
                $this->generateWeekSessions($trainingPlan, $currentWeek, $currentDate, $phase, $isRecoveryWeek);
                $currentWeek++;
                $currentDate->addWeek();
            }
        }
    }

    private function generateWeekSessions(TrainingPlan $trainingPlan, int $weekNumber, Carbon $weekStart, string $phase, bool $isRecoveryWeek): void
    {
        $sessionsPerWeek = $isRecoveryWeek ? 3 : $this->getSessionsPerWeek();
        $weeklyLoad = $this->calculateWeeklyLoad($weekNumber, $phase, $isRecoveryWeek);

        $sessionDays = match($sessionsPerWeek) {
            5 => [1, 2, 4, 5, 6],
            4 => [1, 3, 5, 6],
            3 => [2, 4, 6],
            default => [2, 4, 6],
        };

        $sessionTypes = $this->selectSessionTypes($phase, $sessionsPerWeek, $weekNumber);

        foreach ($sessionDays as $index => $dayOfWeek) {
            if (!isset($sessionTypes[$index])) continue;
            $sessionDate = $weekStart->copy()->addDays($dayOfWeek);
            $this->createSession($trainingPlan, $sessionDate, $sessionTypes[$index], $weeklyLoad, $phase, $weekNumber);
        }
    }

    private function selectSessionTypes(string $phase, int $count, int $weekNumber): array
    {
        // BACKYARD ULTRA SPECIFIQUE
        if ($this->raceFormat === 'backyard') {
            return $this->selectBackyardSessions($phase, $count, $weekNumber);
        }

        // VERTICAL SPECIFIQUE
        if ($this->raceFormat === 'vertical') {
            return $this->selectVerticalSessions($phase, $count, $weekNumber);
        }

        // ULTRA LONG
        if ($this->raceFormat === 'ultra_long') {
            return $this->selectUltraLongSessions($phase, $count, $weekNumber);
        }

        // CLASSIC
        return $this->selectClassicSessions($phase, $count);
    }

    /**
     * Plan spécifique BACKYARD ULTRA
     */
    private function selectBackyardSessions(string $phase, int $count, int $weekNumber): array
    {
        $sessions = [];

        // Toujours commencer par une séance de simulation backyard
        $sessions[] = 'Backyard Simulation';

        if ($phase === 'base') {
            $sessions[] = 'Endurance Fondamentale';
            $sessions[] = 'Tempo';
            if ($count >= 4) $sessions[] = 'Trail Technique';
            if ($count >= 5) $sessions[] = 'Récupération';
        }

        if ($phase === 'build') {
            $sessions[] = 'Backyard Simulation';
            $sessions[] = 'Seuil';
            if ($count >= 4) $sessions[] = 'Fartlek';
            if ($count >= 5) $sessions[] = 'Endurance Fondamentale';
        }

        if ($phase === 'peak') {
            $sessions[] = 'Backyard Long'; // Simulation de 6-8 boucles
            $sessions[] = 'Backyard Fatigue'; // Courir fatigué
            if ($count >= 4) $sessions[] = 'VMA';
            if ($count >= 5) $sessions[] = 'Récupération';
        }

        if ($phase === 'taper') {
            $sessions[] = 'Backyard Test';
            $sessions[] = 'Endurance Fondamentale';
            if ($count >= 3) $sessions[] = 'Repos';
        }

        return array_slice($sessions, 0, $count);
    }

    /**
     * Plan spécifique VERTICAL
     */
    private function selectVerticalSessions(string $phase, int $count): array
    {
        $sessions = ['Côtes'];

        if ($phase === 'base') {
            $sessions[] = 'Trail Technique';
            $sessions[] = 'Endurance Fondamentale';
            if ($count >= 4) $sessions[] = 'Renforcement';
        }

        if ($phase === 'build' || $phase === 'peak') {
            $sessions[] = 'Côtes Longues';
            $sessions[] = 'VMA';
            if ($count >= 4) $sessions[] = 'Trail Technique';
        }

        if ($phase === 'taper') {
            $sessions[] = 'Tempo';
            $sessions[] = 'Récupération';
            if ($count >= 3) $sessions[] = 'Repos';
        }

        return array_slice($sessions, 0, $count);
    }

    /**
     * Plan spécifique ULTRA LONG
     */
    private function selectUltraLongSessions(string $phase, int $count): array
    {
        $sessions = [];

        if ($phase === 'base' || $phase === 'build') {
            $sessions[] = 'Sortie Longue';
            $sessions[] = 'Endurance Fondamentale';
            $sessions[] = 'Tempo';
            if ($count >= 4) $sessions[] = 'Trail Technique';
            if ($count >= 5) $sessions[] = 'Récupération';
        }

        if ($phase === 'peak') {
            $sessions[] = 'Ultra Longue'; // 6-8h
            $sessions[] = 'Back to Back'; // Samedi + Dimanche
            $sessions[] = 'Seuil';
            if ($count >= 4) $sessions[] = 'Fartlek';
            if ($count >= 5) $sessions[] = 'Endurance Fondamentale';
        }

        if ($phase === 'taper') {
            $sessions[] = 'Endurance Fondamentale';
            $sessions[] = 'Tempo';
            if ($count >= 3) $sessions[] = 'Repos';
        }

        return array_slice($sessions, 0, $count);
    }

    /**
     * Plan CLASSIQUE
     */
    private function selectClassicSessions(string $phase, int $count): array
    {
        $distribution = [
            'base' => ['Endurance Fondamentale', 'Sortie Longue', 'Trail Technique', 'Récupération'],
            'build' => ['Endurance Fondamentale', 'Sortie Longue', 'Seuil', 'Tempo', 'Côtes'],
            'peak' => ['Sortie Longue', 'VMA', 'Seuil', 'Fartlek', 'Trail Technique'],
            'taper' => ['Endurance Fondamentale', 'Tempo', 'VMA', 'Récupération', 'Repos'],
        ];

        $sessions = $distribution[$phase];
        return array_slice($sessions, 0, $count);
    }

    private function calculateWeeklyLoad(int $weekNumber, string $phase, bool $isRecoveryWeek): array
    {
        $progressionFactor = min($weekNumber / $this->totalWeeks * 1.3, 1.0);

        $baseDistance = match($this->raceFormat) {
            'backyard' => 40,
            'vertical' => 25,
            'ultra_long' => 80,
            default => $this->race->distance * 0.7,
        };

        $baseElevation = match($this->raceFormat) {
            'backyard' => $this->race->elevation_gain * 8, // 8 boucles en semaine
            'vertical' => $this->race->elevation_gain * 1.5,
            'ultra_long' => $this->race->elevation_gain * 0.6,
            default => $this->race->elevation_gain * 0.5,
        };

        $phaseFactor = match($phase) {
            'base' => 0.6,
            'build' => 0.85,
            'peak' => 1.0,
            'taper' => 0.4,
            default => 0.7
        };

        $recoveryFactor = $isRecoveryWeek ? 0.65 : 1.0;

        return [
            'distance' => round($baseDistance * $phaseFactor * $progressionFactor * $recoveryFactor),
            'elevation' => round($baseElevation * $phaseFactor * $progressionFactor * $recoveryFactor),
        ];
    }

    private function createSession(TrainingPlan $trainingPlan, Carbon $date, string $type, array $weeklyLoad, string $phase, int $weekNumber): TrainingSession
    {
        $sessionSpecs = $this->getSessionSpecifications($type, $weeklyLoad, $phase, $weekNumber);

        return TrainingSession::create([
            'training_plan_id' => $trainingPlan->id,
            'user_id' => $this->user->id,
            'title' => $sessionSpecs['title'],
            'description' => $sessionSpecs['description'],
            'scheduled_date' => $date,
            'scheduled_time' => $sessionSpecs['time'],
            'type' => $this->mapToStandardType($type),
            'duration_minutes' => $sessionSpecs['duration'],
            'planned_distance' => $sessionSpecs['distance'],
            'planned_elevation' => $sessionSpecs['elevation'],
            'intensity_zone' => $sessionSpecs['zone'],
            'target_pace_min' => $sessionSpecs['pace_min'],
            'target_pace_max' => $sessionSpecs['pace_max'],
            'target_heart_rate' => $sessionSpecs['hr_target'],
            'workout_structure' => $sessionSpecs['structure'],
            'status' => 'prévu',
        ]);
    }

    private function mapToStandardType(string $customType): string
    {
        return match($customType) {
            'Backyard Simulation', 'Backyard Long', 'Backyard Fatigue', 'Backyard Test' => 'Sortie Longue',
            'Côtes Longues' => 'Côtes',
            'Ultra Longue', 'Back to Back' => 'Sortie Longue',
            'Renforcement' => 'Trail Technique',
            default => $customType,
        };
    }

    private function getSessionSpecifications(string $type, array $weeklyLoad, string $phase, int $weekNumber): array
    {
        $userPaces = $this->calculateUserPaces();

        // SÉANCES BACKYARD SPÉCIFIQUES
        if (str_contains($type, 'Backyard')) {
            return $this->getBackyardSessionSpec($type, $weekNumber, $userPaces);
        }

        // SÉANCES VERTICAL SPÉCIFIQUES
        if ($type === 'Côtes Longues') {
            return [
                'title' => 'Séance de Côtes Longues',
                'description' => '3-4 montées de 15-20min pour préparer le vertical',
                'time' => '08:00',
                'duration' => 120,
                'distance' => 12,
                'elevation' => min(1200, $this->race->elevation_gain),
                'zone' => 'Z4',
                'pace_min' => $userPaces['threshold'],
                'pace_max' => $userPaces['threshold'] + 1.0,
                'hr_target' => $this->getHeartRateForZone('Z4'),
                'structure' => [
                    'warmup' => '20min plat',
                    'main' => '3-4 montées de 15-20min, récup descente',
                    'cooldown' => 'Retour tranquille'
                ]
            ];
        }

        // SÉANCES ULTRA LONG SPÉCIFIQUES
        if ($type === 'Ultra Longue') {
            return [
                'title' => 'Sortie Ultra Longue',
                'description' => 'Sortie de 6-8h pour adaptation ultra-distance',
                'time' => '06:00',
                'duration' => 420, // 7h
                'distance' => 50,
                'elevation' => 2000,
                'zone' => 'Z2',
                'pace_min' => $userPaces['endurance'],
                'pace_max' => $userPaces['endurance'] + 1.0,
                'hr_target' => $this->getHeartRateForZone('Z2'),
                'structure' => [
                    'warmup' => 'Progressif 30min',
                    'main' => '6-7h en endurance, ravitaillement toutes les heures',
                    'cooldown' => 'Dernière heure tranquille'
                ]
            ];
        }

        // SÉANCES STANDARDS (code existant)
        return $this->getStandardSessionSpec($type, $weeklyLoad, $phase, $userPaces);
    }

    /**
     * Spécifications séances BACKYARD
     */
    private function getBackyardSessionSpec(string $type, int $weekNumber, array $userPaces): array
    {
        $raceDistance = $this->race->distance; // 5.7km
        $raceElevation = $this->race->elevation_gain; // 280m

        return match($type) {
            'Backyard Simulation' => [
                'title' => 'Simulation Backyard (3 boucles)',
                'description' => "3 répétitions de {$raceDistance}km avec {$raceElevation}m D+ - Récup 5min entre chaque",
                'time' => '08:00',
                'duration' => 180,
                'distance' => $raceDistance * 3,
                'elevation' => $raceElevation * 3,
                'zone' => 'Z2',
                'pace_min' => $userPaces['endurance'],
                'pace_max' => $userPaces['endurance'] + 0.5,
                'hr_target' => $this->getHeartRateForZone('Z2'),
                'structure' => [
                    'warmup' => '15min jogging',
                    'main' => "3x boucle de {$raceDistance}km + {$raceElevation}m D+, récup 5min",
                    'cooldown' => 'Retour calme 10min'
                ]
            ],
            'Backyard Long' => [
                'title' => 'Backyard Long (6-8 boucles)',
                'description' => "Simulation longue : 6-8 boucles pour tester l'endurance mentale",
                'time' => '08:00',
                'duration' => 360,
                'distance' => $raceDistance * 7,
                'elevation' => $raceElevation * 7,
                'zone' => 'Z2',
                'pace_min' => $userPaces['endurance'] + 0.2,
                'pace_max' => $userPaces['endurance'] + 0.8,
                'hr_target' => $this->getHeartRateForZone('Z2'),
                'structure' => [
                    'warmup' => 'Progressif 10min',
                    'main' => "6-8 boucles identiques, récup 3-5min entre chaque",
                    'cooldown' => 'Dernière boucle très tranquille'
                ]
            ],
            'Backyard Fatigue' => [
                'title' => 'Backyard en Fatigue',
                'description' => 'Boucles sur jambes lourdes pour simuler H20+',
                'time' => '18:00',
                'duration' => 120,
                'distance' => $raceDistance * 4,
                'elevation' => $raceElevation * 4,
                'zone' => 'Z2',
                'pace_min' => $userPaces['endurance'] + 0.5,
                'pace_max' => $userPaces['endurance'] + 1.0,
                'hr_target' => $this->getHeartRateForZone('Z2'),
                'structure' => [
                    'warmup' => 'Pas d\'échauffement (simuler la fatigue)',
                    'main' => "4 boucles sans récup optimale",
                    'cooldown' => 'Marche 10min'
                ]
            ],
            'Backyard Test' => [
                'title' => 'Test Final Backyard',
                'description' => 'Dernière simulation complète pré-course',
                'time' => '08:00',
                'duration' => 240,
                'distance' => $raceDistance * 5,
                'elevation' => $raceElevation * 5,
                'zone' => 'Z2',
                'pace_min' => $userPaces['endurance'],
                'pace_max' => $userPaces['endurance'] + 0.3,
                'hr_target' => $this->getHeartRateForZone('Z2'),
                'structure' => [
                    'warmup' => 'Échauffement course',
                    'main' => "5 boucles au rythme objectif course",
                    'cooldown' => 'Retour calme'
                ]
            ],
            default => []
        };
    }

    private function getStandardSessionSpec(string $type, array $weeklyLoad, string $phase, array $userPaces): array
    {
        // Code existant pour VMA, Seuil, Tempo, etc...
        return match($type) {
            'VMA' => [
                'title' => 'Séance VMA',
                'description' => '10x400m @ VMA',
                'time' => '18:00',
                'duration' => 60,
                'distance' => 10,
                'elevation' => 50,
                'zone' => 'Z5',
                'pace_min' => $userPaces['vma'],
                'pace_max' => $userPaces['vma'] + 0.2,
                'hr_target' => $this->getHeartRateForZone('Z5'),
                'structure' => ['warmup' => '20min', 'main' => '10x400m R:1min', 'cooldown' => '10min']
            ],
            'Seuil' => [
                'title' => 'Sortie au Seuil',
                'description' => '3x15min @ seuil',
                'time' => '18:00',
                'duration' => 90,
                'distance' => 15,
                'elevation' => 100,
                'zone' => 'Z4',
                'pace_min' => $userPaces['threshold'],
                'pace_max' => $userPaces['threshold'] + 0.15,
                'hr_target' => $this->getHeartRateForZone('Z4'),
                'structure' => ['warmup' => '15min', 'main' => '3x15min R:3min', 'cooldown' => '10min']
            ],
            'Tempo' => [
                'title' => 'Sortie Tempo',
                'description' => '40min tempo',
                'time' => '18:00',
                'duration' => 75,
                'distance' => 12,
                'elevation' => 150,
                'zone' => 'Z3',
                'pace_min' => $userPaces['tempo'],
                'pace_max' => $userPaces['tempo'] + 0.2,
                'hr_target' => $this->getHeartRateForZone('Z3'),
                'structure' => ['warmup' => '15min', 'main' => '40min tempo', 'cooldown' => '10min']
            ],
            'Sortie Longue' => [
                'title' => 'Sortie Longue',
                'description' => 'Sortie longue endurance',
                'time' => '08:00',
                'duration' => $phase === 'taper' ? 90 : 180,
                'distance' => $phase === 'taper'
                    ? 15
                    : max(round($weeklyLoad['distance'] * 0.4), 20), // Minimum 20km, 40% du volume hebdo
                'elevation' => $phase === 'taper'
                    ? 300
                    : max(round($weeklyLoad['elevation'] * 0.45), 500), // Minimum 500m, 45% du D+ hebdo
                'zone' => 'Z2',
                'pace_min' => $userPaces['endurance'],
                'pace_max' => $userPaces['endurance'] + 0.3,
                'hr_target' => $this->getHeartRateForZone('Z2'),
                'structure' => ['warmup' => 'Progressif 10min', 'main' => 'Endurance avec D+', 'cooldown' => 'Retour']
            ],
            'Endurance Fondamentale' => [
                'title' => 'Endurance Fondamentale',
                'description' => 'Sortie EF',
                'time' => '18:00',
                'duration' => 60,
                'distance' => max(round($weeklyLoad['distance'] * 0.15), 10), // Au moins 10km
                'elevation' => max(round($weeklyLoad['elevation'] * 0.15), 150), // Au moins 150m
                'zone' => 'Z2',
                'pace_min' => $userPaces['endurance'],
                'pace_max' => $userPaces['endurance'] + 0.4,
                'hr_target' => $this->getHeartRateForZone('Z2'),
                'structure' => ['warmup' => '5min', 'main' => 'EF continue', 'cooldown' => '5min']
            ],
            'Fartlek' => [
                'title' => 'Fartlek',
                'description' => 'Variations d\'allure',
                'time' => '18:00',
                'duration' => 75,
                'distance' => 12,
                'elevation' => 200,
                'zone' => 'Z3',
                'pace_min' => $userPaces['tempo'],
                'pace_max' => $userPaces['vma'],
                'hr_target' => $this->getHeartRateForZone('Z3'),
                'structure' => ['warmup' => '15min', 'main' => '6-8 accélérations libres', 'cooldown' => '10min']
            ],
            'Côtes' => [
                'title' => 'Séance de Côtes',
                'description' => 'Répétitions côte',
                'time' => '18:00',
                'duration' => 70,
                'distance' => 8,
                'elevation' => 400,
                'zone' => 'Z4',
                'pace_min' => $userPaces['threshold'] - 0.5,
                'pace_max' => $userPaces['threshold'],
                'hr_target' => $this->getHeartRateForZone('Z4'),
                'structure' => ['warmup' => '20min', 'main' => '8-10 côtes 2-3min', 'cooldown' => '10min']
            ],
            'Trail Technique' => [
                'title' => 'Trail Technique',
                'description' => 'Trail technique varié',
                'time' => '08:00',
                'duration' => 120,
                'distance' => max(round($weeklyLoad['distance'] * 0.25), 15), // Au moins 15km
                'elevation' => max(round($weeklyLoad['elevation'] * 0.35), 600), // Au moins 600m
                'zone' => 'Z2',
                'pace_min' => $userPaces['endurance'] + 0.3,
                'pace_max' => $userPaces['endurance'] + 0.8,
                'hr_target' => $this->getHeartRateForZone('Z2'),
                'structure' => ['warmup' => 'Progressif', 'main' => 'Terrain technique varié', 'cooldown' => 'Descente']
            ],
            'Récupération' => [
                'title' => 'Récupération Active',
                'description' => 'Sortie récup',
                'time' => '18:00',
                'duration' => 40,
                'distance' => 6,
                'elevation' => 50,
                'zone' => 'Z1',
                'pace_min' => $userPaces['endurance'] + 0.5,
                'pace_max' => $userPaces['endurance'] + 1.0,
                'hr_target' => $this->getHeartRateForZone('Z1'),
                'structure' => ['warmup' => 'Doux', 'main' => 'Footing tranquille', 'cooldown' => 'Étirements']
            ],
            'Repos' => [
                'title' => 'Repos',
                'description' => 'Repos complet',
                'time' => null,
                'duration' => 0,
                'distance' => 0,
                'elevation' => 0,
                'zone' => null,
                'pace_min' => null,
                'pace_max' => null,
                'hr_target' => null,
                'structure' => null
            ],
            default => [
                'title' => 'Entraînement',
                'description' => '',
                'time' => '18:00',
                'duration' => 60,
                'distance' => 10,
                'elevation' => 100,
                'zone' => 'Z2',
                'pace_min' => $userPaces['endurance'],
                'pace_max' => $userPaces['endurance'] + 0.3,
                'hr_target' => $this->getHeartRateForZone('Z2'),
                'structure' => null
            ],
        };
    }

    private function calculateUserPaces(): array
    {
        $endurancePace = $this->user->endurance_pace ?? 6.0;
        $vma = $this->user->vma ?? 14.0;

        return [
            'endurance' => $endurancePace,
            'tempo' => $endurancePace - 0.5,
            'threshold' => $endurancePace - 1.0,
            'vma' => 60 / $vma,
        ];
    }

    private function getHeartRateForZone(string $zone): ?int
    {
        $zones = $this->user->getHeartRateZones();
        if (empty($zones) || !isset($zones[$zone])) return null;
        return (int)(($zones[$zone]['min'] + $zones[$zone]['max']) / 2);
    }
}
