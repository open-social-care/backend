import { z } from "zod";
import { Organization } from "./Organization";

export const User = z.object({
  id: z.number(),
  name: z.string(),
  email: z.string(),
  organizations: Organization.array().optional(),
  roles: z.string().array().optional(),
  roles_ids: z.number().array().optional(),
  token: z.string().nullish(),
});

export type User = z.infer<typeof User>;
