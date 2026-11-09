import LinkButton from "@/Components/Admin/UI/LinkButton/LinkButton";
import AdminPage from "../AdminPage";
import styles from "../AdminPage.module.css";
import historyStyles from "./Index.module.css";
import { admin } from "../schedule";

type HistoryEntry = {
    id: string;
    clientName: string | null;
    scheduledAt: string | null;
    locationName: string | null;
    status: string;
    cancelledAt: string | null;
    cancelledBy: string | null;
    rescheduledFrom: string | null;
    rescheduledTo: string | null;
};

export type HistoryPage = {
    data: HistoryEntry[];
    prev_page_url: string | null;
    next_page_url: string | null;
    current_page: number;
    last_page: number;
};

const cancellationOperators: Record<string, string> = {
    admin: "管理者",
    client: "お客さま",
};

export default function ReservationsIndex({ reservations }: { reservations: HistoryPage }) {
    return (
        <AdminPage heading="予約履歴">
            <div className={styles.stack}>
                <div className={styles.actions}>
                    <LinkButton href={`${admin}/calendar`} variant="outline">予約カレンダーに戻る</LinkButton>
                </div>
                <ReservationHistoryTable reservations={reservations}
                    caption="過去の予約とキャンセル・振替の記録"
                    emptyMessage="表示できる予約履歴はありません。" />
            </div>
        </AdminPage>
    );
}

export function ReservationHistoryTable({ reservations, caption, emptyMessage }: {
    reservations: HistoryPage; caption: string; emptyMessage: string;
}) {
    return (
        <>
            {reservations.data.length === 0 ? (
                <p>{emptyMessage}</p>
            ) : (
                <div className={historyStyles.tableContainer}>
                    <table className={historyStyles.table}>
                        <caption>{caption}</caption>
                        <thead>
                            <tr>
                                <th scope="col">予約日時</th>
                                <th scope="col">クライアント</th>
                                <th scope="col">会場</th>
                                <th scope="col">状態</th>
                                <th scope="col">変更の記録</th>
                            </tr>
                        </thead>
                        <tbody>
                            {reservations.data.map((reservation) => (
                                <tr key={reservation.id}>
                                    <td>{reservation.scheduledAt ?? "—"}</td>
                                    <td>{reservation.clientName ?? "—"}</td>
                                    <td>{reservation.locationName ?? "未定"}</td>
                                    <td>{reservation.status}</td>
                                    <td>
                                        {reservation.rescheduledFrom && <div>振替元：{reservation.rescheduledFrom}</div>}
                                        {reservation.rescheduledTo && <div>振替先：{reservation.rescheduledTo}</div>}
                                        {reservation.cancelledAt && (
                                            <div>
                                                {reservation.cancelledAt}に取消
                                                {reservation.cancelledBy && `（操作：${cancellationOperators[reservation.cancelledBy] ?? "不明"}）`}
                                            </div>
                                        )}
                                        {!reservation.rescheduledFrom && !reservation.rescheduledTo && !reservation.cancelledAt && "—"}
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
            )}
            {reservations.last_page > 1 && (
                <nav className={styles.actions} aria-label="ページ切り替え">
                    {reservations.prev_page_url && <LinkButton href={reservations.prev_page_url} variant="outline">前へ</LinkButton>}
                    <span>{reservations.current_page} / {reservations.last_page}</span>
                    {reservations.next_page_url && <LinkButton href={reservations.next_page_url} variant="outline">次へ</LinkButton>}
                </nav>
            )}
        </>
    );
}
