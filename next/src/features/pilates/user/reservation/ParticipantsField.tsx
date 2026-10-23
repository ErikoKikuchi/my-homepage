import { useId } from "react";
import styles from "./ParticipantsField.module.css";

interface ParticipantsFieldProps {
  label?: string;
  value: string;
  onChange: (value: string) => void;
  error?: string;
  className?: string;
}
const PARTICIPANTS = ["1", "2", "3", "4"] as const;

export default function ParticipantsField({
  label = "3、参加人数",
  value,
  onChange,
  error,
  className,
}: ParticipantsFieldProps) {
  const participantsId = useId();
  const errorId = `${participantsId}-error`;

  return (
    <div className={`${styles.participantsFieldBlock} ${className ?? ""}`}>
      <div className={styles.row}>
        <label htmlFor={participantsId} className={styles.label}>
          {label}
        </label>
        <select
          id={participantsId}
          value={value}
          onChange={(e) => onChange(e.target.value)}
          aria-invalid={error ? true : undefined}
          aria-describedby={error ? errorId : undefined}
          className={styles.participantsField}
        >
          {PARTICIPANTS.map((participants) => (
            <option value={participants} key={participants}>
              {participants}名
            </option>
          ))}
        </select>
      </div>
      {error && (
        <p id={errorId} className={styles.errorText} role="alert">
          {error}
        </p>
      )}
    </div>
  );
}
