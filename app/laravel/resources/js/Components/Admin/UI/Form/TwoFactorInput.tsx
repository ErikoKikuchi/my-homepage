import styles from "./TwoFactorInput.module.css";
import { useId } from "react";

interface TwoFactorProps {
  value: string;
  onChange: (value: string) => void;
  error?: string;
  className?: string;
  label?: string;
  autoComplete?: string;
  placeholder?: string;
}

export default function TwoFactorInput({
  value,
  label = "二要素認証",
  autoComplete = "one-time-code",
  onChange,
  error,
  className,
  placeholder,
}: TwoFactorProps) {
  const twoFactorId = useId();
  const errorId = `${twoFactorId}-error`;
  return (
    <div className={`${styles.twoFactorBlock} ${className ?? ""}`}>
      <label htmlFor={twoFactorId}>{label}</label>
      <input
        id={twoFactorId}
        type="text"
        name="TwoFactor"
        value={value}
        placeholder={placeholder}
        autoComplete={autoComplete}
        aria-invalid={error ? true : undefined}
        aria-describedby={error ? errorId : undefined}
        onChange={(e) => onChange(e.target.value)}
        className={styles.twoFactorInput}
      />
      {error && (
        <p id={errorId} className={styles.errorText} role="alert">
          {error}
        </p>
      )}
    </div>
  );
}
