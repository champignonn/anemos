<?php

namespace App\Http\Controllers;

use App\Models\TrainingSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TrainingSessionController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $query = Auth::user()->trainingSessions()
            ->with('trainingPlan')
            ->orderBy('scheduled_date', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $sessions = $query->paginate(20)->withQueryString();

        return view('training-sessions.index', compact('sessions'));
    }

    public function show(TrainingSession $trainingSession)
    {
        $this->authorize('view', $trainingSession);
        return view('training-sessions.show', compact('trainingSession'));
    }

    public function edit(TrainingSession $trainingSession)
    {
        $this->authorize('update', $trainingSession);
        return view('training-sessions.edit', compact('trainingSession'));
    }

    public function update(Request $request, TrainingSession $trainingSession)
    {
        $this->authorize('update', $trainingSession);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'scheduled_date' => 'required|date',
            'type' => 'required|in:VMA,Seuil,Tempo,Sortie Longue,Endurance Fondamentale,Fartlek,Côtes,Trail Technique,Récupération,Repos',
            'planned_distance' => 'nullable|numeric|min:0',
            'planned_elevation' => 'nullable|integer|min:0',
            'duration_minutes' => 'nullable|integer|min:0',
        ]);

        $trainingSession->update($validated);

        return redirect()->route('training-sessions.show', $trainingSession)
            ->with('success', 'Séance modifiée ! ✅');
    }

    public function destroy(TrainingSession $trainingSession)
    {
        $this->authorize('delete', $trainingSession);
        $trainingSession->delete();

        return redirect()->route('training-sessions.index')
            ->with('success', 'Séance supprimée.');
    }

    public function complete(Request $request, TrainingSession $trainingSession)
    {
        $this->authorize('update', $trainingSession);

        $validated = $request->validate([
            'actual_distance' => 'required|numeric|min:0',
            'actual_elevation' => 'required|integer|min:0',
            'actual_duration_minutes' => 'required|integer|min:1',
            'average_heart_rate' => 'nullable|integer|min:50|max:220',
            'rpe' => 'nullable|integer|min:1|max:10',
            'notes' => 'nullable|string',
        ]);

        $validated['status'] = 'réalisé';
        $validated['completed_at'] = now();

        if ($validated['actual_distance'] > 0 && $validated['actual_duration_minutes'] > 0) {
            $validated['actual_pace'] = round($validated['actual_duration_minutes'] / $validated['actual_distance'], 2);
        }

        $trainingSession->update($validated);

        return redirect()->route('training-sessions.show', $trainingSession)
            ->with('success', 'Séance validée ! 🎉');
    }

    public function skip(TrainingSession $trainingSession)
    {
        $this->authorize('update', $trainingSession);

        $trainingSession->update(['status' => 'manqué']);

        return redirect()->back()
            ->with('success', 'Séance marquée comme manquée.');
    }

    public function reschedule(Request $request, TrainingSession $trainingSession)
    {
        $this->authorize('update', $trainingSession);

        $validated = $request->validate([
            'new_date' => 'required|date|after_or_equal:today',
        ]);

        $trainingSession->update([
            'scheduled_date' => $validated['new_date'],
            'status' => 'reporté',
        ]);

        return redirect()->route('training-sessions.show', $trainingSession)
            ->with('success', 'Séance décalée au ' . \Carbon\Carbon::parse($validated['new_date'])->format('d/m/Y'));
    }
}
