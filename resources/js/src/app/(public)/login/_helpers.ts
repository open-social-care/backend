import { Roles } from "@/enums/Roles";

export function roleByRoleIds(role_ids?: number[]): Roles | undefined {
  if (role_ids?.length != 1) {
    return undefined;
  }

  const roleId = role_ids[0];

  switch (roleId) {
    case 1:
      return Roles.ADMIN;
    case 2:
      return Roles.MANAGER;
    case 3:
      return Roles.SOCIAL_ASSISTANT;
  }
}
