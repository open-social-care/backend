import { z } from "zod";
import { Address } from "./Address";
import { Subject } from "./Subject";

export const SubjectDetails = Subject.extend({
  nationality: z.string().nullish(),
  phone: z.string().nullish(),
  father_name: z.string().nullish(),
  mother_name: z.string().nullish(),
  cpf: z.string().nullish(),
  rg: z.string().nullish(),
  skin_color: z.string().nullish(),
  relative_relation_type: z.string().nullish(),
  relative_name: z.string().nullish(),
  relative_phone: z.string().nullish(),
  addresses: Address.array(),
});

export type SubjectDetails = z.infer<typeof SubjectDetails>;
