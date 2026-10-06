import styles from "./PhoneInput.module.css";
import { useId } from "react";

interface PhoneProps {
  label?: string;
  hint?: string;
  value: string;
  onChange: (value: string) => void;
  placeholder?: string;
  required?: boolean;
  error?: string;
  className?: string;
  autoComplete?: string;
}

export default function PhoneInput({
  value,
  onChange,
  label = "電話番号",
  hint,
  placeholder,
  required,
  autoComplete = "tel",
  error,
  className,
}: PhoneProps) {
  const id = useId();
  const hintId = `${id}-hint`;
  const errorId = `${id}-error`;
  const describedBy =
    [hint && hintId, error && errorId].filter(Boolean).join(" ") || undefined;

  return (
    <div className={`${styles.phoneBlock} ${className ?? ""}`}>
      <div className={styles.row}>
        <label htmlFor={id} className={styles.label}>
          {label}
        </label>
        <input
          id={id}
          type="tel"
          inputMode="tel"
          autoComplete={autoComplete}
          placeholder={placeholder}
          value={value}
          onChange={(e) => onChange(e.target.value)}
          aria-required={required}
          aria-invalid={error ? true : undefined}
          aria-describedby={describedBy}
          className={styles.phoneInput}
        />
      </div>
      {hint && (
        <p id={hintId} className={styles.hintText}>
          {hint}
        </p>
      )}
      {error && (
        <p id={errorId} className={styles.errorText} role="alert">
          {error}
        </p>
      )}
    </div>
  );
}
