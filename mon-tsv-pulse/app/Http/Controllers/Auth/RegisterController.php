<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'vma' => ['nullable', 'numeric', 'min:8', 'max:25'],
            'endurance_pace' => ['nullable', 'numeric', 'min:3', 'max:10'],
            'max_heart_rate' => ['nullable', 'integer', 'min:120', 'max:220'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'vma' => $validated['vma'] ?? 14,
            'endurance_pace' => $validated['endurance_pace'] ?? 6,
            'threshold_pace' => ($validated['endurance_pace'] ?? 6) - 1,
            'max_heart_rate' => $validated['max_heart_rate'] ?? 190,
            'resting_heart_rate' => 60,
            'experience_level' => 'intermédiaire',
        ]);

        Auth::login($user);

        return redirect('/dashboard')->with('success', 'Bienvenue sur TSV-Pulse ! 🎉');
    }
}
