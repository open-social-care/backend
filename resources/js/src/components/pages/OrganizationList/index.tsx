import { HBox, Paper } from "@/components/containers";
import { AiOutlineEdit, AiOutlineSetting, AiOutlineUsergroupAdd } from "react-icons/ai";

import { t } from "@/lang";
import { Organization, Role } from "@/schemas";

import { Text } from "@/components/ui";
import CardAction from "@/components/ui/CardAction";
import Pagination from "@/components/ui/Pagination";
import { Roles } from "@/enums/Roles";
import { fetchOrganizationsAction } from "./_actions";

interface OrganizationListProps {
  profile: Role;
  search?: string;
  page?: number;
}

export default async function OrganizationList({ profile, search, page }: OrganizationListProps) {
  const { data, pagination } = await fetchOrganizationsAction(profile, search, page);

  const organizations = Organization.array().parse(data);

  if (organizations.length == 0) {
    return <Text>{t("informations.organization_not_found")}</Text>;
  }

  return (
    <>
      {organizations.map((organization) => (
        <Paper
          className="mt-1"
          key={organization.id}
        >
          <Text className="font-semibold">{organization.name}</Text>

          {organization.document_type && (
            <Text className="text-sm">
              {t(`document_types.${organization.document_type}`)}: {organization.document}
            </Text>
          )}

          {organization.phone && (
            <Text className="text-sm">
              {t("labels.phone")}: {organization.phone}
            </Text>
          )}

          <HBox className="mt-4 justify-end gap-4">
            {(profile == Roles.ADMIN || profile == Roles.MANAGER) && (
              <CardAction
                title={t("general_actions.manage")}
                href={`/${profile}/organizations/${organization.id}`}
                icon={<AiOutlineSetting />}
              />
            )}

            {profile == Roles.SOCIAL_ASSISTANT && (
              <CardAction
                title={organization.subject_ref || t("labels.subjects")}
                href={`/${profile}/organizations/${organization.id}/subjects`}
                icon={<AiOutlineUsergroupAdd />}
              />
            )}

            {profile !== Roles.SOCIAL_ASSISTANT && (
              <CardAction
                title={t("general_actions.edit")}
                href={`/${profile}/organizations/${organization.id}/edit`}
                icon={<AiOutlineEdit />}
              />
            )}
          </HBox>
        </Paper>
      ))}

      <Pagination paginate={pagination} />
    </>
  );
}
