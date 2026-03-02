<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrainingSession extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'training_sessions';

    protected $fillable = [
        'training_plan_id', 'user_id', 'title', 'description', 'scheduled_date',
        'scheduled_time', 'type', 'duration_minutes', 'planned_distance',
        'planned_elevation', 'intensity_zone', 'target_pace_min', 'target_pace_max',
        'target_heart_rate', 'workout_structure', 'status', 'completed_at',
        'actual_duration_minutes', 'actual_distance', 'actual_elevation',
        'actual_pace', 'average_heart_rate', 'max_heart_rate', 'rpe',
        'fatigue_level', 'notes', 'weather_conditions', 'strava_activity_id',
        'imported_from_strava',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_date' => 'date',
            'scheduled_time' => 'datetime:H:i',
            'completed_at' => 'datetime',
            'planned_distance' => 'decimal:2',
            'actual_distance' => 'decimal:2',
            'target_pace_min' => 'decimal:2',
            'target_pace_max' => 'decimal:2',
            'actual_pace' => 'decimal:2',
            'workout_structure' => 'array',
            'weather_conditions' => 'array',
            'imported_from_strava' => 'boolean',
        ];
    }

    public function trainingPlan(): BelongsTo { return $this->belongsTo(TrainingPlan::class); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function vlog(): HasOne { return $this->hasOne(Vlog::class); }

    public function getIntensityColorAttribute(): string
    {
        return match($this->type) {
            'VMA', 'Seuil' => '#AA333C',
            'Tempo', 'Fartlek', 'Côtes' => '#A6CEE2',
            'Endurance Fondamentale', 'Sortie Longue', 'Trail Technique' => '#9AAB64',
            'Récupération', 'Repos' => '#F3E6E0',
            default => '#3C5227'
        };
    }
}
