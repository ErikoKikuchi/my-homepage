import axios from "axios";
import { useState } from "react";
import LinkButton from "@/Components/Admin/UI/LinkButton/LinkButton";
import AdminPage, { FieldError } from "../AdminPage";
import styles from "../AdminPage.module.css";
import { admin, type NamedOption } from "../schedule";

type Session = { id: string; session_date: string; is_draft: boolean };
type SessionPage = {
    data: Session[];
    current_page: number;
    last_page: number;
    prev_page_url: string | null;
    next_page_url: string | null;
};

type Client = NamedOption & {
    has_unpaid_fee: boolean;
};

const sessionDate = new Intl.DateTimeFormat("ja-JP", {
    dateStyle: "medium",
    timeStyle: "short",
    timeZone: "Asia/Tokyo",
});

export default function SessionsIndex({
    client,
    sessions,
    todayUnrecorded,
}: {
    client: Client;
    sessions: SessionPage;
    todayUnrecorded: { id: string; startTime: string }[];
}) {
    const clientUrl = `${admin}/clients/${client.id}`;
    const [unpaid, setUnpaid] = useState(client.has_unpaid_fee);
    const [updatingUnpaid, setUpdatingUnpaid] = useState(false);
    const [unpaidError, setUnpaidError] = useState("");

    async function updateUnpaid(checked: boolean) {
        setUpdatingUnpaid(true);
        setUnpaidError("");
        try {
            await axios.patch(
                clientUrl,
                { has_unpaid_fee: checked },
                { headers: { Accept: "application/json" } },
            );
            setUnpaid(checked);
        } catch {
            setUnpaidError("未払いの状態を更新できませんでした。");
        } finally {
            setUpdatingUnpaid(false);
        }
    }

    return (
        <AdminPage heading={`${client.name}さんのセッション記録一覧`}>
            <div className={styles.stack}>
                <h2>基本情報</h2>
                <div className={styles.actions}>
                    <LinkButton href={clientUrl} variant="text">
                        クライアント詳細へ
                    </LinkButton>
                </div>
                <section className={styles.stack}>
                    <h2>会計管理</h2>
                    <div className={styles.actions}>
                        <LinkButton
                            href={`${clientUrl}/accounting`}
                            variant="outline"
                        >
                            支払い記録
                        </LinkButton>
                        <label>
                            <input
                                type="checkbox"
                                checked={unpaid}
                                disabled={updatingUnpaid}
                                onChange={(event) =>
                                    void updateUnpaid(event.target.checked)
                                }
                            />
                            未払いあり
                        </label>
                    </div>
                    <FieldError message={unpaidError} />
                </section>
                <section className={styles.stack}>
                    <h2>{client.name}さんのセッション記録</h2>
                    {sessions.data.length === 0 &&
                    todayUnrecorded.length === 0 ? (
                        <p>セッション記録はありません。</p>
                    ) : (
                        <ul className={styles.stack}>
                            {todayUnrecorded.map((reservation) => (
                                <li
                                    key={reservation.id}
                                    className={styles.card}
                                >
                                    <span>
                                        本日 {reservation.startTime.slice(0, 5)}{" "}
                                        · 未記録
                                    </span>
                                    <LinkButton
                                        href={`${clientUrl}/sessions/create?reservation_id=${encodeURIComponent(reservation.id)}`}
                                        variant="outline"
                                    >
                                        記録する
                                    </LinkButton>
                                </li>
                            ))}
                            {sessions.data.map((session) => (
                                <li key={session.id} className={styles.card}>
                                    <time dateTime={session.session_date}>
                                        {sessionDate.format(
                                            new Date(session.session_date),
                                        )}
                                    </time>
                                    <span>
                                        {session.is_draft
                                            ? "下書き"
                                            : "保存済み"}
                                    </span>
                                </li>
                            ))}
                        </ul>
                    )}
                    {sessions.last_page > 1 && (
                        <nav
                            className={styles.actions}
                            aria-label="セッション記録のページ切り替え"
                        >
                            {sessions.prev_page_url && (
                                <LinkButton
                                    href={sessions.prev_page_url}
                                    variant="outline"
                                >
                                    前へ
                                </LinkButton>
                            )}
                            <span>
                                {sessions.current_page} / {sessions.last_page}
                            </span>
                            {sessions.next_page_url && (
                                <LinkButton
                                    href={sessions.next_page_url}
                                    variant="outline"
                                >
                                    次へ
                                </LinkButton>
                            )}
                        </nav>
                    )}
                </section>
                <section className={styles.stack}>
                    <h2>TrainingLog</h2>
                    <div className={styles.actions}>
                        <LinkButton
                            href={`${clientUrl}/training-logs`}
                            variant="outline"
                        >
                            TrainingLogを開く
                        </LinkButton>
                    </div>
                </section>
                <div className={styles.actions}>
                    <LinkButton href={`${admin}/sessions`} variant="outline">
                        クライアントを選ぶ
                    </LinkButton>
                </div>
            </div>
        </AdminPage>
    );
}
