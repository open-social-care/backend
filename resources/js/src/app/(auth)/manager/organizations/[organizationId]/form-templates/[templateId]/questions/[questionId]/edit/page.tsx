import { Paper } from "@/components/containers";
import { Heading } from "@/components/ui";
import { Question } from "@/schemas";
import { fetchQuestionAction } from "./_actions";
import { FormUpdateQuestion } from "./_form";

interface PageProps {
  params: {
    organizationId: number;
    templateId: number;
    questionId: number;
  };
}

export default async function page({ params }: PageProps) {
  const { data } = await fetchQuestionAction(params.templateId, params.questionId);

  const question = Question.parse(data);

  return (
    <>
      <Heading h1>Editar questão</Heading>

      <Paper className="mt-4">
        <FormUpdateQuestion
          question={question}
          {...params}
        />
      </Paper>
    </>
  );
}
