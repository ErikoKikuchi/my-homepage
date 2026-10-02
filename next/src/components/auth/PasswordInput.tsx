import { useState, useId } from "react";
import styles from "./PasswordInput.module.css";
import { Eye, EyeOff } from "lucide-react";

interface PasswordProps {
  value: string;
  onChange: (value: string) => void;
  error?: string;
  className?: string;
  label?: string;
  autoComplete?: string;
}

export default function PasswordInput({
  label = "パスワード",
  value,
  onChange,
  error,
  className,
  autoComplete = "current-password",
}: PasswordProps) {
  const [isVisible, setIsVisible] = useState(false);
  const passwordId = useId();
  const errorId = `${passwordId}-error`;
  return (
    <div className={`${styles.passwordBlock} ${className ?? ""}`}>
      <div className={styles.row}>
        <label htmlFor={passwordId}>{label}</label>
        <div className={styles.inputWrapper}>
          <input
            id={passwordId}
            type={isVisible ? "text" : "password"}
            name="password"
            value={value}
            autoComplete={autoComplete}
            onChange={(e) => onChange(e.target.value)}
            aria-invalid={error ? true : undefined}
            aria-describedby={error ? errorId : undefined}
            className={styles.passwordInput}
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
