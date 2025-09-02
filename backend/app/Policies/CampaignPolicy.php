<?php

namespace App\Policies;

use App\Models\Campaign;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CampaignPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // All authenticated users can view campaigns (filtered by ownership in controller)
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Campaign $campaign): bool
    {
        // Admins can view all campaigns
        if ($user->isAdmin()) {
            return true;
        }
        
        // Agencies can view campaigns from their managed brands
        if ($user->isAgency()) {
            // For now, agencies can view all campaigns
            // You might want to implement a more specific relationship
            return true;
        }
        
        // Users can only view their own campaigns
        return $user->id === $campaign->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // All authenticated users can create campaigns
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Campaign $campaign): bool
    {
        // Admins can update all campaigns
        if ($user->isAdmin()) {
            return true;
        }
        
        // Agencies can update campaigns from their managed brands
        if ($user->isAgency()) {
            // For now, agencies can update all campaigns
            // You might want to implement a more specific relationship
            return true;
        }
        
        // Users can only update their own campaigns
        return $user->id === $campaign->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Campaign $campaign): bool
    {
        // Admins can delete all campaigns
        if ($user->isAdmin()) {
            return true;
        }
        
        // Users can only delete their own campaigns
        return $user->id === $campaign->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Campaign $campaign): bool
    {
        return $this->delete($user, $campaign);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Campaign $campaign): bool
    {
        // Only admins can permanently delete campaigns
        return $user->isAdmin();
    }
}
