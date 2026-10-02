import styles from "./page.module.css";
import PageHeader from "@/components/public/common/PageHeader";
import ReservationForm from "@/features/pilates/user/reservation/ReservationForm";

export default function ReservationDetailPage() {
  return (
    <>
      <div className={styles.main}>
        <PageHeader heading={"予約フォーム入力画面"}></PageHeader>
      </div>
      <ReservationForm></ReservationForm>
    </>
  );
}
