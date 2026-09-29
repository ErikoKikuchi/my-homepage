"use client";

import { useState, type SubmitEvent } from "react";
import styles from "./LogoutButton.module.css";
import { useRouter } from "next/navigation";
import { AuthApiError } from "@/lib/api/auth/authApi";

type Props = {
  logoutRequest: () => Promise<{ redirectTo: string }>;
  refresh: () => Promise<void>;
  loginPath: string; // 401時の遷移先
};

export function LogoutButton({ logoutRequest, refresh, loginPath }: Props) {
  const router = useRouter();
  const [generalError, setGeneralError] = useState<string | null>(null);
  const [isSubmitting, setIsSubmitting] = useState(false);

  const handleSubmit = async (e: SubmitEvent) => {
    e.preventDefault();
    setGeneralError(null);
    setIsSubmitting(true);

    try {
      const result = await logoutRequest();
      await refresh();
      router.push(result.redirectTo);
    } catch (error) {
      if (error instanceof AuthApiError) {
        if (error.status === 401) {
          // すでにログアウト済み
          await refresh();
          router.push(loginPath); // 実際のログインページのパスに合わせる
          return;
        }
        setGeneralError(error.body.message);
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
    <div>
      <form onSubmit={handleSubmit} className={styles.logoutForm}>
        {generalError && <p role="alert">{generalError}</p>}

        <button
          type="submit"
          disabled={isSubmitting}
          className={styles.logoutButton}
        >
          {isSubmitting ? "ログアウト中..." : "ログアウト"}
        </button>
      </form>
    </div>
  );
}
