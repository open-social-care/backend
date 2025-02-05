"use server";

import api from "@/api";
import { Roles } from "@/enums/Roles";
import { ApiResponse } from "@/schemas";

export async function fetchFormAnswers(
  subjectId: number,
  search?: string,
  page?: number,
): Promise<ApiResponse> {
  const response = await api({
    input: `/${Roles.SOCIAL_ASSISTANT}/form-answers/${subjectId}?page=${page || 1}&q=${search || ""}`,
    init: {
      method: "GET",
      next: { tags: ["form-answers"] },
    },
  });

  const json = await response.json();

  return ApiResponse.parse(json);
}
