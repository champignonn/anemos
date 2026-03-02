<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TrainingSession;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $nextRace = $user->getNextRace();
        $activeTrainingPlan = $user->getActiveTrainingPlan();

        $statistics = [
            'total_distance' => TrainingSession::where('user_id', $user->id)->where('status', 'réalisé')->sum('actual_distance') ?? 0,
            'total_elevation' => TrainingSession::where('user_id', $user->id)->where('status', 'réalisé')->sum('actual_elevation') ?? 0,
            'total_sessions' => TrainingSession::where('user_id', $user->id)->where('status', 'réalisé')->count(),
            'completion_rate' => 100,
        ];

        $weeklyStats = [
            'sessions_count' => TrainingSession::where('user_id', $user->id)->where('status', 'réalisé')
                ->whereBetween('completed_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'distance' => TrainingSession::where('user_id', $user->id)->where('status', 'réalisé')
                    ->whereBetween('completed_at', [now()->startOfWeek(), now()->endOfWeek()])->sum('actual_distance') ?? 0,
            'elevation' => TrainingSession::where('user_id', $user->id)->where('status', 'réalisé')
                    ->whereBetween('completed_at', [now()->startOfWeek(), now()->endOfWeek()])->sum('actual_elevation') ?? 0,
        ];

        $recentSessions = TrainingSession::where('user_id', $user->id)->where('status', 'réalisé')
            ->orderBy('completed_at', 'desc')->limit(5)->get();

        $upcomingSessions = TrainingSession::where('user_id', $user->id)
            ->where('status', 'prévu')
            ->orderBy('scheduled_date')
            ->limit(7)
            ->get();

        return view('dashboard.index', compact('user', 'nextRace', 'activeTrainingPlan', 'statistics', 'weeklyStats', 'recentSessions', 'upcomingSessions'));
    }

    public function getChartData(Request $request)
    {
        $user = Auth::user();
        $offset = (int) $request->query('week_offset', 0);

        // On calcule le début et la fin de la semaine visée
        $startOfWeek = now()->startOfWeek()->addWeeks($offset);
        $endOfWeek = (clone $startOfWeek)->endOfWeek();

        // Récupération de toutes les séances de la semaine en une seule fois
        $sessions = TrainingSession::where('user_id', $user->id)
            ->where('status', 'réalisé')
            ->whereBetween('completed_at', [$startOfWeek, $endOfWeek])
            ->get()
            ->groupBy(function($date) {
                return Carbon::parse($date->completed_at)->format('Y-m-d');
            });

        $data = [];

        // On boucle sur les 7 jours de la semaine
        for ($i = 0; $i < 7; $i++) {
            $date = (clone $startOfWeek)->addDays($i);
            $dateString = $date->format('Y-m-d');

            $daySessions = $sessions->get($dateString, collect());

            $data[] = [
                'date' => $dateString,
                'label' => $date->translatedFormat('D d M'), // Ex: "lun. 24 fév."
                'distance' => round($daySessions->sum('actual_distance'), 2),
                'elevation' => (int) $daySessions->sum('actual_elevation'),
            ];
        }

        return response()->json($data);
    }
}
