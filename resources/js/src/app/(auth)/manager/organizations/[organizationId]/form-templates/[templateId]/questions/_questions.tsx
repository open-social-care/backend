import { HBox, Paper } from "@/components/containers";

import { t } from "@/lang";

import { CardAction, Text } from "@/components/ui";
import { Question } from "@/schemas";
import { AiOutlineEdit } from "react-icons/ai";
import { fetchQuestionsAction } from "./_actions";
import { FormAddQuestion } from "./_form-add-question";
import RemoveQuestionAction from "./_remove-question";

interface QuestionListProps {
  templateId: number;
  organizationId: number;
}

export default async function QuestionList({ templateId, organizationId }: QuestionListProps) {
  const { data } = await fetchQuestionsAction(templateId);

  const questions = Question.array().parse(data);

  return (
    <>
      {questions.map((question) => (
        <Paper
          className="mt-1"
          key={question.id}
        >
          <Text className="font-semibold">{question.description}</Text>

          <Text className="text-sm">
            {question.answer_required && (
              <>
                {t("labels.answer_required")}
                <span className="ml-1 text-sm text-red-400">*</span>
              </>
            )}
          </Text>

          <HBox className="mt-4 justify-end gap-4">
            <CardAction
              icon={<AiOutlineEdit />}
              title={t("general_actions.edit")}
              href={`/manager/organizations/${organizationId}/form-templates/${templateId}/questions/${question.id}/edit`}
            />

            <RemoveQuestionAction
              templateId={templateId}
              questionId={question.id}
            />
          </HBox>
        </Paper>
      ))}

      <FormAddQuestion />
    </>
  );
}
