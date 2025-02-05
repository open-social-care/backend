"use client";

import Form from "@/components/form";
import { testIDs } from "@/e2e/_testIDs";
import { t } from "@/lang";
import { useFormState } from "react-dom";

import { HBox } from "@/components/containers";
import { CardAction } from "@/components/ui";
import { Question } from "@/schemas";
import { updateQuestionAction } from "./_actions";

interface FormUpdateQuestionProps {
  question: Question;
  organizationId: number;
  templateId: number;
  questionId: number;
}

export function FormUpdateQuestion({
  question,
  organizationId,
  templateId,
  questionId,
}: FormUpdateQuestionProps) {
  const [state, formAction] = useFormState(
    updateQuestionAction.bind(null, organizationId, templateId, questionId),
    undefined,
  );

  return (
    <Form action={formAction}>
      {state && (
        <Form.FlashMessage
          type={state.type}
          message={state.message}
        />
      )}

      <Form.Input
        name="description"
        label={t("labels.description")}
        placeholder={t("labels.description")}
        withAsterisk
        errors={state?.errors?.["description"]}
        defaultValue={question.description}
      />

      <Form.Switch
        name="answer_required"
        label={t("labels.answer_required")}
        defaultChecked={question.answer_required}
      />

      <HBox className="mt-4 justify-end gap-8">
        <CardAction
          className="mt-4"
          title={t("general_actions.cancel")}
          href={`/manager/organizations/${organizationId}/form-templates/${templateId}`}
        />

        <Form.Button data-testid={testIDs.SUBMIT_BUTTON}>{t(`general_actions.update`)}</Form.Button>
      </HBox>
    </Form>
  );
}
