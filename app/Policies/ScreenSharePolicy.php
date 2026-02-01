<?php

namespace App\Policies;

use App\Models\ScreenShare;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ScreenSharePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('screen-shares.index');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ScreenShare $screenShare): bool
    {
        // Teacher can view their own screen shares
        if ($screenShare->teacher_id === $user->id) {
            return $user->can('screen-shares.show');
        }

        // Students who are participants can view
        if ($screenShare->participants()->where('student_id', $user->id)->exists()) {
            return $user->can('screen-shares.view');
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('screen-shares.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ScreenShare $screenShare): bool
    {
        return $user->id === $screenShare->teacher_id && $user->can('screen-shares.update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ScreenShare $screenShare): bool
    {
        return $user->id === $screenShare->teacher_id && $user->can('screen-shares.delete');
    }

    /**
     * Determine whether the user can end the screen sharing session.
     */
    public function end(User $user, ScreenShare $screenShare): bool
    {
        return $user->id === $screenShare->teacher_id && $user->can('screen-shares.end');
    }

    /**
     * Determine whether the user can broadcast screen data.
     */
    public function broadcast(User $user, ScreenShare $screenShare): bool
    {
        return $user->id === $screenShare->teacher_id && $user->can('screen-shares.broadcast');
    }

    /**
     * Determine whether the user can join a screen sharing session.
     */
    public function join(User $user): bool
    {
        return $user->can('screen-shares.join');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ScreenShare $screenShare): bool
    {
        return $user->id === $screenShare->teacher_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ScreenShare $screenShare): bool
    {
        return $user->id === $screenShare->teacher_id;
    }
}
