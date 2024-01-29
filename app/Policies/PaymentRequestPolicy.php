<?php

namespace App\Policies;

use App\Models\AdminUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class PaymentRequestPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(AdminUser $user)
    {
        return $user->isAdmin() || $user->isOperator();
    }

    /**
     * Determine whether the user can view the model info.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewDetail(AdminUser $user)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can store the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function store(AdminUser $user)
    {
        return $user->isAdmin() || $user->isCallbackManager() || $user->isAccountant();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(AdminUser $user)
    {
        return $user->isAdmin() || $user->isCallbackManager() || $user->isAccountant();
    }

    /**
     * Determine whether the user can download the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function download(AdminUser $user)
    {
        return $user->isAdmin() || $user->isCallbackManager() || $user->isAccountant();
    }
}
