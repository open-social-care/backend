import { Paper } from "@/components/containers";
import { OrganizationUpdateForm } from "@/components/pages/OrganizationUpdateForm";
import { Heading } from "@/components/ui";
import { Roles } from "@/enums/Roles";
import { t } from "@/lang";
import { Organization } from "@/schemas";
import { fetchOrganizationAction } from "../_actions";

interface PageProps {
  params: {
    organizationId: number;
  };
}

export default async function page({ params }: PageProps) {
  const { data } = await fetchOrganizationAction(params.organizationId);

  const organization = Organization.parse(data);

  return (
    <>
      <Heading h1>{t("page_titles.edit_organization")}</Heading>

      <Paper className="mt-4">
        <OrganizationUpdateForm
          profile={Roles.MANAGER}
          organization={organization}
        />
      </Paper>
    </>
  );
}
