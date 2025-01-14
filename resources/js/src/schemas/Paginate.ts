import { z } from "zod";

export const Paginate = z.object({
  total: z.number(),
  per_page: z.number(),
  current_page: z.number(),
  last_page: z.number(),
  from: z.number().nullish(),
  to: z.number().nullish(),
});

export type Paginate = z.infer<typeof Paginate>;
