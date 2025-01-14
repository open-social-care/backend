import { z } from "zod";

export const City = z.object({
  id: z.number(),
  name: z.string(),
});

export type City = z.infer<typeof City>;
