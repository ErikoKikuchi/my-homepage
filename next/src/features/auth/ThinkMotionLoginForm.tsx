"use client";

import { useState } from "react";
import { useRouter } from "next/navigation";
import { login, AuthApiError } from "@/lib/api/auth/authApi";
import EmailInput from "@/components/auth/EmailInput";
import PasswordInput from "@/components/auth/PasswordInput";
import LinkButton from "@/components/ui/LinkButton/LinkButton";
import styles from "./ThinkMotionLoginForm.module.css";

export function ThinkMotionLoginForm() {
  const router = useRouter();
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [fieldErrors, setFieldErrors] = useState<Record<string, string[]>>({});
  const [generalError, setGeneralError] = useState<string | null>(null);
  const [isSubmitting, setIsSubmitting] = useState(false);

  const handleSubmit = async (e: React.FormEvent<HTMLFormElement>) => {
    e.preventDefault();
    setFieldErrors({});
    setGeneralError(null);
    setIsSubmitting(true);

    try {
      const result = await login("thinkmotion", {
        email,
        password,
        remember: false,
      });
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
    <div className={styles.main}>
      <form onSubmit={handleSubmit} className={styles.thinkMotionLoginForm}>
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

        <button
          type="submit"
          disabled={isSubmitting}
          className={styles.loginButton}
        >
          {isSubmitting ? "ログイン中..." : "ログイン"}
        </button>
      </form>
      <div className={styles.buttonWrapper}>
        <div className={styles.buttonGroup}>
          <div className={styles.registerGroup}>
            <p className={styles.linkDescription}>
              アカウントをお持ちでない方は
            </p>
            <LinkButton
              className={styles.register}
              href="/auth/register"
              variant="text"
            >
              新規登録
            </LinkButton>
          </div>
          <LinkButton
            external
            className={styles.resetPassword}
            href={`${process.env.NEXT_PUBLIC_LARAVEL_URL}/forgot-password?from=pilates`}
            variant="outline"
          >
            パスワードをお忘れの方はこちら
          </LinkButton>
        </div>
      </div>
    </div>
  );
}
