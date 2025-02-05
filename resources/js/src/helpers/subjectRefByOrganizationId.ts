import auth from "@/auth";
import { t } from "@/lang";

export default async function subjectRefByOrganizationId(organizationId: number) {
  const orgs = (await auth()).user.organizations;
  const organization = orgs?.find((org) => org.id == organizationId);

  return organization?.subject_ref || t("labels.subjects");
}
