import { useState } from "react";
import styles from "./PasswordConfirmationInput.module.css";
import { Eye, EyeOff } from "lucide-react";

interface PasswordConfirmationProps {
  value: string;
  onChange: (value: string) => void;
  error?: string;
  className?: string;
}

export default function PasswordConfirmationInput({
  value,
  onChange,
  error,
  className,
}: PasswordConfirmationProps) {
  const [isVisible, setIsVisible] = useState(false);
  return (
    <div className={`${styles.passwordBlock} ${className ?? ""}`}>
      <div className={styles.row}>
        <label>パスワード確認</label>
        <div className={styles.inputWrapper}>
          <input
            type={isVisible ? "text" : "password"}
            name="passwordConfirmation"
            value={value}
            onChange={(e) => onChange(e.target.value)}
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
        <p className={styles.errorText} role="alert">
          {error}
        </p>
      )}
    </div>
  );
}
