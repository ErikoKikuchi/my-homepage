import { useForm } from "@inertiajs/react";
import LinkButton from "@/Components/Admin/UI/LinkButton/LinkButton";
import ActionButton from "@/Components/Admin/UI/ActionButton/ActionButton";
import AdminPage, { FieldError } from "../AdminPage";
import styles from "../AdminPage.module.css";
import { admin, dateInputValue, type LessonSlot, type NamedOption } from "../schedule";

type PendingReservation = {
    id: string;
    lesson_slot: LessonSlot;
    client: NamedOption & { phone: string | null };
    note: string | null;
};

function VenueForm({ id, locations }: { id: string; locations: NamedOption[] }) {
    const form = useForm({ location_id: "" });

    return (
        <form className={styles.form} onSubmit={(event) => {
            event.preventDefault();
            if (window.confirm("この予約を確定しますか？")) form.patch(`${admin}/reservations/${id}/confirm`, { preserveScroll: true });
        }}>
            <label>
                会場
                <select required value={form.data.location_id} onChange={(event) => form.setData("location_id", event.target.value)}>
                    <option value="">会場を選択</option>
                    {locations.map((location) => <option key={location.id} value={location.id}>{location.name}</option>)}
                </select>
            </label>
            <FieldError message={form.errors.location_id} />
            <ActionButton type="submit" disabled={form.processing}>確定する</ActionButton>
        </form>
    );
}

export default function ReservationsPending({ reservations, locations, lineUrl }: {
    reservations: PendingReservation[]; locations: NamedOption[]; lineUrl?: string;
}) {
    return (
        <AdminPage heading="会場確認待ちの予約">
            <div className={styles.stack}>
                {reservations.length === 0 && <p>現在、会場確認待ちの予約はありません。</p>}
                {reservations.map((reservation) => (
                    <article key={reservation.id} className={styles.card}>
                        <div>
                            <h2>{dateInputValue(reservation.lesson_slot.date)} {reservation.lesson_slot.lesson_template.start_time}</h2>
                            <p>{reservation.client.name} 様</p>
                            <p>TEL: {reservation.client.phone ?? "未登録"}</p>
                            {reservation.note && <p>備考: {reservation.note}</p>}
                            {lineUrl && <LinkButton href={lineUrl} external variant="outline">LINEを開く</LinkButton>}
                        </div>
                        <VenueForm id={reservation.id} locations={locations} />
                    </article>
                ))}
            </div>
        </AdminPage>
    );
}
