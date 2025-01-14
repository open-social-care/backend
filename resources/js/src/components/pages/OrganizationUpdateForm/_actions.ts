"use server";

import { actionFlash } from "@/action-flash";
import api from "@/api";
import { ApiResponse, Role } from "@/schemas";
import { redirect } from "next/navigation";

export async function updateOrganizationAction(
  profile: Role,
  organizationId: number,
  prevState: any,
  formData: FormData,
): Promise<ApiResponse> {
  const response = await api({
    input: `/${profile}/organizations/${organizationId}`,
    init: {
      method: "PUT",
      body: JSON.stringify({
        name: formData.get("name"),
        phone: formData.get("phone"),
        document_type: formData.get("document_type"),
        document: formData.get("document"),
        subject_ref: formData.get("subject_ref"),
      }),
    },
  });

  const json = await response.json();

  if (response.ok) {
    actionFlash("success", json.message);
    redirect(`/${profile}/organizations/${organizationId}`);
  }

  return ApiResponse.parse(json);
}
