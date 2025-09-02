<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Admins can view all users, Agencies can view brands they manage
        return $user->isAdmin() || $user->isAgency();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        // Admins can view all users
        if ($user->isAdmin()) {
            return true;
        }
        
        // Users can view their own profile
        return $user->id === $model->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Admins can create any user type, Agencies can only create Brands
        return $user->isAdmin() || $user->isAgency();
    }

    /**
     * Determine whether the user can create a specific role.
     */
    public function createRole(User $user, string $role): bool
    {
        // Admins can create any role
        if ($user->isAdmin()) {
            return true;
        }
        
        // Agencies can only create Brand users
        if ($user->isAgency()) {
            return $role === 'brand';
        }
        
        // Brands cannot create users
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        // Admins can update all users
        if ($user->isAdmin()) {
            return true;
        }
        
        // Users can update their own profile (but not role changes)
        return $user->id === $model->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        // Only admins can delete users
        if (!$user->isAdmin()) {
            return false;
        }
        
        // Prevent self-deletion
        return $user->id !== $model->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        return $this->delete($user, $model);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        // Only admins can permanently delete users
        if (!$user->isAdmin()) {
            return false;
        }
        
        // Prevent self-deletion
        return $user->id !== $model->id;
    }
}
