import PageHeader from "@/components/public/common/PageHeader";
import styles from "./page.module.css";

export default async function PastReservation() {
  return (
    <>
      <div className={styles.main}>
        <PageHeader heading={"予約履歴"}></PageHeader>
      </div>
    </>
  );
}
