import styles from "./EmailInput.module.css";

interface EmailProps {
  value: string;
  onChange: (value: string) => void;
  error?: string;
  className?: string;
}

export default function EmailInput({
  value,
  onChange,
  error,
  className,
}: EmailProps) {
  return (
    <div className={`${styles.emailBlock} ${className ?? ""}`}>
      <input
        type="email"
        name="email"
        value={value}
        onChange={(e) => onChange(e.target.value)}
        className={styles.emailInput}
      />
      {error && (
        <p className={styles.errorText} role="alert">
          {error}
        </p>
      )}
    </div>
  );
}
