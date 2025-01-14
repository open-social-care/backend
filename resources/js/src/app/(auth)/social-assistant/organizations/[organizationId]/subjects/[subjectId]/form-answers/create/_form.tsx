"use client";

import Form from "@/components/form";
import Input from "@/components/form/Input";
import { testIDs } from "@/e2e/_testIDs";
import { t } from "@/lang";
import { FormTemplateWithQuestions } from "@/schemas";
import { useParams } from "next/navigation";
import { useFormState } from "react-dom";
import { createFormAnswer } from "./_actions";

type FormProps = {
  template: FormTemplateWithQuestions;
};

export default function AnswersForm({ template }: FormProps) {
  const { organizationId, subjectId } = useParams<{ organizationId: string; subjectId: string }>();

  const [state, formAction] = useFormState(
    createFormAnswer.bind(null, organizationId, subjectId, template),
    undefined,
  );

  return (
    <Form
      className="mt-2"
      action={formAction}
    >
      {state && (
        <Form.FlashMessage
          type={state.type}
          message={state.message}
        />
      )}

      {template.short_questions.map((question, index) => (
        <Input
          key={index}
          name={`short_answers.${question.id}`}
          label={question.description}
          placeholder={question.description}
          withAsterisk={question.answer_required}
          errors={state?.errors?.[`short_answers.${index}.answer`]}
        />
      ))}

      <Form.Button
        className="self-end"
        data-testid={testIDs.SUBMIT_BUTTON}
      >
        {t("general_actions.create")}
      </Form.Button>
    </Form>
  );
}
