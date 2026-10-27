import styles from "./EmailInput.module.css";
import { useId } from "react";

interface EmailProps {
  label?: string;
  value: string;
  onChange: (value: string) => void;
  error?: string;
  className?: string;
  placeholder?: string;
  autoComplete?: string;
}

export default function EmailInput({
  label = "メールアドレス",
  value,
  onChange,
  error,
  className,
  placeholder,
  autoComplete = "email",
}: EmailProps) {
  const emailId = useId();
  const errorId = `${emailId}-error`;

  return (
    <div className={`${styles.emailBlock} ${className ?? ""}`}>
      <div className={styles.row}>
        <label htmlFor={emailId}>{label}</label>
        <input
          id={emailId}
          type="email"
          name="email"
          value={value}
          autoComplete={autoComplete}
          placeholder={placeholder}
          onChange={(e) => onChange(e.target.value)}
          aria-invalid={error ? true : undefined}
          aria-describedby={error ? errorId : undefined}
          className={styles.emailInput}
        />
      </div>
      {error && (
        <p id={errorId} className={styles.errorText} role="alert">
          {error}
        </p>
      )}
    </div>
  );
}
