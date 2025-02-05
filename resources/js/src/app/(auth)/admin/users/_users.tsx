import { HBox, Paper } from "@/components/containers";
import { AiOutlineEdit } from "react-icons/ai";

import { t } from "@/lang";
import { User } from "@/schemas";

import { Text } from "@/components/ui";
import Badge from "@/components/ui/Badge";
import CardAction from "@/components/ui/CardAction";
import Pagination from "@/components/ui/Pagination";
import { fetchUsersAction } from "./_actions";

interface UserListProps {
  search?: string;
  page?: number;
}

export default async function Users({ search, page }: UserListProps) {
  const { data, pagination } = await fetchUsersAction(search, page);

  const users = User.array().parse(data);

  return (
    <>
      {users.map((user) => (
        <Paper
          className="mt-1"
          key={user.id}
        >
          <Text className="font-semibold">{user.name}</Text>
          <Text className="text-sm">{user.email}</Text>

          <HBox className="mt-2 gap-4">
            {user.roles?.map((role, index) => <Badge key={index}>{role}</Badge>)}
          </HBox>

          <HBox className="mt-4 justify-end gap-4">
            <CardAction
              title={t("general_actions.edit")}
              href={`/admin/users/${user.id}/edit`}
              icon={<AiOutlineEdit />}
            />
          </HBox>
        </Paper>
      ))}

      <Pagination paginate={pagination} />
    </>
  );
}
