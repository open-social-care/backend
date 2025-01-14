"use server";

import api from "@/api";
import { Roles } from "@/enums/Roles";
import { ApiResponse } from "@/schemas";

export async function fetchSubjectAction(subjectId: string): Promise<ApiResponse> {
  const response = await api({
    input: `/${Roles.SOCIAL_ASSISTANT}/subjects/show/${subjectId}`,
    init: {
      method: "GET",
    },
  });

  const json = await response.json();

  return ApiResponse.parse(json);
}
