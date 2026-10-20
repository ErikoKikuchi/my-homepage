import PilatesCalendar from "@/features/pilates/guest/PilatesCalendar";
import styles from "./page.module.css";

export default function ReservationPage() {
  const now = new Date();
  const initialMonth = `${now.getFullYear()}-${String(now.getMonth() + 1).padStart(2, "0")}`;

  return (
    <div className={styles.main}>
      <div className={styles.pilatesCalendar}>
        <PilatesCalendar initialMonth={initialMonth} />
      </div>
      <div className={styles.explanation}>
        <p className={styles.mark}>〇：空きあり</p>
        <p className={styles.mark}>満：公開枠は満席</p>
        <p className={styles.mark}>△：直接お問い合わせ</p>
        <p className={styles.mark}>受付不可：予約・ご相談の受付なし</p>
      </div>
      <div className={styles.timeSelect}>
        <p className={styles.notice}>＊レッスンは60分間です</p>
      </div>
    </div>
  );
}
