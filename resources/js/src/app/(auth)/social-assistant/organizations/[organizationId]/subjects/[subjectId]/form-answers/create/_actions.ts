"use server";

import { actionFlash } from "@/action-flash";
import api from "@/api";
import { Roles } from "@/enums/Roles";
import { ApiResponse, FormTemplateWithQuestions } from "@/schemas";
import { redirect } from "next/navigation";

export async function fetchTemplates(organizationId: number): Promise<ApiResponse> {
  const res = await api({
    input: `/${Roles.SOCIAL_ASSISTANT}/form-templates/select/${organizationId}`,
    init: {
      method: "GET",
    },
  });

  const json = await res.json();

  return ApiResponse.parse(json);
}

export async function fetchFormTemplate(templateId: number): Promise<ApiResponse> {
  const res = await api({
    input: `/${Roles.SOCIAL_ASSISTANT}/form-templates/${templateId}`,
    init: {
      method: "GET",
    },
  });

  const json = await res.json();

  return ApiResponse.parse(json);
}

export async function createFormAnswer(
  organizationId: string,
  subjectId: string,
  template: FormTemplateWithQuestions,
  prevstate: any,
  formData: FormData,
): Promise<ApiResponse> {
  const response = await api({
    input: `/${Roles.SOCIAL_ASSISTANT}/form-answers/${subjectId}`,
    init: {
      method: "POST",
      body: JSON.stringify({
        form_template_id: template.id,
        short_answers: template.short_questions.map((question) => ({
          short_question_id: question.id,
          answer: formData.get(`short_answers.${question.id}`),
        })),
      }),
    },
  });

  const json = await response.json();

  if (response.ok) {
    actionFlash("success", json.message);
    redirect(
      `/${Roles.SOCIAL_ASSISTANT}/organizations/${organizationId}/subjects/${subjectId}/form-answers`,
    );
  }

  return ApiResponse.parse(json);
}
