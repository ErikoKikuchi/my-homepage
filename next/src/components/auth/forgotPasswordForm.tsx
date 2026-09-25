"use client";

import { useState, type SubmitEvent } from "react";
import { forgotPassword, AuthApiError } from "@/lib/api/auth/authClient";
import EmailInput from "@/components/auth/EmailInput";
import styles from "./forgotPassword.module.css";

export function ForgotPassWordForm() {
  const [email, setEmail] = useState("");
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
      const result = await forgotPassword({
        email,
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
      <form onSubmit={handleSubmit} className={styles.forgotPasswordForm}>
        {generalError && <p role="alert">{generalError}</p>}
        {successMessage && <p role="status">{successMessage}</p>}

        <EmailInput
          value={email}
          onChange={setEmail}
          error={fieldErrors.email?.[0]}
        />

        <button
          type="submit"
          disabled={isSubmitting}
          className={styles.submitButton}
        >
          {isSubmitting ? "送信中..." : "送信"}
        </button>
      </form>
    </div>
  );
}
