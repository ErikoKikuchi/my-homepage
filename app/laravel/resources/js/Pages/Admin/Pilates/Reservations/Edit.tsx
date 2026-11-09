import { useForm } from "@inertiajs/react";
import LinkButton from "@/Components/Admin/UI/LinkButton/LinkButton";
import ActionButton from "@/Components/Admin/UI/ActionButton/ActionButton";
import AdminPage, { FieldError } from "../AdminPage";
import styles from "../AdminPage.module.css";
import ScheduleFields from "../ScheduleFields";
import { admin, dateInputValue, lessonTime, type LessonSlot, type LessonTemplate, type NamedOption } from "../schedule";

type Reservation = { id: string; lesson_slot: LessonSlot; location: NamedOption | null; client: NamedOption };

export default function ReservationsEdit({ reservation, lessonTemplates, locations }: {
    reservation: Reservation; lessonTemplates: LessonTemplate[]; locations: NamedOption[];
}) {
    const form = useForm({ date: "", lesson_template_id: "", location_id: "" });
    const cancel = useForm({});
    const path = `${admin}/reservations/${reservation.id}`;

    return (
        <AdminPage heading="予約の編集">
            <section className={styles.card}>
                <div>
                    <h2>変更前</h2>
                    <dl>
                        <dt>日付</dt><dd>{dateInputValue(reservation.lesson_slot.date)}</dd>
                        <dt>時間帯</dt><dd>{lessonTime(reservation.lesson_slot.lesson_template)}</dd>
                        <dt>開催場所</dt><dd>{reservation.location?.name ?? "未定"}</dd>
                        <dt>お名前</dt><dd>{reservation.client.name}</dd>
                    </dl>
                </div>
            </section>
            <form className={styles.form} onSubmit={(event) => {
                event.preventDefault();
                form.put(path);
            }}>
                <h2>変更後</h2>
                <div>
                    <label htmlFor="date">日付（必須）</label>
                    <input id="date" type="date" required value={form.data.date} onChange={(event) => form.setData("date", event.target.value)} />
                    <FieldError message={form.errors.date} />
                </div>
                <ScheduleFields lessonTemplates={lessonTemplates} locations={locations}
                    templateId={form.data.lesson_template_id} locationId={form.data.location_id}
                    onTemplateChange={(value) => form.setData("lesson_template_id", value)}
                    onLocationChange={(value) => form.setData("location_id", value)} errors={form.errors} />
                <div className={styles.actions}>
                    <ActionButton type="submit" disabled={form.processing || cancel.processing}>変更する</ActionButton>
                    <LinkButton href={`${admin}/calendar`} variant="outline">カレンダーに戻る</LinkButton>
                </div>
            </form>
            <ActionButton variant="text" className={styles.danger} disabled={form.processing || cancel.processing} onClick={() => {
                if (window.confirm("この予約をキャンセルしますか？")) cancel.delete(path);
            }}>この予約をキャンセルする</ActionButton>
        </AdminPage>
    );
}
