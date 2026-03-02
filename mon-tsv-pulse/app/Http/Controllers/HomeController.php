<?php

namespace App\Http\Controllers;

use App\Models\PublicRace;
use App\Models\Race;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $query = PublicRace::query()
            ->where('race_date', '>=', now())
            ->where('is_verified', true);

        // Filtres
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('difficulty')) {
            $query->where('difficulty_level', $request->difficulty);
        }

        if ($request->filled('city')) {
            $query->where('city', 'like', '%' . $request->city . '%');
        }

        if ($request->filled('distance_min')) {
            $query->where('distance', '>=', $request->distance_min);
        }

        if ($request->filled('distance_max')) {
            $query->where('distance', '<=', $request->distance_max);
        }

        // Tri
        $sort = $request->get('sort', 'date');

        switch ($sort) {
            case 'date':
                $query->orderBy('race_date');
                break;
            case 'distance':
                $query->orderBy('distance');
                break;
            case 'elevation':
                $query->orderBy('elevation_gain', 'desc');
                break;
            case 'name':
                $query->orderBy('name');
                break;
        }

        $publicRaces = $query->paginate(12);

        // Courses personnelles de l'utilisateur
        $userRaces = Auth::check()
            ? Auth::user()->races()->where('race_date', '>=', now())->orderBy('race_date')->take(3)->get()
            : collect();

        // Stats générales
        $stats = [
            'total_races' => PublicRace::where('race_date', '>=', now())->count(),
            'total_runners' => \App\Models\User::count(),
            'countries' => PublicRace::distinct('country')->count(),
        ];

        return view('home.index', compact('publicRaces', 'userRaces', 'stats'));
    }

    public function addToMyRaces(Request $request, PublicRace $publicRace)
    {
        $user = Auth::user();

        // Mapper les types de PublicRace vers les types Race
        $typeMapping = [
            'ultra' => 'trail',
            'vertical' => 'trail',
            'backyard' => 'boucle',
            'trail' => 'trail',
        ];

        // Créer une course personnelle à partir de la course publique
        $race = Race::create([
            'user_id' => $user->id,
            'name' => $publicRace->name,
            'description' => $publicRace->description,
            'race_date' => $publicRace->race_date,
            'type' => $typeMapping[$publicRace->type] ?? 'trail', // Mapper le type
            'distance' => $publicRace->distance,
            'elevation_gain' => $publicRace->elevation_gain,
            'location' => $publicRace->location,
            'priority' => $request->get('priority', 'B'),
            'status' => 'à_venir',
        ]);

        // Marquer l'intérêt
        $user->interestedInRaces()->attach($publicRace->id, [
            'status' => 'inscrit'
        ]);

        return redirect()->route('races.show', $race)
            ->with('success', 'Course ajoutée à votre calendrier ! Vous pouvez maintenant générer un plan d\'entraînement. 🎉');
    }

    public function markInterested(PublicRace $publicRace)
    {
        $user = Auth::user();

        if ($user->interestedInRaces()->where('public_race_id', $publicRace->id)->exists()) {
            $user->interestedInRaces()->detach($publicRace->id);
            $message = 'Course retirée de vos intérêts.';
        } else {
            $user->interestedInRaces()->attach($publicRace->id, [
                'status' => 'intéressé'
            ]);
            $message = 'Course ajoutée à vos intérêts ! ⭐';
        }

        return back()->with('success', $message);
    }

    public function suggestNearby(Request $request)
    {
        // Coordonnées par défaut (Lyon)
        $lat = $request->get('lat', 45.7640);
        $lng = $request->get('lng', 4.8357);
        $radius = $request->get('radius', 100); // km

        $races = PublicRace::query()
            ->where('race_date', '>=', now())
            ->where('is_verified', true)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get()
            ->filter(function ($race) use ($lat, $lng, $radius) {
                return $race->getDistanceFromUser($lat, $lng) <= $radius;
            })
            ->sortBy(function ($race) use ($lat, $lng) {
                return $race->getDistanceFromUser($lat, $lng);
            })
            ->take(6);

        return view('home.nearby', compact('races', 'lat', 'lng', 'radius'));
    }
}
