"use server";

import api from "@/api";
import { ApiResponse, Role } from "@/schemas";

export async function fetchOrganizationsAction(
  profile: Role,
  search?: string,
  page?: number,
): Promise<ApiResponse> {
  const response = await api({
    input: `/${profile}/organizations?page=${page || 1}&q=${search || ""}`,
    init: {
      method: "GET",
    },
  });

  const json = await response.json();

  return ApiResponse.parse(json);
}
