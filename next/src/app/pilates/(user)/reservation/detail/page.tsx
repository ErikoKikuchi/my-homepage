import styles from "./page.module.css";
import PageHeader from "@/components/public/common/PageHeader";
import ReservationForm from "@/features/pilates/user/reservation/ReservationForm";
import { getReservationDetail } from "@/lib/api/pilates/reservationServer";
import { redirect } from "next/navigation";

type Props = {
  searchParams: Promise<{ date?: string; start?: string }>;
};

export default async function ReservationDetailPage({ searchParams }: Props) {
  const { date, start } = await searchParams;

  if (!date || !start) {
    redirect("/pilates/reservation");
  }
  const detail = await getReservationDetail(date, start);
  return (
    <>
      <div className={styles.main}>
        <PageHeader heading={"予約フォーム入力画面"}></PageHeader>
      </div>
      <ReservationForm detail={detail} />
    </>
  );
}
