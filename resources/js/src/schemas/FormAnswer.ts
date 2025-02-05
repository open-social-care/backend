import { z } from "zod";

export const FormAnswer = z.object({
  id: z.number(),
  form_template_title: z.string(),
  user_name: z.string(),
  created_at: z.string().datetime(),
});

export type FormAnswer = z.infer<typeof FormAnswer>;
