<?php

namespace App\Http\Controllers;

use App\Models\Vlog;
use App\Models\Comment;
use App\Models\TrainingSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class VlogController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $vlogs = Auth::user()->vlogs()
            ->with('trainingSession')
            ->latest()
            ->paginate(12);

        return view('vlogs.index', compact('vlogs'));
    }

    public function create()
    {
        $sessions = Auth::user()->trainingSessions()
            ->where('status', 'réalisé')
            ->whereDoesntHave('vlog')
            ->orderBy('completed_at', 'desc')
            ->get();

        return view('vlogs.create', compact('sessions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'training_session_id' => 'required|exists:training_sessions,id',
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'video_url' => 'nullable|url',
            'photos.*' => 'nullable|image|max:10240', // 10MB max
            'mood' => 'nullable|in:excellent,bien,moyen,difficile,terrible',
            'visibility' => 'required|in:public,followers,private',
            'tags' => 'nullable|string',
        ]);

        $session = TrainingSession::findOrFail($validated['training_session_id']);
        if ($session->user_id !== Auth::id()) {
            abort(403);
        }

        $photoPaths = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('vlogs/photos', 'public');
                $photoPaths[] = $path;
            }
        }

        $vlog = Vlog::create([
            'training_session_id' => $validated['training_session_id'],
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'content' => $validated['content'],
            'video_url' => $validated['video_url'],
            'photo_paths' => $photoPaths,
            'mood' => $validated['mood'],
            'visibility' => $validated['visibility'],
            'tags' => $validated['tags'] ? explode(',', $validated['tags']) : null,
            'published_at' => now(),
        ]);

        return redirect()->route('vlogs.show', $vlog)
            ->with('success', 'Vlog publié ! 🎉');
    }

    public function show(Vlog $vlog)
    {
        if (!$this->canView($vlog)) {
            abort(403, 'Vous n\'avez pas accès à ce vlog.');
        }

        // Chargement des relations et des commentaires (du plus récent au plus ancien)
        $vlog->load(['trainingSession', 'user', 'comments.user' => function($query) {
            $query->latest();
        }]);

        // LOGIQUE DE VUE UNIQUE : On enregistre la vue seulement si l'utilisateur ne l'a jamais vu
        if (Auth::check() && !$vlog->views()->where('user_id', Auth::id())->exists()) {
            $vlog->views()->attach(Auth::id());
            $vlog->increment('views_count');
        }

        return view('vlogs.show', compact('vlog'));
    }

    public function edit(Vlog $vlog)
    {
        $this->authorize('update', $vlog);

        $sessions = Auth::user()->trainingSessions()
            ->where('status', 'réalisé')
            ->orderBy('completed_at', 'desc')
            ->get();

        return view('vlogs.edit', compact('vlog', 'sessions'));
    }

    public function update(Request $request, Vlog $vlog)
    {
        $this->authorize('update', $vlog);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'video_url' => 'nullable|url',
            'photos.*' => 'nullable|image|max:10240',
            'mood' => 'nullable|in:excellent,bien,moyen,difficile,terrible',
            'visibility' => 'required|in:public,followers,private',
            'tags' => 'nullable|string',
            'remove_photos' => 'nullable|array',
        ]);

        if ($request->has('remove_photos')) {
            $currentPhotos = $vlog->photo_paths ?? [];
            foreach ($request->remove_photos as $photoPath) {
                Storage::disk('public')->delete($photoPath);
                $currentPhotos = array_diff($currentPhotos, [$photoPath]);
            }
            $vlog->photo_paths = array_values($currentPhotos);
        }

        if ($request->hasFile('photos')) {
            $photoPaths = $vlog->photo_paths ?? [];
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('vlogs/photos', 'public');
                $photoPaths[] = $path;
            }
            $vlog->photo_paths = $photoPaths;
        }

        $vlog->title = $validated['title'];
        $vlog->content = $validated['content'];
        $vlog->video_url = $validated['video_url'];
        $vlog->mood = $validated['mood'];
        $vlog->visibility = $validated['visibility'];
        $vlog->tags = $validated['tags'] ? explode(',', $validated['tags']) : null;

        $vlog->save();

        return redirect()->route('vlogs.show', $vlog)
            ->with('success', 'Vlog mis à jour ! ✅');
    }

    public function destroy(Vlog $vlog)
    {
        $this->authorize('delete', $vlog);

        if ($vlog->photo_paths) {
            foreach ($vlog->photo_paths as $path) {
                Storage::disk('public')->delete($path);
            }
        }

        $vlog->delete();

        return redirect()->route('vlogs.index')
            ->with('success', 'Vlog supprimé.');
    }

    public function like(Vlog $vlog)
    {
        if (!$this->canView($vlog)) {
            abort(403);
        }

        $user = Auth::user();

        if ($vlog->likes()->where('user_id', $user->id)->exists()) {
            $vlog->likes()->detach($user->id);
            $vlog->decrement('likes_count');
            $msg = 'Like retiré.';
        } else {
            $vlog->likes()->attach($user->id);
            $vlog->increment('likes_count');

            \App\Helpers\NotificationHelper::vlogLiked($user, $vlog);
            $msg = 'Vlog liké ! ❤️';
        }

        return back()->with('success', $msg);
    }

    // AJOUTER UN COMMENTAIRE
    public function storeComment(Request $request, Vlog $vlog)
    {
        if (!$this->canView($vlog)) {
            abort(403);
        }

        $validated = $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $vlog->comments()->create([
            'user_id' => Auth::id(),
            'content' => $validated['content']
        ]);

        return back()->with('success', 'Commentaire publié !');
    }

    public function feed()
    {
        $vlogs = Vlog::with(['user', 'trainingSession', 'likes']) // On eager load 'likes' pour le coeur rouge
        ->where(function($query) {
            $query->where('visibility', 'public')
                ->orWhere(function($q) {
                    $q->where('visibility', 'followers')
                        ->whereIn('user_id', Auth::user()->following()->pluck('following_id'));
                });
        })
            ->latest('published_at')
            ->paginate(12);

        return view('vlogs.feed', compact('vlogs'));
    }

    private function canView(Vlog $vlog): bool
    {
        if ($vlog->user_id === Auth::id()) {
            return true;
        }

        if ($vlog->visibility === 'public') {
            return true;
        }

        if ($vlog->visibility === 'followers') {
            return Auth::user()->isFollowing($vlog->user_id);
        }

        return false;
    }
}
