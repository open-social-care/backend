import { Paper, VBox } from "@/components/containers";
import { Heading, Text } from "@/components/ui";
import { t } from "@/lang";
import { FormAnswerWithQuestionAnswers } from "@/schemas";
import dayjs from "dayjs";
import { twMerge } from "tailwind-merge";
import { fetchFormAnswer } from "./_actions";

interface PageProps {
  params: {
    formAnswerId: number;
  };
}

export default async function page({ params }: PageProps) {
  const { data } = await fetchFormAnswer(params.formAnswerId);

  const formAnswer = FormAnswerWithQuestionAnswers.parse(data);

  return (
    <>
      <Heading h1>{t("page_titles.view_form_answer")}</Heading>

      <Paper className="mt-5">
        <Text className="font-semibold">{formAnswer.form_template_title}</Text>

        <Text>{dayjs(formAnswer.created_at).format("DD/MM/YYYY HH:mm")}</Text>

        <Text className="text-gray-400">
          {t("labels.filled_by")}
          {": "}
          {formAnswer.user_name}
        </Text>
      </Paper>

      <VBox className="mt-2">
        {formAnswer.short_answers.map((question_answer) => {
          const questionFilled = !!question_answer.answer;

          return (
            <Paper key={question_answer.id}>
              <Text className={twMerge("font-semibold", !questionFilled && "text-gray-400")}>
                {question_answer.short_question.description}
                {question_answer.short_question.answer_required && (
                  <span className="ml-1 text-sm text-red-400">*</span>
                )}
              </Text>

              <Text className={twMerge(!questionFilled && "text-gray-400")}>
                {question_answer.answer || t("informations.question_not_filled")}
              </Text>
            </Paper>
          );
        })}
      </VBox>
    </>
  );
}
