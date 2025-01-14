import { z } from "zod";
import { Question } from "./Question";

export const QuestionAnswer = z.object({
  id: z.number(),
  answer: z.string().nullable(),
  short_question: Question,
});

export type QuestionAnswer = z.infer<typeof QuestionAnswer>;
