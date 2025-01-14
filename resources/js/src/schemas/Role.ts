import { Roles } from "@/enums/Roles";
import { z } from "zod";

export const Role = z.nativeEnum(Roles);

export type Role = z.infer<typeof Role>;
