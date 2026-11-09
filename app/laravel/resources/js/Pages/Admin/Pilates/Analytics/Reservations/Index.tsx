import LinkButton from "@/Components/Admin/UI/LinkButton/LinkButton";
import AdminPage from "../../AdminPage";
import styles from "../../AdminPage.module.css";
import { ReservationHistoryTable, type HistoryPage } from "../../Reservations/Index";
import { admin } from "../../schedule";

export default function ReservationAnalyticsIndex({ reservations }: { reservations: HistoryPage }) {
    return (
        <AdminPage heading="予約分析">
            <div className={styles.stack}>
                <nav className={styles.actions} aria-label="関連ページ">
                    <LinkButton href={`${admin}/analytics`} variant="outline">分析トップに戻る</LinkButton>
                    <LinkButton href={`${admin}/reservations`} variant="text">予約履歴を見る</LinkButton>
                </nav>
                <section className={styles.stack}>
                    <h2>キャンセル一覧</h2>
                    <p>操作担当者は、画面でキャンセルを処理した人です。</p>
                    <ReservationHistoryTable reservations={reservations}
                        caption="キャンセル処理日時が新しい順"
                        emptyMessage="キャンセルされた予約はありません。" />
                </section>
            </div>
        </AdminPage>
    );
}
