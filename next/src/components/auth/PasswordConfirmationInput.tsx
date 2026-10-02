import { useState, useId } from "react";
import styles from "./PasswordConfirmationInput.module.css";
import { Eye, EyeOff } from "lucide-react";

interface PasswordConfirmationProps {
  value: string;
  onChange: (value: string) => void;
  error?: string;
  className?: string;
  label?: string;
  autoComplete?: string;
}

export default function PasswordConfirmationInput({
  label = "パスワード確認",
  value,
  onChange,
  error,
  className,
  autoComplete = "new-password",
}: PasswordConfirmationProps) {
  const [isVisible, setIsVisible] = useState(false);
  const passwordConfirmationId = useId();
  const errorId = `${passwordConfirmationId}-error`;

  return (
    <div className={`${styles.passwordBlock} ${className ?? ""}`}>
      <div className={styles.row}>
        <label htmlFor={passwordConfirmationId}>{label}</label>
        <div className={styles.inputWrapper}>
          <input
            id={passwordConfirmationId}
            type={isVisible ? "text" : "password"}
            name="passwordConfirmation"
            autoComplete={autoComplete}
            value={value}
            onChange={(e) => onChange(e.target.value)}
            aria-invalid={error ? true : undefined}
            aria-describedby={error ? errorId : undefined}
            className={styles.passwordConfirmationInput}
          />
          <button
            type="button"
            onClick={() => setIsVisible((prev) => !prev)}
            className={styles.toggleButton}
            aria-label={
              isVisible ? "パスワードを非表示にする" : "パスワードを表示する"
            }
          >
            {isVisible ? (
              <EyeOff size={20} aria-hidden="true" />
            ) : (
              <Eye size={20} aria-hidden="true" />
            )}
          </button>
        </div>
      </div>
      {error && (
        <p id={errorId} className={styles.errorText} role="alert">
          {error}
        </p>
      )}
    </div>
  );
}
