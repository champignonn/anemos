<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Follower;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show(User $user)
    {
        $user->load(['races', 'trainingPlans', 'vlogs']);

        $stats = [
            'total_distance' => $user->trainingSessions()->where('status', 'réalisé')->sum('actual_distance'),
            'total_elevation' => $user->trainingSessions()->where('status', 'réalisé')->sum('actual_elevation'),
            'total_sessions' => $user->trainingSessions()->where('status', 'réalisé')->count(),
            'followers' => $user->followersCount(),
            'following' => $user->followingCount(),
        ];

        $recentVlogs = $user->vlogs()
            ->where(function($query) {
                if (Auth::id() !== request()->route('user')->id) {
                    $query->where('visibility', 'public')
                        ->orWhere(function($q) {
                            $q->where('visibility', 'followers')
                                ->whereHas('user.followers', function($sq) {
                                    $sq->where('follower_id', Auth::id());
                                });
                        });
                }
            })
            ->latest()
            ->limit(6)
            ->get();

        $isFollowing = Auth::check() && Auth::user()->isFollowing($user->id);

        return view('profile.show', compact('user', 'stats', 'recentVlogs', 'isFollowing'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'bio' => 'nullable|string|max:500',
            'avatar' => 'nullable|image|max:2048',
            'vma' => 'nullable|numeric|min:8|max:25',
            'endurance_pace' => 'nullable|numeric|min:3|max:10',
            'threshold_pace' => 'nullable|numeric|min:3|max:10',
            'max_heart_rate' => 'nullable|integer|min:120|max:220',
            'resting_heart_rate' => 'nullable|integer|min:30|max:100',
            'weight' => 'nullable|integer|min:40|max:150',
        ]);

        // Upload avatar
        if ($request->hasFile('avatar')) {
            // Supprimer ancien avatar
            if ($user->avatar_path) {
                Storage::disk('public')->delete($user->avatar_path);
            }
            $validated['avatar_path'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($validated);

        return redirect()->route('profile.show', $user)
            ->with('success', 'Profil mis à jour ! ✅');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Mot de passe actuel incorrect.']);
        }

        $user->update([
            'password' => Hash::make($validated['password'])
        ]);

        return back()->with('success', 'Mot de passe modifié ! 🔒');
    }

    public function follow(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Vous ne pouvez pas vous suivre vous-même.');
        }

        if (Auth::user()->isFollowing($user->id)) {
            return back()->with('error', 'Vous suivez déjà cet utilisateur.');
        }

        Follower::create([
            'follower_id' => Auth::id(),
            'following_id' => $user->id,
        ]);

        \App\Helpers\NotificationHelper::newFollower(Auth::user(), $user);

        return back()->with('success', 'Vous suivez maintenant ' . $user->name . ' ! 👥');
    }

    public function unfollow(User $user)
    {
        Follower::where('follower_id', Auth::id())
            ->where('following_id', $user->id)
            ->delete();

        return back()->with('success', 'Vous ne suivez plus ' . $user->name . '.');
    }

    public function followers(User $user)
    {
        $followers = User::whereIn('id', $user->followers()->pluck('follower_id'))
            ->get();

        return view('profile.followers', compact('user', 'followers'));
    }

    public function following(User $user)
    {
        $following = User::whereIn('id', $user->following()->pluck('following_id'))
            ->get();

        return view('profile.following', compact('user', 'following'));
    }
}
