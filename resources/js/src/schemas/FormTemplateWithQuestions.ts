import { z } from "zod";
import { FormTemplate } from "./FormTemplate";
import { Question } from "./Question";

export const FormTemplateWithQuestions = FormTemplate.extend({
  short_questions: Question.array(),
});

export type FormTemplateWithQuestions = z.infer<typeof FormTemplateWithQuestions>;
