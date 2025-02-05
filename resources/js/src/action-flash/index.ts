"use server";

import { cookies } from "next/headers";

export type ActionFlashProps = {
  type: "error" | "success";
  message: string;
};

export const actionFlash = (type: "error" | "success", message: string) => {
  const cookieStore = cookies();
  const all = JSON.parse(cookieStore.get("action-flash")?.value || "[]");

  cookieStore.set(
    "action-flash",
    JSON.stringify([
      ...all,
      {
        type,
        message,
      },
    ]),
    {
      httpOnly: false,
      sameSite: "lax",
    },
  );
};

export const getActionFlashes = async (): Promise<ActionFlashProps[]> => {
  const cookieStore = cookies();
  const all = JSON.parse(cookieStore.get("action-flash")?.value || "[]");
  cookieStore.delete("action-flash");
  return all;
};
