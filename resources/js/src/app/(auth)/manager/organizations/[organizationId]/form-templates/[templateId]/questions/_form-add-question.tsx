"use client";

import Form from "@/components/form";
import { testIDs } from "@/e2e/_testIDs";
import { t } from "@/lang";
import { useFormState } from "react-dom";

import { Paper } from "@/components/containers";
import { Heading } from "@/components/ui";
import { useParams } from "next/navigation";
import { createQuestionAction } from "./_actions";

export function FormAddQuestion() {
  const params = useParams<{ templateId: string }>();

  const [state, formAction] = useFormState(
    createQuestionAction.bind(null, params.templateId),
    undefined,
  );

  return (
    <Paper className="mt-1">
      <Heading h2>{t("labels.add_question")}</Heading>

      <Form action={formAction}>
        <Form.Input
          name="description"
          label={t("labels.description")}
          placeholder={t("labels.description")}
          withAsterisk
          errors={state?.errors?.["description"]}
        />

        <Form.Switch
          name="answer_required"
          label={t("labels.answer_required")}
        />

        <Form.Button
          className="self-end"
          data-testid={testIDs.SUBMIT_BUTTON}
        >
          {t(`general_actions.create`)}
        </Form.Button>
      </Form>
    </Paper>
  );
}
