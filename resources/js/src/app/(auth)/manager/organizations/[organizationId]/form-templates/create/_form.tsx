"use client";

import Form from "@/components/form";
import { testIDs } from "@/e2e/_testIDs";
import { t } from "@/lang";
import { useFormState } from "react-dom";

import { useParams } from "next/navigation";
import { createFormTemplateAction } from "./_actions";

export function CreateFormTemplateForm() {
  const params = useParams<{ organizationId: string }>();

  const [state, formAction] = useFormState(
    createFormTemplateAction.bind(null, params.organizationId),
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
        name="title"
        label={t("labels.name")}
        placeholder={t("labels.name")}
        withAsterisk
        errors={state?.errors?.["title"]}
      />

      <Form.Input
        name="description"
        label={t("labels.description")}
        placeholder={t("labels.description")}
        withAsterisk
        errors={state?.errors?.["description"]}
      />

      <Form.Button
        className="self-end"
        data-testid={testIDs.SUBMIT_BUTTON}
      >
        {t(`general_actions.create`)}
      </Form.Button>
    </Form>
  );
}
