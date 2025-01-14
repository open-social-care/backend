import { ActionFlashes } from "@/action-flash/ActionFlashes";
import { Paper } from "@/components/containers";
import OrganizationMembers from "@/components/pages/OrganizationMembers";
import { Heading } from "@/components/ui";
import { Roles } from "@/enums/Roles";
import { t } from "@/lang";
import { Organization, User } from "@/schemas";
import { fetchNonMembersAction, fetchOrganizationAction } from "./_actions";
import FormAddMembers from "./_form-add-members";

interface PageProps {
  params: {
    organizationId: number;
  };
  searchParams: {
    managersPage: number;
    socialAssistantsPage: number;
  };
}

export default async function page({ params, searchParams }: PageProps) {
  const { data: organizationData } = await fetchOrganizationAction(params.organizationId);

  const organization = Organization.parse(organizationData);

  const { data: nonManagersData } = await fetchNonMembersAction(
    params.organizationId,
    Roles.MANAGER,
  );

  const nonManagers = User.array().parse(nonManagersData);

  const { data: nonSocialAssistantsData } = await fetchNonMembersAction(
    params.organizationId,
    Roles.SOCIAL_ASSISTANT,
  );

  const nonSocialAssistants = User.array().parse(nonSocialAssistantsData);

  return (
    <>
      <ActionFlashes />

      <Heading h1>{organization.name}</Heading>

      <Paper className="mt-4">
        <Heading h2>{t("roles.managers")}</Heading>

        <FormAddMembers
          users={nonManagers}
          organizationId={params.organizationId}
          role={Roles.MANAGER}
        />

        <OrganizationMembers
          profile={Roles.ADMIN}
          //
          organizationId={params.organizationId}
          role={Roles.MANAGER}
          //
          pageQueryName="managersPage"
          page={searchParams.managersPage}
        />
      </Paper>

      <Paper className="mt-4">
        <Heading h2>{t("roles.social_assistants")}</Heading>

        <FormAddMembers
          users={nonSocialAssistants}
          organizationId={params.organizationId}
          role={Roles.SOCIAL_ASSISTANT}
        />

        <OrganizationMembers
          profile={Roles.ADMIN}
          //
          organizationId={params.organizationId}
          role={Roles.SOCIAL_ASSISTANT}
          //
          pageQueryName="socialAssistantsPage"
          page={searchParams.socialAssistantsPage}
        />
      </Paper>
    </>
  );
}
