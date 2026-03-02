<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vlog extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'training_session_id', 'user_id', 'title', 'content', 'video_url',
        'video_thumbnail', 'photo_paths', 'location', 'tags', 'mood',
        'likes_count', 'views_count', 'visibility', 'is_featured', 'published_at',
    ];

    protected function casts(): array
    {
        return [
            'photo_paths' => 'array',
            'tags' => 'array',
            'is_featured' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    // Relations de base
    public function trainingSession(): BelongsTo { return $this->belongsTo(TrainingSession::class); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }

    /**
     * Les utilisateurs qui ont liké ce vlog (Like unique).
     */
    public function likes(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'vlog_likes')->withTimestamps();
    }

    /**
     * Les utilisateurs qui ont vu ce vlog (Vue unique par profil).
     */
    public function views(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'vlog_views')->withTimestamps();
    }

    /**
     * Les commentaires associés au vlog.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)->latest();
    }
}
