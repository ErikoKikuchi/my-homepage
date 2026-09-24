import {
  LoginCredentials,
  LoginSuccessResponse,
  ValidationErrorResponse,
  AuthErrorResponse,
} from "@/types/auth/auth";
import type { AuthPilatesUser } from "@/types/auth/auth";
import { ensureCsrfCookie, getCsrfTokenFromCookie } from "@/lib/api/csrf";

export async function login(
  section: "pilates" | "thinkmotion",
  credentials: LoginCredentials,
): Promise<LoginSuccessResponse> {
  await ensureCsrfCookie();

  const response = await fetch(`/api/${section}/login`, {
    method: "POST",
    credentials: "include",
    headers: {
      "Content-Type": "application/json",
      Accept: "application/json",
      "X-XSRF-TOKEN": getCsrfTokenFromCookie(),
    },
    body: JSON.stringify(credentials),
  });

  if (!response.ok) {
    const errorBody = await response.json();
    throw new AuthApiError(response.status, errorBody);
  }

  return response.json() as Promise<LoginSuccessResponse>;
}

export class AuthApiError extends Error {
  constructor(
    public status: number,
    public body: ValidationErrorResponse | AuthErrorResponse,
  ) {
    super(body.message);
  }
}
export async function getCurrentPilatesUser(): Promise<AuthPilatesUser | null> {
  const response = await fetch("/auth/user", {
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
