<?php

namespace App\Http\Controllers;

use App\Models\Race;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class RaceController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        $races = Auth::user()->races()->orderBy('race_date')->get();
        return view('races.index', compact('races'));
    }

    public function create()
    {
        return view('races.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'race_date' => 'required|date|after:today',
            'distance' => 'required|numeric|min:5|max:300',
            'elevation_gain' => 'required|integer|min:0|max:20000',
            'location' => 'nullable|string|max:255',
            'type' => 'required|in:boucle,classique',
            'priority' => 'required|in:A,B,C',
            'target_time' => 'nullable|date_format:H:i',
            'description' => 'nullable|string',
        ]);

        $race = Auth::user()->races()->create($validated);

        return redirect()->route('races.show', $race)
            ->with('success', 'Course créée avec succès ! 🏃‍♀️');
    }

    public function show(Race $race)
    {
        $this->authorize('view', $race);
        return view('races.show', compact('race'));
    }

    public function edit(Race $race)
    {
        $this->authorize('update', $race);
        return view('races.edit', compact('race'));
    }

    public function update(Request $request, Race $race)
    {
        $this->authorize('update', $race);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'race_date' => 'required|date',
            'distance' => 'required|numeric|min:5',
            'elevation_gain' => 'required|integer|min:0',
            'location' => 'nullable|string|max:255',
            'type' => 'required|in:boucle,classique',
            'priority' => 'required|in:A,B,C',
            'status' => 'required|in:à_venir,en_préparation,terminée,abandonnée',
            'target_time' => 'nullable|date_format:H:i',
            'description' => 'nullable|string',
        ]);

        $race->update($validated);

        return redirect()->route('races.show', $race)
            ->with('success', 'Course mise à jour ! ✅');
    }

    public function destroy(Race $race)
    {
        $this->authorize('delete', $race);
        $race->delete();
        return redirect()->route('races.index')
            ->with('success', 'Course supprimée.');
    }
}
