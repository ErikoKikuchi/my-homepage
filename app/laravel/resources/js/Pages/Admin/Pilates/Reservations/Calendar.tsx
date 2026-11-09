import { useEffect, useState } from "react";
import LinkButton from "@/Components/Admin/UI/LinkButton/LinkButton";
import ActionButton from "@/Components/Admin/UI/ActionButton/ActionButton";
import AdminPage, { FieldError } from "../AdminPage";
import styles from "../AdminPage.module.css";
import calendarStyles from "./Calendar.module.css";
import { admin, type NamedOption } from "../schedule";

type Slot = {
    id: string;
    time: string;
    location: NamedOption | null;
    reservations: NamedOption[];
};

type Week = { weekStart: string; previous: string; next: string; weekMap: Record<string, Slot[]> };
const days = ["日", "月", "火", "水", "木", "金", "土"];

export default function ReservationsCalendar({ weekStart }: { weekStart: string }) {
    const [week, setWeek] = useState(weekStart);
    const [data, setData] = useState<Week | null>(null);
    const [error, setError] = useState("");
    const [attempt, setAttempt] = useState(0);

    useEffect(() => {
        const controller = new AbortController();
        setError("");
        fetch(`${admin}/calendar/events?week_start=${encodeURIComponent(week)}`, {
            headers: { Accept: "application/json" },
            signal: controller.signal,
        }).then((response) => {
            if (!response.ok) throw new Error();
            return response.json() as Promise<Week>;
        }).then((result) => {
            if (!controller.signal.aborted) setData(result);
        }).catch(() => {
            if (!controller.signal.aborted) setError("予約カレンダーを取得できませんでした。");
        });
        return () => controller.abort();
    }, [week, attempt]);

    const loading = week !== data?.weekStart && !error;

    return (
        <AdminPage heading="予約確認カレンダー・代理予約">
            <div className={styles.stack}>
                <div className={styles.actions}>
                    <ActionButton variant="outline" disabled={!data || loading} onClick={() => { if (data) setWeek(data.previous); }}>前週</ActionButton>
                    <ActionButton variant="outline" disabled={!data || loading} onClick={() => { if (data) setWeek(data.next); }}>翌週</ActionButton>
                    <h2>{data?.weekStart ?? week}の週</h2>
                    <LinkButton href={`${admin}/reservations`} variant="outline">予約履歴</LinkButton>
                </div>
                {loading && <p role="status">読み込み中…</p>}
                {error && <div className={styles.actions}>
                    <FieldError message={error} />
                    <ActionButton variant="outline" onClick={() => setAttempt(attempt + 1)}>再読み込み</ActionButton>
                </div>}
                {data && <div className={calendarStyles.calendar}>
                    <table aria-label="週間予約カレンダー">
                        <thead><tr>{days.map((day) => <th key={day} scope="col">{day}</th>)}</tr></thead>
                        <tbody><tr>{days.map((day, index) => {
                            const date = new Date(`${data.weekStart}T00:00:00Z`);
                            date.setUTCDate(date.getUTCDate() + index);
                            const key = date.toISOString().slice(0, 10);
                            return (
                                <td key={day}>
                                    <time dateTime={key}>{key}</time>
                                    {(data.weekMap[key] ?? []).map((slot) => (
                                        <div key={slot.id} className={calendarStyles.slot}>
                                            <strong>{slot.time}</strong>
                                            <span>{slot.location?.name}</span>
                                            {slot.reservations.map((reservation) => (
                                                <div key={reservation.id}>
                                                    <p>{reservation.name}</p>
                                                    <LinkButton href={`${admin}/reservations/${reservation.id}/edit`} variant="text">詳細</LinkButton>
                                                </div>
                                            ))}
                                            {slot.reservations.length === 0 && (
                                                <LinkButton href={`${admin}/lesson-slots/${slot.id}/reservations/create`} variant="outline">予約</LinkButton>
                                            )}
                                        </div>
                                    ))}
                                </td>
                            );
                        })}</tr></tbody>
                    </table>
                </div>}
            </div>
        </AdminPage>
    );
}
