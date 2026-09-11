import { useState } from "react";
import styles from "./PasswordInput.module.css";

interface PasswordProps {
  value: string;
  onChange: (value: string) => void;
  error?: string;
  className?: string;
}

export default function PasswordInput({
  value,
  onChange,
  error,
  className,
}: PasswordProps) {
  const [isVisible, setIsVisible] = useState(false);
  return (
    <div className={`${styles.passwordBlock} ${className ?? ""}`}>
      <div className={styles.inputWrapper}>
        <input
          type={isVisible ? "text" : "password"}
          name="password"
          value={value}
          onChange={(e) => onChange(e.target.value)}
          className={styles.PasswordInput}
        />
        <button
          type="button"
          onClick={() => setIsVisible((prev) => !prev)}
          className={styles.toggleButton}
          aria-label={
            isVisible ? "パスワードを非表示にする" : "パスワードを表示する"
          }
        >
          {isVisible ? "隠す" : "表示"}
        </button>
      </div>
      {error && (
        <p className={styles.errorText} role="alert">
          {error}
        </p>
      )}
    </div>
  );
}
