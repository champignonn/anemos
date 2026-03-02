<?php

namespace App\Http\Controllers;

use App\Models\TrainingSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use App\Models\User; // Assure-toi d'importer le modèle User

class StravaController extends Controller
{
    public function redirect()
    {
        $clientId = config('services.strava.client_id');
        $redirectUri = config('services.strava.redirect_uri');

        $url = "https://www.strava.com/oauth/authorize?" . http_build_query([
                'client_id' => $clientId,
                'redirect_uri' => $redirectUri,
                'response_type' => 'code',
                'scope' => 'read,activity:read_all,activity:write',
            ]);

        return redirect($url);
    }

    public function callback(Request $request)
    {
        $code = $request->input('code');

        if (!$code) {
            return redirect()->route('dashboard')->with('error', 'Erreur de connexion Strava.');
        }

        $response = Http::withoutVerifying()->post('https://www.strava.com/oauth/token', [
            'client_id' => config('services.strava.client_id'),
            'client_secret' => config('services.strava.client_secret'),
            'code' => $code,
            'grant_type' => 'authorization_code',
        ]);

        if ($response->successful()) {
            $data = $response->json();
            $stravaId = (string) $data['athlete']['id'];
            $user = Auth::user();

            // Vérification de doublon
            $duplicate = User::where('strava_id', $stravaId)
                ->where('id', '!=', $user->id)
                ->first();

            if ($duplicate) {
                return redirect()->route('dashboard')
                    ->with('error', 'Ce compte Strava est déjà utilisé par un autre membre.');
            }

            $user->update([
                'strava_id' => $stravaId,
                'strava_access_token' => $data['access_token'],
                'strava_refresh_token' => $data['refresh_token'],
                'strava_token_expires_at' => now()->addSeconds($data['expires_in']),
            ]);

            $this->syncRecentActivities();
            return redirect()->route('dashboard')->with('success', 'Ton Strava est bien connecté ! 🎉');
        }

        return redirect()->route('dashboard')->with('error', 'Erreur lors de la communication avec Strava.');
    }

    public function disconnect()
    {
        Auth::user()->update([
            'strava_id' => null,
            'strava_access_token' => null,
            'strava_refresh_token' => null,
            'strava_token_expires_at' => null,
        ]);

        return redirect()->route('dashboard')->with('success', 'Strava déconnecté.');
    }

    public function syncActivities()
    {
        $user = Auth::user();

        if (!$user->hasStravaConnected()) {
            return redirect()->route('dashboard')->with('error', 'Vous devez connecter Strava d\'abord.');
        }

        $count = $this->syncRecentActivities();

        return redirect()->route('dashboard')
            ->with('success', "Synchronisation terminée ! {$count} activités importées. 🏃‍♀️");
    }

    private function syncRecentActivities(): int
    {
        $user = Auth::user();

        if (!$user->hasStravaConnected()) {
            return 0;
        }

        $response = Http::withoutVerifying()
            ->withToken($user->strava_access_token)
            ->get('https://www.strava.com/api/v3/athlete/activities', [
                'per_page' => 30,
                'page' => 1
            ]);

        if (!$response->successful()) {
            return 0;
        }

        $activities = $response->json();
        $imported = 0;

        foreach ($activities as $activity) {
            if (!in_array($activity['type'], ['Run', 'TrailRun'])) {
                continue;
            }

            if (TrainingSession::where('strava_activity_id', $activity['id'])->exists()) {
                continue;
            }

            $this->createSessionFromStravaActivity($activity);
            $imported++;
        }

        return $imported;
    }

    private function createSessionFromStravaActivity(array $activity): void
    {
        $user = Auth::user();
        $distanceKm = $activity['distance'] / 1000;
        $durationMinutes = $activity['moving_time'] / 60;
        $paceMinKm = $distanceKm > 0 ? ($durationMinutes / $distanceKm) : 0;
        $type = $this->determineSessionType($distanceKm, $paceMinKm, $activity['total_elevation_gain'] ?? 0);
        $activePlan = $user->getActiveTrainingPlan();

        TrainingSession::create([
            'training_plan_id' => $activePlan?->id,
            'user_id' => $user->id,
            'title' => $activity['name'] ?? 'Sortie Strava',
            'description' => 'Importé depuis Strava',
            'type' => $type,
            'scheduled_date' => Carbon::parse($activity['start_date_local']),
            'status' => 'réalisé',
            'completed_at' => Carbon::parse($activity['start_date_local']),
            'actual_distance' => round($distanceKm, 2),
            'actual_elevation' => $activity['total_elevation_gain'] ?? 0,
            'actual_duration_minutes' => round($durationMinutes),
            'actual_pace' => round($paceMinKm, 2),
            'average_heart_rate' => $activity['average_heartrate'] ?? null,
            'max_heart_rate' => $activity['max_heartrate'] ?? null,
            'planned_distance' => round($distanceKm, 2),
            'planned_elevation' => $activity['total_elevation_gain'] ?? 0,
            'duration_minutes' => round($durationMinutes),
            'strava_activity_id' => (string)$activity['id'],
            'imported_from_strava' => true,
        ]);
    }

    private function determineSessionType(float $distance, float $pace, int $elevation): string
    {
        if ($distance > 20) return 'Sortie Longue';
        if ($elevation > 300 && $distance < 15) return 'Côtes';
        if ($elevation > 500) return 'Trail Technique';
        if ($distance < 8 && $pace < 4.5) return 'VMA';
        if ($distance >= 8 && $distance < 18 && $pace < 5.5) return 'Seuil';
        if ($pace < 6.0) return 'Tempo';
        return 'Endurance Fondamentale';
    }
}
