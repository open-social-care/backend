"use client";

import Form from "@/components/form";
import { testIDs } from "@/e2e/_testIDs";
import { t } from "@/lang";
import { useFormState } from "react-dom";

import { HBox } from "@/components/containers";
import { CardAction } from "@/components/ui";
import { FormTemplate } from "@/schemas";
import { updateFormTemplateAction } from "./_actions";

interface UpdateFormTemplateFormProps {
  template: FormTemplate;
  organizationId: number;
}

export function UpdateFormTemplateForm({ template, organizationId }: UpdateFormTemplateFormProps) {
  const [state, formAction] = useFormState(
    updateFormTemplateAction.bind(null, organizationId, template.id),
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
        defaultValue={template.title}
      />

      <Form.Input
        name="description"
        label={t("labels.description")}
        placeholder={t("labels.description")}
        withAsterisk
        errors={state?.errors?.["description"]}
        defaultValue={template.description}
      />

      <HBox className="mt-4 justify-end gap-8">
        <CardAction
          className="mt-4"
          title={t("general_actions.cancel")}
          href={`/manager/organizations/${organizationId}/form-templates/${template.id}`}
        />

        <Form.Button data-testid={testIDs.SUBMIT_BUTTON}>{t(`general_actions.update`)}</Form.Button>
      </HBox>
    </Form>
  );
}
