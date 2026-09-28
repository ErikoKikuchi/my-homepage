"use client";

import { useState, type SubmitEvent } from "react";
import {
  resendVerificationEmail,
  AuthApiError,
} from "@/lib/api/auth/authClient";
import styles from "./ForgotPasswordForm.module.css";
import LinkButton from "../ui/LinkButton/LinkButton";

export function EmailVerify() {
  const [generalError, setGeneralError] = useState<string | null>(null);
  const [successMessage, setSuccessMessage] = useState<string | null>(null);
  const [isSubmitting, setIsSubmitting] = useState(false);
  // 開発環境のみ設定する(本番では未設定にしてボタンを出さない)
  const mailInboxUrl = process.env.NEXT_PUBLIC_MAIL_INBOX_URL;

  const handleSubmit = async (e: SubmitEvent) => {
    e.preventDefault();
    setGeneralError(null);
    setSuccessMessage(null);
    setIsSubmitting(true);

    try {
      const result = await resendVerificationEmail();
      setSuccessMessage(result.message);
    } catch (error) {
      if (error instanceof AuthApiError) {
        if (error.status === 429) {
          setGeneralError(
            "再送の回数が上限に達しました。しばらくしてからお試しください。",
          );
        } else if ("errors" in error.body) {
          setGeneralError(error.body.message);
        } else {
          setGeneralError(
            "送信に失敗しました。時間をおいて再度お試しください。",
          );
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
      <form onSubmit={handleSubmit} className={styles.emailVerifyForm}>
        {generalError && <p role="alert">{generalError}</p>}
        {successMessage && <p role="status">{successMessage}</p>}

        {mailInboxUrl && (
          <LinkButton
            className={styles.submitButton}
            href={mailInboxUrl}
            variant="outline"
          >
            {" "}
            メールを開く
          </LinkButton>
        )}

        <button
          type="submit"
          disabled={isSubmitting}
          className={styles.submitButton}
        >
          {isSubmitting ? "送信中..." : "認証メールを再送する"}
        </button>
      </form>
    </div>
  );
}
