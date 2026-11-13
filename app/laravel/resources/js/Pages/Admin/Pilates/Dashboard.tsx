import { Link } from "@inertiajs/react";
import LinkButton from "@/Components/Admin/UI/LinkButton/LinkButton";
import PageHeader from "@/Components/Admin/UI/PageHeader";
import styles from "./Dashboard.module.css";
import { admin, dateInputValue, type NamedOption } from "./schedule";

type ReservationRow = {
    id: string;
    client_id: string;
    client: NamedOption;
    lesson_slot: { date: string; lesson_template: { start_time: string } };
};

type TodayReservation = ReservationRow & {
    pilates_session: { is_draft: boolean } | null;
};

type DraftSession = {
    id: string;
    client_id: string;
    client: NamedOption;
    session_date: string;
};

type DashboardProps = {
    pendingReservationCount: number;
    todayReservations: TodayReservation[];
    draftSession: DraftSession[];
    unFilledSession: ReservationRow[];
};

const pendingReservationsUrl = "/pilates/admin/reservations/pending";
const quickMenu = [
    { label: "会場確認待ち", href: pendingReservationsUrl },
    { label: "予約確認・代理予約", href: "/pilates/admin/calendar" },
    { label: "スケジュール管理", href: "/pilates/admin/lesson-slots" },
    { label: "クライアント管理", href: "/pilates/admin/clients" },
    { label: "セッション記録", href: "/pilates/admin/sessions" },
    { label: "ピラティス種目管理", href: "/pilates/admin/exercises" },
    { label: "会計管理", href: "/pilates/admin/accounting" },
    { label: "BodyMind管理", href: "/pilates/admin/training-logs" },
    { label: "場所管理", href: "/pilates/admin/location" },
    { label: "分析", href: "/pilates/admin/analytics" },
];

function reservationTime(reservation: ReservationRow) {
    return reservation.lesson_slot.lesson_template.start_time.slice(0, 5);
}

function SessionRow({ schedule, clientId, clientName, status, reservationId }: {
    schedule: string;
    clientId: string;
    clientName: string;
    status?: string;
    reservationId?: string;
}) {
    const sessionsUrl = `${admin}/clients/${clientId}/sessions`;
    const action = reservationId ? "記録する" : "記録一覧";
    const href = reservationId
        ? `${sessionsUrl}/create?reservation_id=${encodeURIComponent(reservationId)}`
        : sessionsUrl;

    return (
        <li className={styles.sessionRow}>
            <span>{schedule}　{clientName}</span>
            {status && <span>{status}</span>}
            <Link
                href={href}
                aria-label={`${schedule} ${clientName}のセッションを${action === "記録する" ? action : "記録一覧で見る"}`}
                className={styles.taskLink}
            >
                {action}
            </Link>
        </li>
    );
}

export default function Dashboard({ pendingReservationCount, todayReservations, draftSession, unFilledSession }: DashboardProps) {
    const todayUnrecorded = todayReservations.filter(({ pilates_session }) => pilates_session === null);
    const todayDrafts = todayReservations.filter(({ pilates_session }) => pilates_session?.is_draft);
    const sessionTasks = [
        { label: "今日の未記録", canCreate: true, rows: todayUnrecorded.map((reservation) => ({
            ...reservation, schedule: reservationTime(reservation),
        })) },
        { label: "今日の下書き", rows: todayDrafts.map((reservation) => ({
            ...reservation, schedule: reservationTime(reservation),
        })) },
        { label: "過去の未記録", canCreate: true, rows: unFilledSession.map((reservation) => ({
            ...reservation,
            schedule: `${dateInputValue(reservation.lesson_slot.date)} ${reservationTime(reservation)}`,
        })) },
        { label: "過去の下書き", rows: draftSession.map((session) => ({
            ...session, schedule: dateInputValue(session.session_date),
        })) },
    ];

    return (
        <>
            <PageHeader heading="管理者ダッシュボード" />

            <section className={styles.section}>
                <p className={styles.sectionLabel}>クイックメニュー</p>
                <div className={styles.quickMenu}>
                    {quickMenu.map(({ label, href }) => (
                        <LinkButton
                            key={label}
                            href={href}
                            fullReload={href === "#"}
                        >
                            {label}
                        </LinkButton>
                    ))}
                </div>
            </section>

            <section className={styles.section}>
                <p className={styles.sectionLabel}>今日のセッション</p>
                {todayReservations.length === 0 ? (
                    <p>今日の確定済み予約はありません。</p>
                ) : (
                    <ul className={styles.taskList}>
                        {todayReservations.map((reservation) => (
                            <SessionRow
                                key={reservation.id}
                                schedule={reservationTime(reservation)}
                                clientId={reservation.client_id}
                                clientName={reservation.client.name}
                                status={reservation.pilates_session === null
                                    ? "未記録"
                                    : reservation.pilates_session.is_draft ? "下書き" : "保存済み"}
                            />
                        ))}
                    </ul>
                )}
            </section>

            <section className={styles.section}>
                <p className={styles.sectionLabel}>やることリスト</p>
                <ul className={styles.taskList}>
                    <li>
                        {pendingReservationCount > 0 ? (
                            <Link
                                href={pendingReservationsUrl}
                                className={styles.taskLink}
                            >
                                会場確認待ちが{pendingReservationCount}
                                件あります
                            </Link>
                        ) : (
                            "会場確認待ちの予約はありません"
                        )}
                    </li>
                </ul>
                {sessionTasks.every(({ rows }) => rows.length === 0) ? (
                    <p className={styles.sessionTasks}>未完了のセッション記録はありません。</p>
                ) : sessionTasks.filter(({ rows }) => rows.length > 0).map(({ label, rows, canCreate }) => (
                    <div key={label} className={styles.sessionTasks}>
                        <h2>{label}（{rows.length}件）</h2>
                        <ul className={styles.taskList}>
                            {rows.map((row) => (
                                <SessionRow
                                    key={row.id}
                                    schedule={row.schedule}
                                    clientId={row.client_id}
                                    clientName={row.client.name}
                                    reservationId={canCreate ? row.id : undefined}
                                />
                            ))}
                        </ul>
                    </div>
                ))}
            </section>

            <section className={styles.section}>
                <p className={styles.sectionLabel}>開発メモ</p>
                <LinkButton
                    href="https://app.notion.com/p/39d63551227080bfb153fc77b48c4341?source=copy_link"
                    external
                >
                    Notionを開く
                </LinkButton>
            </section>
        </>
    );
}
