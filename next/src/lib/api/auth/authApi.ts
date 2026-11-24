import {
  RegisterCredentials,
  RegisterSuccessResponse,
  LoginCredentials,
  LoginSuccessResponse,
  ValidationErrorResponse,
  AuthErrorResponse,
} from "@/types/auth/auth";
import {
  ForgotPasswordCredentials,
  ForgotPasswordSuccessResponse,
} from "@/types/auth/forgotPassword";
import {
  ResetPasswordCredentials,
  ResetPasswordSuccessResponse,
} from "@/types/auth/resetPassword";
import { EmailVerifySuccessResponse } from "@/types/auth/emailVerify";

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

export async function register(
  credentials: RegisterCredentials,
): Promise<RegisterSuccessResponse> {
  await ensureCsrfCookie();

  const { passwordConfirmation, ...rest } = credentials;

  const response = await fetch(`/api/auth/register`, {
    method: "POST",
    credentials: "include",
    headers: {
      "Content-Type": "application/json",
      Accept: "application/json",
      "X-XSRF-TOKEN": getCsrfTokenFromCookie(),
    },
    body: JSON.stringify({
      ...rest,
      password_confirmation: passwordConfirmation,
    }),
  });
  if (!response.ok) {
    const errorBody = await response.json();
    throw new AuthApiError(response.status, errorBody);
  }

  return response.json() as Promise<RegisterSuccessResponse>;
}

async function readPasswordResponse<T>(response: Response): Promise<T> {
  const body = await response.json().catch(() => null);
  if (!response.ok || !body) {
    const message = response.status === 419
      ? "ページを開き直してから、もう一度お試しください。"
      : "パスワード再設定の処理に失敗しました。";
    throw new AuthApiError(response.status, body ?? { message });
  }

  return body;
}

export async function forgotPassword(
  credentials: ForgotPasswordCredentials,
): Promise<ForgotPasswordSuccessResponse> {
  await ensureCsrfCookie();

  const response = await fetch(`/api/auth/forgot-password`, {
    method: "POST",
    credentials: "include",
    headers: {
      "Content-Type": "application/json",
      Accept: "application/json",
      "X-XSRF-TOKEN": getCsrfTokenFromCookie(),
    },
    body: JSON.stringify(credentials),
  });
  return readPasswordResponse<ForgotPasswordSuccessResponse>(response);
}

export async function resetPassword(
  credentials: ResetPasswordCredentials,
): Promise<ResetPasswordSuccessResponse> {
  await ensureCsrfCookie();

  const { passwordConfirmation, ...rest } = credentials;

  const response = await fetch(`/api/auth/reset-password`, {
    method: "POST",
    credentials: "include",
    headers: {
      "Content-Type": "application/json",
      Accept: "application/json",
      "X-XSRF-TOKEN": getCsrfTokenFromCookie(),
    },
    body: JSON.stringify({
      ...rest,
      password_confirmation: passwordConfirmation,
    }),
  });
  return readPasswordResponse<ResetPasswordSuccessResponse>(response);
}

export async function resendVerificationEmail() {
  await ensureCsrfCookie();
  const response = await fetch(`/api/auth/email/verification-notification`, {
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

  return response.json() as Promise<EmailVerifySuccessResponse>;
}
