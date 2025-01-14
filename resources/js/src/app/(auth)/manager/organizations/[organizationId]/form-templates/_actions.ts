"use server";

import api from "@/api";
import { ApiResponse } from "@/schemas";
import { revalidateTag } from "next/cache";

export async function fetchFormTemplatesAction(
  organizationId: number,
  search?: string,
  page?: number,
): Promise<ApiResponse> {
  const response = await api({
    input: `/manager/form-templates/${organizationId}?page=${page || 1}&q=${search || ""}`,
    init: {
      method: "GET",
      next: { tags: ["form-templates"] },
    },
  });

  const json = await response.json();

  return ApiResponse.parse(json);
}

export async function removeFormTemplateAction(templateId: number): Promise<ApiResponse> {
  const response = await api({
    input: `/manager/form-templates/${templateId}`,
    init: {
      method: "DELETE",
    },
  });

  const json = await response.json();

  if (response.ok) {
    revalidateTag("form-templates");
  }

  return ApiResponse.parse(json);
}
