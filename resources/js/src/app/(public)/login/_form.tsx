"use client";

import Form from "@/components/form";
import { testIDs } from "@/e2e/_testIDs";
import { t } from "@/lang";
import { useFormState } from "react-dom";
import { loginAction } from "./_actions";

export default function LoginForm() {
  const [state, formAction] = useFormState(loginAction, undefined);

  return (
    <>
      <Form action={formAction}>
        {state && (
          <Form.FlashMessage
            type={state.type}
            message={state.message}
          />
        )}

        <Form.Input
          name="email"
          label={t("auth.email")}
          placeholder="your@email.com"
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

        <Form.Button
          className="self-center"
          data-testid={testIDs.SUBMIT_BUTTON}
        >
          {t("auth.login")}
        </Form.Button>
      </Form>
    </>
  );
}
