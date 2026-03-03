<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Notifications\DatabaseNotification;

class NotificationPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {

    }

    public function update(User $user, DatabaseNotification $databaseNotification): bool
    {
        return $user->id === $databaseNotification->notifiable_id;
    }
}
