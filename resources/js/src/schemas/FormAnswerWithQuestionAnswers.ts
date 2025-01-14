import { z } from "zod";
import { FormAnswer } from "./FormAnswer";
import { QuestionAnswer } from "./QuestionAnswer";

export const FormAnswerWithQuestionAnswers = FormAnswer.extend({
  short_answers: QuestionAnswer.array(),
});

export type FormAnswerWithQuestionAnswers = z.infer<typeof FormAnswerWithQuestionAnswers>;
