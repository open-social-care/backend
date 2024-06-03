<?php

namespace App\Policies;

use App\Models\FormAnswer;
use App\Models\Subject;
use App\Models\User;

class FormAnswerPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, FormAnswer $formAnswer): bool
    {
        return $user->isAdminSystem() || $this->userAndFormAnswerHasCommonOrganization($user, $formAnswer);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function viewBySubject(User $user, Subject $subject): bool
    {
        $subjectOrganization = $subject->organization->id;

        return $user->isAdminSystem() || $user->hasOrganization($subjectOrganization);
    }

    /**
     * Determine whether the user can create models.
     */
    public function createForSubject(User $user, Subject $subject): bool
    {
        $subjectOrganization = $subject->organization->id;

        return $user->isAdminSystem() || $user->hasOrganization($subjectOrganization);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, FormAnswer $formAnswer): bool
    {
        return $user->isAdminSystem() || $this->userAndFormAnswerHasCommonOrganization($user, $formAnswer);
    }

    /**
     * Verify user has common organization
     */
    private function userAndFormAnswerHasCommonOrganization(User $user, FormAnswer $formAnswer): bool
    {
        $formAnswer->load('formTemplate');

        $userOrganizations = $user->organizations->pluck('id')->toArray();
        $formTemplate = $formAnswer->formTemplate;
        $formTemplateOrganizations = $formTemplate->organizations->pluck('id')->toArray();

        $hasCommonOrganization = ! empty(array_intersect($userOrganizations, $formTemplateOrganizations));

        return $hasCommonOrganization;
    }
}
