import styles from "./TwoFactorInput.module.css";

interface TwoFactorProps {
  value: string;
  onChange: (value: string) => void;
  error?: string;
  className?: string;
}

export default function TwoFactorInput({
  value,
  onChange,
  error,
  className,
}: TwoFactorProps) {
  return (
    <div className={`${styles.twoFactorBlock} ${className ?? ""}`}>
      <input
        type="text"
        inputMode="numeric"
        pattern="[0-9]*"
        maxLength={6}
        name="TwoFactor"
        value={value}
        onChange={(e) => onChange(e.target.value)}
        className={styles.twoFactorInput}
      />
      {error && (
        <p className={styles.errorText} role="alert">
          {error}
        </p>
      )}
    </div>
  );
}
