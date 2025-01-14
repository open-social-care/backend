import { DocumentTypes } from "@/enums/DocumentTypes";
import { z } from "zod";

export const Organization = z.object({
  id: z.number(),
  name: z.string(),
  phone: z.string().optional(),
  document: z.string(),
  document_type: z.nativeEnum(DocumentTypes).optional(),
  subject_ref: z.string().optional(),
});

export type Organization = z.infer<typeof Organization>;
