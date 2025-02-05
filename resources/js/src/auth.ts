"use server";

import { User } from "@/schemas";
import { cookies } from "next/headers";
import { permanentRedirect } from "next/navigation";

const getAuthDataFromCookie = () => {
  const cookieStore = cookies();

  const cookieData = cookieStore.get("auth-user")?.value;

  if (cookieData) {
    const user = User.parse(JSON.parse(cookieData));

    return user;
  }
};

export const getTokenFromCookie = () => {
  const user = getAuthDataFromCookie();

  return user?.token;
};

const getUserFromCookie = () => {
  const user = getAuthDataFromCookie();

  if (user) {
    // remove token info for non server components
    return { ...user, token: null };
  }
};

export default async function auth() {
  const user = getUserFromCookie();

  if (user) {
    return { user };
  }

  permanentRedirect("/login");
}
