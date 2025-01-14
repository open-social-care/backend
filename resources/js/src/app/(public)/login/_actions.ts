"use server";

import api from "@/api";
import { ApiResponse, User } from "@/schemas";
import { cookies } from "next/headers";
import { redirect } from "next/navigation";
import { roleByRoleIds } from "./_helpers";

export async function loginAction(prevState: any, formData: FormData): Promise<ApiResponse> {
  const response = await api({
    input: "/login",
    init: {
      method: "POST",
      body: JSON.stringify({
        email: formData.get("email"),
        password: formData.get("password"),
      }),
    },
  });

  const json = await response.json();

  const { success: isValidUser, data: user } = User.safeParse(json.data);

  if (isValidUser && json.token) {
    const cookieStore = cookies();

    cookieStore.set(
      "auth-user",
      JSON.stringify({
        ...user,
        token: json.token,
      }),
      {
        httpOnly: true,
        sameSite: "lax",
      },
    );

    const role = roleByRoleIds(user.roles_ids);

    if (role) {
      redirect(`/${role}/organizations`);
    } else {
      redirect("/profile-select");
    }
  }

  return ApiResponse.parse(json);
}
