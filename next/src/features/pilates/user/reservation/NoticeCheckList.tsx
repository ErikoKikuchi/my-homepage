import { Notice, Agreements, NoticeId } from "./ReservationNotices";
import { useId } from "react";
import styles from "./NoticeCheckList.module.css";

type NoticeCheckListProps = {
  notices: readonly Notice[];
  agreements: Agreements;
  onChange: (id: NoticeId, checked: boolean) => void;
  error?: string;
};

export function NoticeCheckList({
  notices,
  agreements,
  onChange,
  error,
}: NoticeCheckListProps) {
  const errorId = useId();

  return (
    <fieldset
      aria-describedby={error ? errorId : undefined}
      className={styles.noticeCheckList}
    >
      <legend className={styles.noticeLegend}>
        5、ご確認事項/キャンセルポリシー
      </legend>
      {notices.map((notice) => (
        <label key={notice.id}>
          <input
            type="checkbox"
            checked={agreements[notice.id]}
            onChange={(e) => onChange(notice.id, e.target.checked)}
            required
          />
          <span>{notice.text}</span>
        </label>
      ))}
      {error && (
        <p id={errorId} role="alert" className={styles.errorText}>
          {error}
        </p>
      )}
    </fieldset>
  );
}
