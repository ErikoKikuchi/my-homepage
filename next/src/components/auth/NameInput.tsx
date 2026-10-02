import styles from "./NameInput.module.css";
import { useId } from "react";

interface NameProps {
  label?: string;
  value: string;
  onChange: (value: string) => void;
  error?: string;
  className?: string;
  autoComplete?: string;
}

export default function NameInput({
  label = "名前入力",
  value,
  onChange,
  error,
  className,
  autoComplete = "name",
}: NameProps) {
  const nameId = useId();
  const errorId = `${nameId}-error`;
  return (
    <div className={`${styles.nameBlock} ${className ?? ""}`}>
      <div className={styles.row}>
        <label htmlFor={nameId}>{label}</label>
        <input
          id={nameId}
          type="text"
          name="name"
          autoComplete={autoComplete}
          value={value}
          onChange={(e) => onChange(e.target.value)}
          aria-invalid={error ? true : undefined}
          aria-describedby={error ? errorId : undefined}
          className={styles.nameInput}
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
