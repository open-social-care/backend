"use server";

import api from "@/api";
import { ApiResponse } from "@/schemas";

export async function fetchOrganizationAction(
  organizationId: number | string,
): Promise<ApiResponse> {
  const response = await api({
    input: `/social-assistant/organizations/${organizationId}`,
    init: {
      method: "GET",
    },
  });

  const json = await response.json();

  return ApiResponse.parse(json);
}
