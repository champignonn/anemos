<?php

namespace App\Helpers;

use App\Models\Notification;
use App\Models\User;

class NotificationHelper
{
    public static function newFollower(User $follower, User $followed): void
    {
        Notification::create([
            'user_id' => $followed->id,
            'type' => 'new_follower',
            'title' => 'Nouveau follower',
            'message' => "{$follower->name} a commencé à vous suivre",
            'icon' => '👥',
            'link' => route('profile.show', $follower),
        ]);
    }

    public static function vlogLiked(User $liker, $vlog): void
    {
        if ($liker->id === $vlog->user_id) return;

        Notification::create([
            'user_id' => $vlog->user_id,
            'type' => 'vlog_like',
            'title' => 'Vlog liké',
            'message' => "{$liker->name} a aimé votre vlog \"{$vlog->title}\"",
            'icon' => '❤️',
            'link' => route('vlogs.show', $vlog),
        ]);
    }

    public static function sessionReminder(User $user, $session): void
    {
        Notification::create([
            'user_id' => $user->id,
            'type' => 'session_reminder',
            'title' => 'Séance programmée demain',
            'message' => "N'oubliez pas : {$session->title}",
            'icon' => '🏃',
            'link' => route('training-sessions.show', $session),
        ]);
    }

    public static function raceReminder(User $user, $race, int $daysLeft): void
    {
        Notification::create([
            'user_id' => $user->id,
            'type' => 'race_reminder',
            'title' => "Course dans {$daysLeft} jours !",
            'message' => "{$race->name} approche !",
            'icon' => '🏁',
            'link' => route('races.show', $race),
        ]);
    }
}
