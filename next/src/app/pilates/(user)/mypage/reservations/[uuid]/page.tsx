import { getBookingDetail } from "@/lib/api/pilates/reservationServer";
import LineLink from "@/components/ui/Line/LineLink";
import styles from "@/app/pilates/(user)/mypage/reservations/[uuid]/page.module.css";
import PageHeader from "@/components/public/common/PageHeader";
import BookingDetailClient from "@/features/pilates/user/reservation/BookingDetailClient";

type PageProps = {
  params: Promise<{
    uuid: string;
  }>;
};

export default async function BookingDetailPage({ params }: PageProps) {
  const { uuid } = await params;
  const data = await getBookingDetail(uuid);
  return (
    <>
      <PageHeader heading={"予約内容の確認/キャンセル"}></PageHeader>
      <main className={styles.main}>
        <table className={styles.table}>
          <tbody>
            <tr className={styles.row}>
              <th scope="row">日付</th>
              <td>{data.booking.date}</td>
            </tr>
            <tr className={styles.row}>
              <th scope="row">時間</th>
              <td>
                {data.booking.start_time} ～ {data.booking.end_time}
              </td>
            </tr>
            <tr className={styles.row}>
              <th scope="row">場所</th>
              <td>{data.booking.location}</td>
            </tr>
            <tr className={styles.row}>
              <th scope="row">参加人数</th>
              <td>{data.booking.participants}名</td>
            </tr>
            <tr className={styles.row}>
              <th scope="row">備考</th>
              <td>{data.booking.note ?? "なし"}</td>
            </tr>
          </tbody>
        </table>

        {data.isPastCutoff ? (
          <div>
            <p>
              前日正午を過ぎてからのキャンセルはLINEにて承っております。
              キャンセル料500円が発生いたします。
            </p>
            <LineLink></LineLink>
          </div>
        ) : (
          <BookingDetailClient uuid={uuid} />
        )}
      </main>
    </>
  );
}
