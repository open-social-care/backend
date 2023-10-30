<?php

namespace Database\Factories;

use App\Models\FormTemplate;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class OrganizationFormTemplateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $organization = Organization::factory()->createOneQuietly();
        $formTemplate = FormTemplate::factory()->createOneQuietly();

        return [
            'organization_id' => $organization->id,
            'form_template_id' => $formTemplate->id,
        ];
    }
}
