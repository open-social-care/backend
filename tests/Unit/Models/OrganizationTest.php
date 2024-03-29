<?php

namespace Tests\Unit\Models;

use App\Models\Address;
use App\Models\Organization;
use App\Models\OrganizationFormTemplate;
use App\Models\OrganizationUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrganizationTest extends TestCase
{
    use RefreshDatabase;

    public function testOrganizationMorphManyAddresses()
    {
        $organization = Organization::factory()->createOneQuietly();

        $address1 = Address::factory()->createOneQuietly([
            'model_id' => $organization->id,
            'model_type' => Organization::class,
        ]);

        $address2 = Address::factory()->createOneQuietly([
            'model_id' => $organization->id,
            'model_type' => Organization::class,
        ]);

        $this->assertTrue($organization->addresses->contains($address1));
        $this->assertTrue($organization->addresses->contains($address2));
    }

    public function testOrganizationHasManyMultipleOrganizationUsers()
    {
        $organization = Organization::factory()->createOneQuietly();
        $organizationUser1 = OrganizationUser::factory()->for($organization)->createOneQuietly();
        $organizationUser2 = OrganizationUser::factory()->for($organization)->createOneQuietly();

        $this->assertTrue($organization->users()->where('user_id', $organizationUser1->user_id)->exists());
        $this->assertTrue($organization->users()->where('user_id', $organizationUser2->user_id)->exists());
    }

    public function testOrganizationHasManyMultipleOrganizationFormTemplates()
    {
        $organization = Organization::factory()->createOneQuietly();
        $organizationFormTemplate1 = OrganizationFormTemplate::factory()->for($organization)->createOneQuietly();
        $organizationFormTemplate2 = OrganizationFormTemplate::factory()->for($organization)->createOneQuietly();

        $this->assertTrue($organization->organizationFormTemplates->contains($organizationFormTemplate1));
        $this->assertTrue($organization->organizationFormTemplates->contains($organizationFormTemplate2));
    }
}
