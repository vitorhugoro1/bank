<?php

namespace App\Domains\Account\Policies;

use App\Domains\Users\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Domains\Account\Models\Account;

class AccountPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Domains\Users\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Domains\Users\Models\User  $user
     * @param  \App\Account  $account
     * @return mixed
     */
    public function view(User $user, Account $account)
    {
        return $account->user->id === $user->id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Domains\Users\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Domains\Users\Models\User  $user
     * @param  \App\Account  $account
     * @return mixed
     */
    public function update(User $user, Account $account)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Domains\Users\Models\User  $user
     * @param  \App\Account  $account
     * @return mixed
     */
    public function delete(User $user, Account $account)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Domains\Users\Models\User  $user
     * @param  \App\Account  $account
     * @return mixed
     */
    public function restore(User $user, Account $account)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Domains\Users\Models\User  $user
     * @param  \App\Account  $account
     * @return mixed
     */
    public function forceDelete(User $user, Account $account)
    {
        //
    }
}
