import { z } from "zod";

export const SkinColor = z.object({
  id: z.string(),
  name: z.string(),
});

export type SkinColor = z.infer<typeof SkinColor>;
