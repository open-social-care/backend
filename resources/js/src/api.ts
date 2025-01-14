import { getTokenFromCookie } from "./auth";

type ApiProps = { input: RequestInfo | URL; init?: RequestInit };

// normalize baseUrl by removing last slash if exists
const baseUrl = process.env.API_BASE_URL?.endsWith("/")
  ? process.env.API_BASE_URL.slice(0, -1)
  : process.env.API_BASE_URL;

const api = async ({ input, init }: ApiProps) => {
  const config: RequestInit = {
    headers: {
      "Content-Type": "application/json",
      Accept: "application/json",
      Authorization: `Bearer ${getTokenFromCookie()}`,
    },
    ...init,
  };

  return await fetch(`${baseUrl}${input}`, config);
};

export default api;
