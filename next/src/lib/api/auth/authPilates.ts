import { LogoutSuccessResponse } from "@/types/auth/auth";
import { ensureCsrfCookie, getCsrfTokenFromCookie } from "@/lib/api/csrf";
import { AuthApiError } from "./authApi";
import type { AuthPilatesUser } from "@/types/auth/auth";

export async function logoutPilates() {
  await ensureCsrfCookie();
  const response = await fetch(`/api/pilates/logout`, {
    method: "POST",
    credentials: "include",
    headers: {
      "Content-Type": "application/json",
      Accept: "application/json",
      "X-XSRF-TOKEN": getCsrfTokenFromCookie(),
    },
    body: JSON.stringify({}),
  });
  if (!response.ok) {
    const errorBody = await response.json();
    throw new AuthApiError(response.status, errorBody);
  }

  return response.json() as Promise<LogoutSuccessResponse>;
}

export async function getCurrentPilatesUser(): Promise<AuthPilatesUser | null> {
  const response = await fetch("/api/auth/user", {
    credentials: "include",
    headers: {
      Accept: "application/json",
    },
  });

  if (response.status === 401) {
    return null;
  }

  if (!response.ok) {
    throw new Error("認証状態の取得に失敗しました");
  }

  const data: { user: AuthPilatesUser } = await response.json();

  return data.user;
}
