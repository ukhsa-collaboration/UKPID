<?php

namespace App\Policies;

use App\Models\SourceOfEnquiry;
use App\Models\User;

class SourceOfEnquiryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('source_of_enquiry.read');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SourceOfEnquiry $sourceOfEnquiry): bool
    {
        return $user->can('source_of_enquiry.read');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('source_of_enquiry.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SourceOfEnquiry $sourceOfEnquiry): bool
    {
        return $user->can('source_of_enquiry.update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SourceOfEnquiry $sourceOfEnquiry): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, SourceOfEnquiry $sourceOfEnquiry): bool
    {
        return $user->can('source_of_enquiry.update');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, SourceOfEnquiry $sourceOfEnquiry): bool
    {
        return false;
    }
}
