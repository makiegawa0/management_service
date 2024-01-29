<?php

namespace App\Policies;

use App\Models\AdminUser;
use App\Models\NewCallbackLog;
use Illuminate\Auth\Access\HandlesAuthorization;

class ApiLogPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\AdminUser  $user
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(AdminUser $user)
    {
        return $user->isAdmin() || $user->isManager() || $user->isCallbackManager() || $user->isAccountant() || $user->isViewer();
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\AdminUser  $user
     * @param  \App\Models\NewCallbackLog  $newCallbackLog
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(AdminUser $user)
    {
        return $user->isAdmin() || $user->isManager() || $user->isAccountant();
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\AdminUser  $user
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(AdminUser $user)
    {
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\AdminUser  $user
     * @param  \App\Models\NewCallbackLog  $newCallbackLog
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(AdminUser $user, NewCallbackLog $newCallbackLog)
    {
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\AdminUser  $user
     * @param  \App\Models\NewCallbackLog  $newCallbackLog
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(AdminUser $user, NewCallbackLog $newCallbackLog)
    {
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\AdminUser  $user
     * @param  \App\Models\NewCallbackLog  $newCallbackLog
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(AdminUser $user, NewCallbackLog $newCallbackLog)
    {
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\AdminUser  $user
     * @param  \App\Models\NewCallbackLog  $newCallbackLog
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(AdminUser $user, NewCallbackLog $newCallbackLog)
    {
    }
}
