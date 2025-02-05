"use server";

import api from "@/api";
import { ApiResponse, Role } from "@/schemas";
import { revalidateTag } from "next/cache";

export async function fetchOrganizationAction(organizationId: number): Promise<ApiResponse> {
  const response = await api({
    input: `/admin/organizations/${organizationId}`,
    init: {
      method: "GET",
    },
  });

  const json = await response.json();

  return ApiResponse.parse(json);
}

export async function fetchNonMembersAction(
  organizationId: number,
  role: Role,
): Promise<ApiResponse> {
  const response = await api({
    input: `/admin/organizations/${organizationId}/get-non-members-by-role/${role}`,
    init: {
      method: "GET",
      next: {
        tags: [`non-members.${role}`],
      },
    },
  });

  const json = await response.json();

  return ApiResponse.parse(json);
}

export async function associateUserAction(
  organizationId: number,
  role: Role,
  prevState: any,
  formData: FormData,
): Promise<ApiResponse> {
  const response = await api({
    input: `/admin/organizations/${organizationId}/associate-users`,
    init: {
      method: "POST",
      body: JSON.stringify({ data: [{ user_id: formData.get("user_id"), role_name: role }] }),
    },
  });

  if (response.ok) {
    revalidateTag(`non-members.${role}`);
    revalidateTag(`members.${role}`);
  }

  const json = await response.json();
  return ApiResponse.parse(json);
}
