import { z } from "zod";

export const State = z.object({
  id: z.number(),
  name: z.string(),
});

export type State = z.infer<typeof State>;
