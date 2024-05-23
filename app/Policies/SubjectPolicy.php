<?php

namespace App\Policies;

use App\Models\Organization;
use App\Models\Subject;
use App\Models\User;

class SubjectPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function viewByOrganization(User $currentUser, Organization $organization): bool
    {
        return $currentUser->isAdminSystem() || $currentUser->isSocialAssistantOf($organization);
    }

    /**
     * Determine whether the user can create models.
     */
    public function createByOrganization(User $currentUser, Organization $organization): bool
    {
        return $currentUser->isAdminSystem() || $currentUser->isSocialAssistantOf($organization);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $currentUser, Subject $subject): bool
    {
        $organization = $subject->organization;

        return $currentUser->isAdminSystem() || $currentUser->isSocialAssistantOf($organization);
    }
}
