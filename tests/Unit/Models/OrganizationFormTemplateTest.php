<?php

namespace Tests\Unit\Models;

use App\Models\FormTemplate;
use App\Models\Organization;
use App\Models\OrganizationFormTemplate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrganizationFormTemplateTest extends TestCase
{
    use RefreshDatabase;

    public function testOrganizationFormTemplateBelongsToOrganization()
    {
        $organization = Organization::factory()->createOneQuietly();
        $organizationFormTemplate = OrganizationFormTemplate::factory()->for($organization)->createOneQuietly();

        $this->assertEquals($organization->id, $organizationFormTemplate->organization_id);
    }

    public function testOrganizationFormTemplateBelongsToFormTemplate()
    {
        $formTemplate = FormTemplate::factory()->createOneQuietly();
        $organizationFormTemplate = OrganizationFormTemplate::factory()->for($formTemplate)->createOneQuietly();

        $this->assertEquals($formTemplate->id, $organizationFormTemplate->form_template_id);
    }
}
