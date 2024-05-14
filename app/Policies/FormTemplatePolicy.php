<?php

namespace App\Policies;

use App\Models\FormTemplate;
use App\Models\Organization;
use App\Models\User;

class FormTemplatePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdminSystem();
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewForOrganization(User $user, Organization $organization): bool
    {
        return $user->isManagerOf($organization);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, FormTemplate $formTemplate): bool
    {
        return $user->isAdminSystem() || $this->userAndFormTemplatesHasCommonOrganization($user, $formTemplate);
    }

    /**
     * Determine whether the user can create models.
     */
    public function createQuestionsForFormTemplate(User $user, FormTemplate $formTemplate): bool
    {
        return $user->isAdminSystem() || $this->userAndFormTemplatesHasCommonOrganization($user, $formTemplate);
    }

    /**
     * Determine whether the user can create models.
     */
    public function createForOrganization(User $user, Organization $organization): bool
    {
        return $user->isAdminSystem() || $user->isManagerOf($organization);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, FormTemplate $formTemplate): bool
    {
        return $user->isAdminSystem() || $this->userAndFormTemplatesHasCommonOrganization($user, $formTemplate);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, FormTemplate $formTemplate): bool
    {
        return $user->isAdminSystem() || $this->userAndFormTemplatesHasCommonOrganization($user, $formTemplate);
    }

    private function userAndFormTemplatesHasCommonOrganization(User $user, FormTemplate $formTemplate): bool
    {
        $userOrganizations = $user->organizations->pluck('id')->toArray();
        $formTemplateOrganizations = $formTemplate->organizations->pluck('id')->toArray();

        $hasCommonOrganization = ! empty(array_intersect($userOrganizations, $formTemplateOrganizations));

        return $hasCommonOrganization;
    }
}
