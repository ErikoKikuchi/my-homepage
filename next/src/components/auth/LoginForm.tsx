"use client";

import { useState } from "react";
import { useRouter } from "next/navigation";
import { login, AuthApiError } from "@/lib/api/authClient";
import EmailInput from "@/components/auth/EmailInput";
import PasswordInput from "@/components/auth/PasswordInput";

export function LoginForm() {
  const router = useRouter();
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [fieldErrors, setFieldErrors] = useState<Record<string, string[]>>({});
  const [generalError, setGeneralError] = useState<string | null>(null);
  const [isSubmitting, setIsSubmitting] = useState(false);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setFieldErrors({});
    setGeneralError(null);
    setIsSubmitting(true);

    try {
      const result = await login({ email, password, remember: false });
      router.push(result.redirectTo);
    } catch (error) {
      if (error instanceof AuthApiError) {
        if ("errors" in error.body) {
          setFieldErrors(error.body.errors);
        } else {
          setGeneralError(error.body.message);
        }
      } else {
        setGeneralError(
          "通信エラーが発生しました。時間をおいて再度お試しください。",
        );
      }
    } finally {
      setIsSubmitting(false);
    }
  };

  return (
    <form onSubmit={handleSubmit}>
      {generalError && <p role="alert">{generalError}</p>}

      <EmailInput
        value={email}
        onChange={setEmail}
        error={fieldErrors.email?.[0]}
      />

      <PasswordInput
        value={password}
        onChange={setPassword}
        error={fieldErrors.password?.[0]}
      />

      <button type="submit" disabled={isSubmitting}>
        {isSubmitting ? "ログイン中..." : "ログイン"}
      </button>
    </form>
  );
}
