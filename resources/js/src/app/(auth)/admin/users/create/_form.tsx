"use client";

import Form from "@/components/form";
import { testIDs } from "@/e2e/_testIDs";
import { t } from "@/lang";
import { useFormState } from "react-dom";

import { createUserAction } from "./_actions";

export function CreateUserForm() {
  const [state, formAction] = useFormState(createUserAction, undefined);

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
        label="Nome"
        placeholder="Nome"
        withAsterisk
        errors={state?.errors?.["name"]}
      />

      <Form.Input
        name="email"
        label={t("auth.email")}
        placeholder="user@email.com"
        withAsterisk
        errors={state?.errors?.["email"]}
      />

      <Form.Input
        name="password"
        label={t("auth.password")}
        type="password"
        placeholder="********"
        withAsterisk
        errors={state?.errors?.["password"]}
      />

      <Form.Input
        name="password_confirmation"
        label={t("auth.password_confirmation")}
        type="password"
        placeholder="********"
        withAsterisk
        errors={state?.errors?.["password_confirmation"]}
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
