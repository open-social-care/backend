import { z } from "zod";

export const Address = z.object({
  state_id: z.number(),
  city_id: z.number(),
  street: z.string(),
  number: z.string(),
  district: z.string(),
  complement: z.string().nullish(),
});

export type Address = z.infer<typeof Address>;
