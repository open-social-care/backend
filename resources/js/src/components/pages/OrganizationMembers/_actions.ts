"use server";

import api from "@/api";
import { ApiResponse } from "@/schemas";
import { Role } from "@/schemas/Role";
import { revalidateTag } from "next/cache";

export async function fetchUsersByRole(
  profile: Role,
  organizationId: number,
  role: Role,
  search?: string,
  page?: number,
): Promise<ApiResponse> {
  const response = await api({
    input: `/${profile}/organizations/${organizationId}/get-users-by-role/${role}?page=${page || 1}&q=${search || ""}`,
    init: {
      method: "GET",
      next: {
        tags: [`members.${role}`],
      },
    },
  });

  const json = await response.json();

  return ApiResponse.parse(json);
}

export interface DissociateUserActionValues {
  organizationId: number;
  role: Role;
  userId: number;
}

export async function dissociateUserAction({
  organizationId,
  role,
  userId,
}: DissociateUserActionValues): Promise<ApiResponse> {
  const response = await api({
    input: `/admin/organizations/${organizationId}/disassociate-users`,
    init: {
      method: "POST",
      body: JSON.stringify({ data: [{ user_id: userId, role_name: role }] }),
    },
  });

  const json = await response.json();

  if (response.ok) {
    revalidateTag(`members.${role}`);
  }

  return ApiResponse.parse(json);
}
