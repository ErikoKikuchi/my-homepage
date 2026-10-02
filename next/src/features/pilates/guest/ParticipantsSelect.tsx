import { useId } from "react";
import styles from "./ParticipantsSelect.module.css";

interface ParticipantsSelectProps {
  label?: string;
  value: string;
  onChange: (value: string) => void;
  error?: string;
  className?: string;
}
const PARTICIPANTS = ["1", "2", "3", "4"] as const;

export default function ParticipantsSelect({
  label = "参加人数",
  value,
  onChange,
  error,
  className,
}: ParticipantsSelectProps) {
  const participantsId = useId();
  const errorId = `${participantsId}-error`;

  return (
    <div className={`${styles.participantsSelectBlock} ${className ?? ""}`}>
      <div className={styles.row}>
        <label htmlFor={participantsId}>{label}</label>
        <select
          id={participantsId}
          value={value}
          onChange={(e) => onChange(e.target.value)}
          aria-invalid={error ? true : undefined}
          aria-describedby={error ? errorId : undefined}
          className={styles.participantsSelect}
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
