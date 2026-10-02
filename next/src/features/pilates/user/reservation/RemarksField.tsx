import styles from "./ReservationNotesFields.module.css";

interface ReservationNotesFieldsProps {
  participantNames: string;
  remarks: string;
  onParticipantNamesChange: (value: string) => void;
  onRemarksChange: (value: string) => void;
}

export default function ReservationNotesFields({
  participantNames,
  remarks,
  onParticipantNamesChange,
  onRemarksChange,
}: ReservationNotesFieldsProps) {
  return (
    <div className={styles.reservationNotes}>
      <div>
        <label htmlFor="participantNames">参加者名(任意)</label>
        <p id="participantNamesHint">
          複数名で参加する場合は、参加者名をこちらにご記入ください。
        </p>
        <input
          id="participantNames"
          type="text"
          aria-describedby="participantNamesHint"
          value={participantNames}
          onChange={(e) => onParticipantNamesChange(e.target.value)}
        />
      </div>

      <div>
        <label htmlFor="remarks">備考(任意)</label>
        <p id="remarksHint">
          場所のご希望やお身体の状態など、事前にお伝えしたいことがあればご記入ください。
        </p>
        <textarea
          id="remarks"
          aria-describedby="remarksHint"
          value={remarks}
          onChange={(e) => onRemarksChange(e.target.value)}
        />
      </div>
    </div>
  );
}
