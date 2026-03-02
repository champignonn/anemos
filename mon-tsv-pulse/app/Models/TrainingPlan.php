<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrainingPlan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'race_id', 'name', 'description', 'start_date', 'end_date',
        'total_weeks', 'base_phase_weeks', 'build_phase_weeks', 'peak_phase_weeks',
        'taper_weeks', 'weekly_distance_min', 'weekly_distance_max',
        'weekly_elevation_min', 'weekly_elevation_max', 'sessions_per_week',
        'long_run_frequency', 'intensity_ratio', 'status', 'is_generated',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'intensity_ratio' => 'decimal:2',
            'is_generated' => 'boolean',
        ];
    }

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function race(): BelongsTo { return $this->belongsTo(Race::class); }
    public function trainingSessions(): HasMany { return $this->hasMany(TrainingSession::class); }

    public function getCurrentWeekNumber(): int
    {
        if (now()->lt($this->start_date)) return 0;
        if (now()->gt($this->end_date)) return $this->total_weeks + 1;
        return $this->start_date->diffInWeeks(now()) + 1;
    }

    public function getProgressPercentage(): float
    {
        $totalDays = $this->start_date->diffInDays($this->end_date);
        if ($totalDays == 0) return 0;
        $elapsedDays = $this->start_date->diffInDays(now());
        return round(min(($elapsedDays / $totalDays) * 100, 100), 2);
    }

    public function getCurrentPhase(): string
    {
        $currentWeek = $this->getCurrentWeekNumber();
        if ($currentWeek <= $this->base_phase_weeks) return 'Base';
        if ($currentWeek <= $this->base_phase_weeks + $this->build_phase_weeks) return 'Développement';
        if ($currentWeek <= $this->total_weeks - $this->taper_weeks) return 'Pic';
        return 'Affûtage';
    }
}
