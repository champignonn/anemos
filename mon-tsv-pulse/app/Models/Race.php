<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Race extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'name', 'description', 'race_date', 'type', 'distance',
        'elevation_gain', 'elevation_loss', 'location', 'target_time', 'priority', 'status',
    ];

    protected function casts(): array
    {
        return [
            'race_date' => 'date',
            'distance' => 'decimal:2',
            'target_time' => 'datetime:H:i',
        ];
    }

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function trainingPlans(): HasMany { return $this->hasMany(TrainingPlan::class); }

    public function getDaysUntilRaceAttribute(): int
    {
        return max(0, $this->race_date->diffInDays(now(), false));
    }

    public function getDifficultyCoefficient(): float
    {
        // Coefficient de difficulté = dénivelé / distance
        if ($this->distance == 0) return 0;
        return round($this->elevation_gain / $this->distance, 1);
    }

    public function getCategoryAttribute(): string
    {
        // Catégorie basée sur la distance
        if ($this->distance < 21) return 'Trail Court';
        if ($this->distance < 42) return 'Trail';
        if ($this->distance < 80) return 'Ultra-Trail';
        return 'Ultra Long';
    }

    public function getEquivalentFlatDistanceAttribute(): float
    {
        // Formule : 1m de D+ = 10m de plat
        $extraDistance = $this->elevation_gain / 100; // en km
        return round($this->distance + $extraDistance, 1);
    }

    public function getEstimatedTimeAttribute(): ?string
    {
        // Estimation basée sur 10km/h en plat et ajustement pour le D+
        if (!$this->distance) return null;

        $flatTime = $this->distance / 10; // heures pour la distance plate
        $elevationTime = $this->elevation_gain / 400; // heures pour le D+ (400m/h)

        $totalHours = $flatTime + $elevationTime;
        $hours = floor($totalHours);
        $minutes = ($totalHours - $hours) * 60;

        return sprintf('%02d:%02d', $hours, $minutes);
    }
}
