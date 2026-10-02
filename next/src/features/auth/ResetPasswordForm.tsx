"use client";

import { useState, type SubmitEvent } from "react";
import { resetPassword, AuthApiError } from "@/lib/api/auth/authApi";
import EmailInput from "@/components/auth/EmailInput";
import PasswordInput from "@/components/auth/PasswordInput";
import PasswordConfirmationInput from "@/components/auth/PasswordConfirmationInput";
import styles from "./ResetPasswordForm.module.css";
import LinkButton from "@/components/ui/LinkButton/LinkButton";

interface ResetPasswordFormProps {
  token: string;
  initialEmail?: string;
}

export function ResetPasswordForm({
  token,
  initialEmail = "",
}: ResetPasswordFormProps) {
  const [email, setEmail] = useState(initialEmail);
  const [password, setPassword] = useState("");
  const [passwordConfirmation, setPasswordConfirmation] = useState("");
  const [fieldErrors, setFieldErrors] = useState<Record<string, string[]>>({});
  const [generalError, setGeneralError] = useState<string | null>(null);
  const [successMessage, setSuccessMessage] = useState<string | null>(null);
  const [isSubmitting, setIsSubmitting] = useState(false);

  const handleSubmit = async (e: SubmitEvent) => {
    e.preventDefault();
    setFieldErrors({});
    setGeneralError(null);
    setSuccessMessage(null);
    setIsSubmitting(true);

    try {
      const result = await resetPassword({
        token,
        email,
        password,
        passwordConfirmation,
      });
      setSuccessMessage(result.message);
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
      <form onSubmit={handleSubmit} className={styles.resetPasswordForm}>
        {generalError && <p role="alert">{generalError}</p>}
        {successMessage && <p role="status">{successMessage}</p>}

        <EmailInput
          value={email}
          onChange={setEmail}
          error={fieldErrors.email?.[0]}
        />

        <PasswordInput
          value={password}
          onChange={setPassword}
          autoComplete="new-password"
          error={fieldErrors.password?.[0]}
        />

        <PasswordConfirmationInput
          value={passwordConfirmation}
          onChange={setPasswordConfirmation}
        />

        <button
          type="submit"
          disabled={isSubmitting}
          className={styles.submitButton}
        >
          {isSubmitting ? "変更中..." : "パスワードを変更"}
        </button>
      </form>
      <div className={styles.buttonWrapper}>
        <div className={styles.buttonGroup}>
          <p className={styles.linkDescription}>ログインの方はこちらへ</p>
          <div className={styles.registerGroup}>
            <LinkButton
              className={styles.pilatesLogin}
              href="/auth/pilates/login"
              variant="primary"
            >
              ピラティスのログインはこちら
            </LinkButton>
          </div>
          <LinkButton
            className={styles.thinkMotionLogin}
            href="/auth/thinkmotion/login"
            variant="outline"
          >
            ThinkMotionのログインはこちら
          </LinkButton>
        </div>
      </div>
    </div>
  );
}
