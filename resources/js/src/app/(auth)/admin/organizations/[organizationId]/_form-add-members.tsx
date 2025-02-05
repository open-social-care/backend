"use client";

import Form from "@/components/form";
import { testIDs } from "@/e2e/_testIDs";
import { t } from "@/lang";
import { Role, User } from "@/schemas";
import { useFormState } from "react-dom";
import { associateUserAction } from "./_actions";

interface FormAddMembersProps {
  organizationId: number;
  role: Role;
  users: User[];
}

export default function FormAddMembers({ organizationId, role, users }: FormAddMembersProps) {
  const [state, formAction] = useFormState(
    associateUserAction.bind(null, organizationId, role),
    undefined,
  );

  const usersForSelect = users.map((user) => {
    return {
      label: user.name,
      value: user.id.toString(),
    };
  });

  return (
    <Form action={formAction}>
      {state && (
        <Form.FlashMessage
          type={state.type}
          message={state.message}
        />
      )}

      <Form.Select
        name="user_id"
        label={t("general_actions.add")}
        placeholder={t("page_titles.users")}
        data={usersForSelect}
        errors={state?.errors?.["user_id"]}
      />

      <Form.Button
        className="self-end"
        data-testid={testIDs.SUBMIT_BUTTON}
      >
        {t(`general_actions.add`)}
      </Form.Button>
    </Form>
  );
}
