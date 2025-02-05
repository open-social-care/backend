"use server";

import api from "@/api";
import { Roles } from "@/enums/Roles";
import { ApiResponse } from "@/schemas";

export async function fetchFormAnswer(formAnswerId: number): Promise<ApiResponse> {
  const response = await api({
    input: `/${Roles.SOCIAL_ASSISTANT}/form-answers/show/${formAnswerId}`,
    init: { method: "GET" },
  });

  const json = await response.json();

  return ApiResponse.parse(json);
}
