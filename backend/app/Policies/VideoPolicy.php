<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Video;
use Illuminate\Auth\Access\Response;

class VideoPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // All authenticated users can view videos (filtered by ownership in controller)
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Video $video): bool
    {
        // Admins can view all videos
        if ($user->isAdmin()) {
            return true;
        }
        
        // Agencies can view videos from campaigns they manage
        if ($user->isAgency()) {
            // For now, agencies can view all videos
            // You might want to implement a more specific relationship
            return true;
        }
        
        // Users can only view videos from their own campaigns
        return $user->id === $video->campaign->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // All authenticated users can create videos
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Video $video): bool
    {
        // Admins can update all videos
        if ($user->isAdmin()) {
            return true;
        }
        
        // Agencies can update videos from campaigns they manage
        if ($user->isAgency()) {
            // For now, agencies can update all videos
            // You might want to implement a more specific relationship
            return true;
        }
        
        // Users can only update videos from their own campaigns
        return $user->id === $video->campaign->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Video $video): bool
    {
        // Admins can delete all videos
        if ($user->isAdmin()) {
            return true;
        }
        
        // Users can only delete videos from their own campaigns
        return $user->id === $video->campaign->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Video $video): bool
    {
        return $this->delete($user, $video);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Video $video): bool
    {
        // Only admins can permanently delete videos
        return $user->isAdmin();
    }
}
