<?php

namespace App\Http\Controllers;

use App\Models\Race;
use App\Models\TrainingPlan;
use App\Services\TrainingPlanGeneratorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TrainingPlanController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        $plans = Auth::user()->trainingPlans()->with('race')->latest()->get();
        return view('training-plans.index', compact('plans'));
    }

    public function show(TrainingPlan $trainingPlan)
    {
        $this->authorize('view', $trainingPlan);
        $trainingPlan->load('race', 'trainingSessions');
        return view('training-plans.show', compact('trainingPlan'));
    }

    public function generate(Request $request, Race $race)
    {
        $this->authorize('view', $race);

        // Vérifier qu'il n'y a pas déjà un plan actif pour cette course
        $existingPlan = $race->trainingPlans()->where('status', 'actif')->first();
        if ($existingPlan) {
            return redirect()->route('training-plans.show', $existingPlan)
                ->with('info', 'Un plan actif existe déjà pour cette course.');
        }

        $weeks = $request->input('weeks', 12);

        $generator = new TrainingPlanGeneratorService(Auth::user(), $race, $weeks);
        $plan = $generator->generate();

        return redirect()->route('training-plans.show', $plan)
            ->with('success', "Plan généré avec succès ! 🎉 {$plan->trainingSessions()->count()} séances créées.");
    }

    public function destroy(TrainingPlan $trainingPlan)
    {
        $this->authorize('delete', $trainingPlan);
        $trainingPlan->delete();

        return redirect()->route('training-plans.index')
            ->with('success', 'Plan supprimé.');
    }
}
