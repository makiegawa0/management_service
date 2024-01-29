<?php

namespace App\Policies;

use App\Models\AdminUser;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the admin user can view any models.
     *π
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(AdminUser $adminUser)
    {
        return $adminUser->isAdmin() || $adminUser->isOperator() || $adminUser->isCallbackManager() || $adminUser->isManager();
    }

    /**
     * Determine whether the admin user can view the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(AdminUser $adminUser, User $user)
    {
    }

    /**
     * Determine whether the user can create models.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(AdminUser $adminUser)
    {
        return $adminUser->isAdmin();
    }

    /**
     * Determine whether the user can export models.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function export(AdminUser $adminUser)
    {
        return $adminUser->isAdmin() || $adminUser->isCallbackManager() || $adminUser->isManager();
    }

    /**
     * Determine whether the admin user can update the model.
     *
     * @param  \App\Models\User  $adminUser
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(AdminUser $adminUser)
    {
        return $adminUser->isAdmin();
    }

    /**
     * Determine whether the admin user can delete the model.
     *
     * @param  \App\Models\User  $adminUser
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(AdminUser $adminUser)
    {
        return $adminUser->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(AdminUser $adminUser, User $user)
    {
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(AdminUser $adminUser, User $user)
    {
    }

    /**
     * Determine whether the user can view risk levels.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewRiskLevels(AdminUser $adminUser)
    {
        return $adminUser->isAdmin() || $adminUser->isCallbackManager() || $adminUser->isManager();
    }

    /**
     * Add a note to the adminUser.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function addNote(AdminUser $adminUser)
    {
        return $adminUser->isAdmin() || $adminUser->isCallbackManager();
    }
}
