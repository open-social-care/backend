import { Paper } from "@/components/containers";
import OrganizationMembers from "@/components/pages/OrganizationMembers";
import { Heading } from "@/components/ui";
import { Roles } from "@/enums/Roles";
import { t } from "@/lang";
import { Organization } from "@/schemas";
import { fetchOrganizationAction } from "./_actions";

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
  const { data } = await fetchOrganizationAction(params.organizationId);

  const organization = Organization.parse(data);

  return (
    <>
      <Heading h1>{organization.name}</Heading>

      <Paper className="mt-4">
        <Heading h2>{t("roles.managers")}</Heading>

        <OrganizationMembers
          profile={Roles.SOCIAL_ASSISTANT}
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

        <OrganizationMembers
          profile={Roles.SOCIAL_ASSISTANT}
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
