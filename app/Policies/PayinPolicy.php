<?php

namespace App\Policies;

use App\Models\AdminUser;
use App\Models\Payin;
use Illuminate\Auth\Access\HandlesAuthorization;

class PayinPolicy
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
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\AdminUser  $user
     * @param  \App\Models\Payin  $payin
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(AdminUser $user, Payin $payin)
    {
        return $user->isAdmin();
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
     * @param  \App\Models\Payin  $payin
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(AdminUser $user, Payin $payin)
    {
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\AdminUser  $user
     * @param  \App\Models\Payin  $payin
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(AdminUser $user, Payin $payin)
    {
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\AdminUser  $user
     * @param  \App\Models\Payin  $payin
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(AdminUser $user, Payin $payin)
    {
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\AdminUser  $user
     * @param  \App\Models\Payin  $payin
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(AdminUser $user, Payin $payin)
    {
    }

    /**
     * Determine whether the user can import the model.
     *
     * @param  \App\Models\AdminUser  $user
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function import(AdminUser $user)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can export the model.
     *
     * @param  \App\Models\AdminUser  $user
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function export(AdminUser $user)
    {
        return $user->isAdmin() || $user->isCallbackManager() || $user->isAccountant();
    }

    /**
     * Determine whether the user can unmatch the model with the payment request.
     *
     * @param  \App\Models\AdminUser  $user
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function unmatchPaymentRequest(AdminUser $user)
    {
        return $user->isAdmin() || $user->isOperator() || $user->isCallbackManager();
    }

    /**
     * Determine whether the user can match the model with the payment request.
     *
     * @param  \App\Models\AdminUser  $user
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function matchPaymentRequest(AdminUser $user)
    {
        return $user->isAdmin() || $user->isOperator() || $user->isCallbackManager();
    }
}
