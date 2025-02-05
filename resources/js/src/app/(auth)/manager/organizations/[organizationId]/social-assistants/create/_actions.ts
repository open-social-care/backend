"use server";

import api from "@/api";
import { ApiResponse } from "@/schemas";

export async function createUserAction(
  organizationId: string,
  prevState: any,
  formData: FormData,
): Promise<ApiResponse> {
  const response = await api({
    input: `/manager/users/${organizationId}`,
    init: {
      method: "POST",
      body: JSON.stringify({
        name: formData.get("name"),
        email: formData.get("email"),
        password: formData.get("password"),
        password_confirmation: formData.get("password_confirmation"),
      }),
    },
  });

  const json = await response.json();

  return ApiResponse.parse(json);
}
