<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name', 'email', 'password', 'vma', 'endurance_pace', 'threshold_pace',
        'weight', 'resting_heart_rate', 'max_heart_rate', 'bio', 'avatar_path',
        'experience_level', 'strava_id', 'strava_access_token', 'strava_refresh_token',
        'strava_token_expires_at',
    ];

    protected $hidden = ['password', 'remember_token', 'strava_access_token', 'strava_refresh_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'vma' => 'decimal:2',
            'endurance_pace' => 'decimal:2',
            'threshold_pace' => 'decimal:2',
            'strava_token_expires_at' => 'datetime',
        ];
    }

    public function races(): HasMany { return $this->hasMany(Race::class); }
    public function trainingPlans(): HasMany { return $this->hasMany(TrainingPlan::class); }
    public function trainingSessions(): HasMany { return $this->hasMany(TrainingSession::class); }
    public function vlogs(): HasMany { return $this->hasMany(Vlog::class); }

    public function getActiveTrainingPlan(): ?TrainingPlan
    {
        return $this->trainingPlans()->where('status', 'actif')
            ->where('start_date', '<=', now())->where('end_date', '>=', now())->first();
    }

    public function getNextRace(): ?Race
    {
        return $this->races()->where('race_date', '>', now())
            ->where('status', '!=', 'abandonnée')->orderBy('race_date')->first();
    }

    public function hasStravaConnected(): bool
    {
        return !empty($this->strava_id) && !empty($this->strava_access_token)
            && $this->strava_token_expires_at > now();
    }

    public function getHeartRateZones(): array
    {
        if (!$this->max_heart_rate || !$this->resting_heart_rate) return [];
        $hrr = $this->max_heart_rate - $this->resting_heart_rate;
        return [
            'Z1' => ['min' => round($this->resting_heart_rate + ($hrr * 0.50)), 'max' => round($this->resting_heart_rate + ($hrr * 0.60))],
            'Z2' => ['min' => round($this->resting_heart_rate + ($hrr * 0.60)), 'max' => round($this->resting_heart_rate + ($hrr * 0.70))],
            'Z3' => ['min' => round($this->resting_heart_rate + ($hrr * 0.70)), 'max' => round($this->resting_heart_rate + ($hrr * 0.80))],
            'Z4' => ['min' => round($this->resting_heart_rate + ($hrr * 0.80)), 'max' => round($this->resting_heart_rate + ($hrr * 0.90))],
            'Z5' => ['min' => round($this->resting_heart_rate + ($hrr * 0.90)), 'max' => $this->max_heart_rate],
        ];
    }

    public function followers(): HasMany
    {
        return $this->hasMany(Follower::class, 'following_id');
    }

    public function following(): HasMany
    {
        return $this->hasMany(Follower::class, 'follower_id');
    }

    public function isFollowing(int $userId): bool
    {
        return $this->following()->where('following_id', $userId)->exists();
    }

    public function followersCount(): int
    {
        return $this->followers()->count();
    }

    public function followingCount(): int
    {
        return $this->following()->count();
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    public function unreadNotificationsCount(): int
    {
        return $this->notifications()->where('read', false)->count();
    }

    public function interestedInRaces(): BelongsToMany
    {
        return $this->belongsToMany(PublicRace::class, 'user_public_races')
            ->withPivot('status')
            ->withTimestamps();
    }
}
