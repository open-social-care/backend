"use server";

import { actionFlash } from "@/action-flash";
import api from "@/api";
import { ApiResponse } from "@/schemas";

export async function fetchQuestionsAction(
  templateId: number,
  search?: string,
  page?: number,
): Promise<ApiResponse> {
  const response = await api({
    input: `/manager/form-templates/${templateId}/short-questions`,
    init: {
      method: "GET",
    },
  });

  const json = await response.json();

  return ApiResponse.parse(json);
}

export async function createQuestionAction(
  templateId: string,
  prevState: any,
  formData: FormData,
): Promise<ApiResponse> {
  const response = await api({
    input: `/manager/form-templates/${templateId}/short-questions`,
    init: {
      method: "POST",
      body: JSON.stringify({
        description: formData.get("description"),
        answer_required: formData.get("answer_required") == "on" ? true : false,
      }),
    },
  });

  const json = await response.json();

  if (response.ok) {
    actionFlash("success", json.message);
  }

  return ApiResponse.parse(json);
}

export async function removeQuestionAction(
  templateId: number,
  questionId: number,
): Promise<ApiResponse> {
  const response = await api({
    input: `/manager/form-templates/${templateId}/short-questions/${questionId}`,
    init: {
      method: "DELETE",
    },
  });

  const json = await response.json();

  if (response.ok) {
    actionFlash("success", json.message);
  }

  return ApiResponse.parse(json);
}
