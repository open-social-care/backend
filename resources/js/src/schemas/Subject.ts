import { z } from "zod";

export const Subject = z.object({
  id: z.number(),
  name: z.string(),
  birth_date: z.string(),
  last_form_answer_date: z.string().nullish(),
});

export type Subject = z.infer<typeof Subject>;
