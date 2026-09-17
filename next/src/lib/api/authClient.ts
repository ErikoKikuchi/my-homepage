import {
  LoginCredentials,
  LoginSuccessResponse,
  ValidationErrorResponse,
  AuthErrorResponse,
} from "@/types/auth/auth";

async function ensureCsrfCookie(): Promise<void> {
  await fetch("/sanctum/csrf-cookie", {
    credentials: "include",
  });
}

function getCsrfTokenFromCookie(): string {
  const match = document.cookie.match(/XSRF-TOKEN=([^;]+)/);
  return match ? decodeURIComponent(match[1]) : "";
}

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
