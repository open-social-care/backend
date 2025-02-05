"use server";

import { actionFlash } from "@/action-flash";
import api from "@/api";
import { ApiResponse } from "@/schemas";
import { redirect } from "next/navigation";

export async function createOrganizationAction(
  prevState: any,
  formData: FormData,
): Promise<ApiResponse> {
  const response = await api({
    input: "/admin/organizations",
    init: {
      method: "POST",
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
    redirect("/admin/organizations");
  }

  return ApiResponse.parse(json);
}
