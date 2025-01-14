"use server";

import { actionFlash } from "@/action-flash";
import api from "@/api";
import { ApiResponse } from "@/schemas";
import { redirect } from "next/navigation";

export async function updateFormTemplateAction(
  organizationId: number,
  templateId: number,
  prevState: any,
  formData: FormData,
): Promise<ApiResponse> {
  const response = await api({
    input: `/manager/form-templates/${templateId}`,
    init: {
      method: "PUT",
      body: JSON.stringify({
        title: formData.get("title"),
        description: formData.get("description"),
      }),
    },
  });

  const json = await response.json();

  if (response.ok) {
    actionFlash("success", json.message);
    redirect(`/manager/organizations/${organizationId}/form-templates`);
  }

  return ApiResponse.parse(json);
}
