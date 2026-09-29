"use client";

import { useState, type SubmitEvent } from "react";
import { useRouter } from "next/navigation";
import { register, AuthApiError } from "@/lib/api/auth/authApi";
import NameInput from "./NameInput";
import EmailInput from "@/components/auth/EmailInput";
import PasswordInput from "@/components/auth/PasswordInput";
import PasswordConfirmationInput from "./PasswordConfirmationInput";
import styles from "./RegisterForm.module.css";
import LinkButton from "../ui/LinkButton/LinkButton";
import { RegisterCredentials } from "@/types/auth/auth";

export function RegisterForm() {
  const router = useRouter();
  const [name, setName] = useState("");
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [passwordConfirmation, setPasswordConfirmation] = useState("");
  const [service, setService] = useState<RegisterCredentials["service"] | null>(
    null,
  );
  const [fieldErrors, setFieldErrors] = useState<Record<string, string[]>>({});
  const [generalError, setGeneralError] = useState<string | null>(null);
  const [isSubmitting, setIsSubmitting] = useState(false);

  const handleSubmit = async (e: SubmitEvent) => {
    e.preventDefault();
    setFieldErrors({});
    setGeneralError(null);
    if (!service) {
      setGeneralError("サービスを選択してください。");
      return;
    }
    setIsSubmitting(true);

    try {
      const result = await register({
        name,
        email,
        password,
        passwordConfirmation,
        service,
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
      <form onSubmit={handleSubmit} className={styles.registerForm}>
        {generalError && <p role="alert">{generalError}</p>}

        <NameInput
          value={name}
          onChange={setName}
          error={fieldErrors.name?.[0]}
        />
        <p className={styles.nameAnnounce}>
          ＊ピラティスをご利用の方はフルネームでご登録ください。
        </p>
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

        <PasswordConfirmationInput
          value={passwordConfirmation}
          onChange={setPasswordConfirmation}
          error={fieldErrors.passwordConfirmation?.[0]}
        />
        <div className={styles.radioButtonGroup}>
          <label>
            <input
              type="radio"
              name="service"
              value="pilates"
              checked={service === "pilates"}
              onChange={() => setService("pilates")}
              required
              className={styles.radioButton}
            />
            Pilates（ピラティス予約）
          </label>
          <label>
            <input
              type="radio"
              name="service"
              value="thinkmotion"
              checked={service === "thinkmotion"}
              onChange={() => setService("thinkmotion")}
              required
              className={styles.radioButton}
            />
            ThinkMotion（医療従事者向けプラットフォーム）
          </label>
          <p className={styles.announce}>
            ＊両方ご利用希望の方は管理者までご連絡ください。
          </p>
        </div>
        <button
          type="submit"
          disabled={isSubmitting}
          className={styles.registerButton}
        >
          {isSubmitting ? "新規登録中..." : "新規登録"}
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
