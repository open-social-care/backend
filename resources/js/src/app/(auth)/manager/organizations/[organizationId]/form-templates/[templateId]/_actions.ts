import api from "@/api";
import { ApiResponse } from "@/schemas";

export async function fetchFormTemplateAction(templateId: number): Promise<ApiResponse> {
  const response = await api({
    input: `/manager/form-templates/show/${templateId}`,
    init: {
      method: "GET",
    },
  });

  const json = await response.json();

  return ApiResponse.parse(json);
}
