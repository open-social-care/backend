import { Role, User } from "@/schemas";

import { HBox, VBox } from "@/components/containers";
import { Text } from "@/components/ui";
import Pagination from "@/components/ui/Pagination";
import { t } from "@/lang";
import { fetchUsersByRole } from "./_actions";
import DissociateAction from "./_dissociate-action";

interface OrganizationMembersProps {
  profile: Role;
  organizationId: number;
  role: Role;
  search?: string;
  page?: number;
  pageQueryName: string;
}

export default async function OrganizationMembers({
  profile,
  organizationId,
  role,
  search,
  page,
  pageQueryName,
}: OrganizationMembersProps) {
  const { data, pagination } = await fetchUsersByRole(profile, organizationId, role, search, page);

  const users = User.array().parse(data);

  if (users.length == 0) {
    return <Text className="mt-4">{t("informations.users_not_found")}</Text>;
  }

  return (
    <div className="mt-4">
      {users.map((user) => (
        <VBox
          key={user.id}
          className="rounded-lg px-4 py-2 hover:bg-gray-50 sm:flex-row sm:justify-between"
        >
          <VBox>
            <Text className="font-semibold">{user.name}</Text>

            <Text className="text-sm">{user.email}</Text>
          </VBox>

          {profile == "admin" && (
            <HBox className="justify-end">
              <DissociateAction
                organizationId={organizationId}
                role={role}
                userId={user.id}
              />
            </HBox>
          )}
        </VBox>
      ))}

      <Pagination
        paginate={pagination}
        queryName={pageQueryName}
      />
    </div>
  );
}
