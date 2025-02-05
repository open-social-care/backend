"use server";

import { actionFlash } from "@/action-flash";
import api from "@/api";
import { Roles } from "@/enums/Roles";
import { ApiResponse } from "@/schemas";
import { redirect } from "next/navigation";

export async function createSubjectAction(
  organizationId: string,
  prevState: any,
  formData: FormData,
): Promise<ApiResponse> {
  const address = {
    street: formData.get("street"),
    number: formData.get("number"),
    district: formData.get("district"),
    complement: formData.get("complement"),
    state_id: formData.get("state_id"),
    city_id: formData.get("city_id"),
  };

  const addressHasValue = Object.values(address).filter((a) => a != "" && a).length;

  const response = await api({
    input: `/${Roles.SOCIAL_ASSISTANT}/subjects/${organizationId}`,
    init: {
      method: "POST",
      body: JSON.stringify({
        name: formData.get("name"),
        birth_date: formData.get("birth_date"),
        nationality: formData.get("nationality"),
        phone: formData.get("phone"),
        father_name: formData.get("father_name"),
        mother_name: formData.get("mother_name"),
        cpf: formData.get("cpf"),
        rg: formData.get("rg"),
        skin_color: formData.get("skin_color"),
        relative_relation_type: formData.get("relative_relation_type"),
        relative_name: formData.get("relative_name"),
        relative_phone: formData.get("relative_phone"),
        addresses: addressHasValue ? [address] : null,
      }),
    },
  });

  const json = await response.json();

  if (response.ok) {
    actionFlash("success", json.message);
    redirect(`/${Roles.SOCIAL_ASSISTANT}/organizations/${organizationId}/subjects`);
  }

  return ApiResponse.parse(json);
}
