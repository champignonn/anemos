<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RaceController;
use App\Http\Controllers\TrainingPlanController;
use App\Http\Controllers\TrainingSessionController;
use App\Http\Controllers\VlogController;
use App\Http\Controllers\StravaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;

// Pages légales
Route::view('/confidentialite', 'legal.privacy')->name('legal.privacy');
Route::view('/cgu', 'legal.cgu')->name('legal.cgu');
Route::view('/cookies', 'legal.cookies')->name('legal.cookies');

Route::view('/aide', 'support.help')->name('support.help');
Route::view('/documentation', 'support.docs')->name('support.docs');
Route::view('/contact', 'support.contact')->name('support.contact');
Route::view('/faq', 'support.faq')->name('support.faq');

Route::post('/contact', function (Illuminate\Http\Request $request) {
    return back()->with('success', 'Votre message a bien été envoyé au camp de base !');
})->name('support.contact.submit');

//page de base
Route::get('/', function () {
    return view('welcome');
})->name('welcome');


// Page d'accueil découverte courses
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/nearby', [App\Http\Controllers\HomeController::class, 'suggestNearby'])->name('home.nearby');

// Routes protégées pour ajouter des courses
Route::middleware(['auth'])->group(function () {
    Route::post('/home/{publicRace}/add', [App\Http\Controllers\HomeController::class, 'addToMyRaces'])->name('home.add-to-races');
    Route::post('/home/{publicRace}/interest', [App\Http\Controllers\HomeController::class, 'markInterested'])->name('home.mark-interested');
});

// Routes d'authentification
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Route de connexion auto (développement)
Route::get('/auto-login/{id}', function ($id) {
    $user = App\Models\User::find($id);
    if ($user) {
        Auth::login($user);
        return redirect()->route('dashboard');
    }
    return redirect('/')->with('error', 'Utilisateur introuvable');
})->name('auto-login');

// Routes protégées (authentification requise)
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/chart-data', [DashboardController::class, 'getChartData'])->name('dashboard.chart-data');

    // Gestion des courses
    Route::resource('races', RaceController::class);

    // Gestion des plans d'entraînement
    Route::resource('training-plans', TrainingPlanController::class)->only(['index', 'show', 'destroy']);
    Route::post('/training-plans/generate/{race}', [TrainingPlanController::class, 'generate'])->name('training-plans.generate');

    // Gestion des séances
    Route::resource('training-sessions', TrainingSessionController::class);
    Route::post('/training-sessions/{trainingSession}/complete', [TrainingSessionController::class, 'complete'])->name('training-sessions.complete');
    Route::post('/training-sessions/{trainingSession}/skip', [TrainingSessionController::class, 'skip'])->name('training-sessions.skip');
    Route::post('/training-sessions/{trainingSession}/reschedule', [TrainingSessionController::class, 'reschedule'])->name('training-sessions.reschedule');

    // Gestion des vlogs
    Route::resource('vlogs', VlogController::class);
    Route::post('/vlogs/{vlog}/like', [VlogController::class, 'like'])->name('vlogs.like');
    Route::get('/feed', [VlogController::class, 'feed'])->name('vlogs.feed');
    Route::post('/vlogs/{vlog}/comments', [VlogController::class, 'storeComment'])->name('vlogs.comments.store');

    // Intégration Strava
    Route::get('/strava/connect', [StravaController::class, 'redirect'])->name('strava.connect');
    Route::get('/strava/callback', [StravaController::class, 'callback'])->name('strava.callback');
    Route::post('/strava/sync', [StravaController::class, 'syncActivities'])->name('strava.sync');
    Route::delete('/strava/disconnect', [StravaController::class, 'disconnect'])->name('strava.disconnect');

    // Profil utilisateur
    Route::get('/profile/{user}', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile-edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');

    // Système de followers
    Route::post('/profile/{user}/follow', [ProfileController::class, 'follow'])->name('profile.follow');
    Route::delete('/profile/{user}/unfollow', [ProfileController::class, 'unfollow'])->name('profile.unfollow');
    Route::get('/profile/{user}/followers', [ProfileController::class, 'followers'])->name('profile.followers');
    Route::get('/profile/{user}/following', [ProfileController::class, 'following'])->name('profile.following');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unread-count');
});
