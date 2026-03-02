<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PublicRace extends Model
{
    protected $fillable = [
        'name', 'description', 'race_date', 'type', 'distance', 'elevation_gain',
        'location', 'city', 'country', 'latitude', 'longitude', 'organizer',
        'website_url', 'registration_url', 'price', 'max_participants',
        'difficulty_level', 'features', 'image_url', 'is_verified'
    ];

    protected function casts(): array
    {
        return [
            'race_date' => 'date',
            'distance' => 'decimal:2',
            'price' => 'decimal:2',
            'features' => 'array',
            'is_verified' => 'boolean',
        ];
    }

    public function interestedUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_public_races')
            ->withPivot('status')
            ->withTimestamps();
    }

    public function getDaysUntilRaceAttribute(): int
    {
        return max(0, now()->diffInDays($this->race_date, false));
    }

    public function getDifficultyBadgeColorAttribute(): string
    {
        return match($this->difficulty_level) {
            'débutant' => 'green',
            'intermédiaire' => 'cyan',
            'expert' => 'violet',
            default => 'gray'
        };
    }

    public function getDistanceFromUser($userLat, $userLng): float
    {
        if (!$this->latitude || !$this->longitude) {
            return 0;
        }

        // Formule de Haversine pour calculer la distance
        $earthRadius = 6371; // km

        $latFrom = deg2rad($userLat);
        $lonFrom = deg2rad($userLng);
        $latTo = deg2rad($this->latitude);
        $lonTo = deg2rad($this->longitude);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
                cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));

        return round($angle * $earthRadius, 1);
    }
}
