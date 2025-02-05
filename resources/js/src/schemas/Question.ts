import { z } from "zod";

export const Question = z.object({
  id: z.number(),
  description: z.string(),
  answer_required: z.boolean(),
});

export type Question = z.infer<typeof Question>;
