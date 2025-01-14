"use server";

import { actionFlash } from "@/action-flash";
import api from "@/api";
import { ApiResponse } from "@/schemas";
import { redirect } from "next/navigation";

export async function fetchQuestionAction(
  templateId: number,
  questionId: number,
): Promise<ApiResponse> {
  const response = await api({
    input: `/manager/form-templates/${templateId}/short-questions/${questionId}`,
    init: {
      method: "GET",
    },
  });

  const json = await response.json();

  return ApiResponse.parse(json);
}

export async function updateQuestionAction(
  organizationId: number,
  templateId: number,
  questionId: number,
  prevState: any,
  formData: FormData,
): Promise<ApiResponse> {
  const response = await api({
    input: `/manager/form-templates/${templateId}/short-questions/${questionId}`,
    init: {
      method: "PUT",
      body: JSON.stringify({
        description: formData.get("description"),
        answer_required: formData.get("answer_required") == "on" ? true : false,
      }),
    },
  });

  const json = await response.json();

  if (response.ok) {
    actionFlash("success", json.message);
    redirect(`/manager/organizations/${organizationId}/form-templates/${templateId}`);
  }

  return ApiResponse.parse(json);
}
