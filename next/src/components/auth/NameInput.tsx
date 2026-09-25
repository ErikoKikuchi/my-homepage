import styles from "./NameInput.module.css";

interface NameProps {
  value: string;
  onChange: (value: string) => void;
  error?: string;
  className?: string;
}

export default function NameInput({
  value,
  onChange,
  error,
  className,
}: NameProps) {
  return (
    <div className={`${styles.nameBlock} ${className ?? ""}`}>
      <div className={styles.row}>
        <label>名前</label>
        <input
          type="string"
          name="string"
          value={value}
          onChange={(e) => onChange(e.target.value)}
          className={styles.nameInput}
        />
      </div>
      {error && (
        <p className={styles.errorText} role="alert">
          {error}
        </p>
      )}
    </div>
  );
}
