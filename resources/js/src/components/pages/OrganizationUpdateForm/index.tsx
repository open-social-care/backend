"use client";

import Form from "@/components/form";
import { testIDs } from "@/e2e/_testIDs";
import { t } from "@/lang";
import { useFormState } from "react-dom";

import documentTypesToSelect from "@/helpers/documentTypesToSelect";
import { Organization, Role } from "@/schemas";
import { updateOrganizationAction } from "./_actions";

interface OrganizationUpdateFormProps {
  profile: Role;
  organization: Organization;
}

export function OrganizationUpdateForm({ profile, organization }: OrganizationUpdateFormProps) {
  const [state, formAction] = useFormState(
    updateOrganizationAction.bind(null, profile, organization.id),
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
        name="name"
        label={t("labels.name")}
        placeholder={t("labels.name")}
        withAsterisk
        errors={state?.errors?.["name"]}
        defaultValue={organization.name}
      />

      <Form.Input
        name="phone"
        label={t("labels.phone")}
        placeholder="(00) 00000-0000"
        withAsterisk
        mask={[{ mask: "(00) 0000-0000" }, { mask: "(00) 00000-0000" }]}
        errors={state?.errors?.["phone"]}
        defaultValue={organization.phone}
      />

      <Form.Select
        name="document_type"
        label={t("labels.document_type")}
        data={documentTypesToSelect()}
        withAsterisk
        errors={state?.errors?.["document_type"]}
        defaultValue={organization.document_type}
      />

      <Form.Input
        name="document"
        label={t("labels.document")}
        placeholder={t("labels.document")}
        withAsterisk
        mask={[{ mask: "000.000.000-00" }, { mask: "00.000.000/0000-00" }]}
        errors={state?.errors?.["document"]}
        defaultValue={organization.document}
      />

      <Form.Input
        name="subject_ref"
        label={t("labels.subject_reference")}
        placeholder={t("labels.subject_reference")}
        withAsterisk
        errors={state?.errors?.["subject_ref"]}
        defaultValue={organization.subject_ref}
      />

      <Form.Button
        className="self-end"
        data-testid={testIDs.SUBMIT_BUTTON}
      >
        {t(`general_actions.update`)}
      </Form.Button>
    </Form>
  );
}
