"use server";

import api from "@/api";
import { Roles } from "@/enums/Roles";
import { ApiResponse } from "@/schemas";
import { revalidateTag } from "next/cache";

export async function fetchFormInfo(): Promise<ApiResponse> {
  const response = await api({
    input: `/${Roles.SOCIAL_ASSISTANT}/subjects/get/form-infos`,
    init: {
      method: "GET",
    },
  });

  const json = await response.json();

  return ApiResponse.parse(json);
}

export async function fetchSubjectsAction(
  organizationId: number,
  search?: string,
  page?: number,
): Promise<ApiResponse> {
  const response = await api({
    input: `/${Roles.SOCIAL_ASSISTANT}/subjects/${organizationId}?page=${page || 1}&q=${search || ""}`,
    init: {
      method: "GET",
      next: { tags: ["subjects"] },
    },
  });

  const json = await response.json();

  return ApiResponse.parse(json);
}

export async function removeSubjectAction(subjectId: number): Promise<ApiResponse> {
  const response = await api({
    input: `/${Roles.SOCIAL_ASSISTANT}/subjects/${subjectId}`,
    init: {
      method: "DELETE",
    },
  });

  const json = await response.json();

  if (response.ok) {
    revalidateTag("subjects");
  }

  return ApiResponse.parse(json);
}
