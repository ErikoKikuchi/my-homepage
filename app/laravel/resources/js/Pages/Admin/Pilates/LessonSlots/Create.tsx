import { useForm } from "@inertiajs/react";
import { useState } from "react";
import ActionButton from "@/Components/Admin/UI/ActionButton/ActionButton";
import LinkButton from "@/Components/Admin/UI/LinkButton/LinkButton";
import AdminPage, { FieldError } from "../AdminPage";
import ScheduleFields from "../ScheduleFields";
import styles from "../AdminPage.module.css";
import { admin, type LessonTemplate, type NamedOption } from "../schedule";

type Props = { lessonTemplates: LessonTemplate[]; locations: NamedOption[] };

export default function LessonSlotsCreate({ lessonTemplates, locations }: Props) {
    const form = useForm({
        dates: [] as string[],
        lesson_template_id: "",
        location_id: "",
    });
    const [date, setDate] = useState("");
    const index = `${admin}/lesson-slots`;

    return (
        <AdminPage heading="レッスン枠作成">
            <form className={styles.form} onSubmit={(event) => {
                event.preventDefault();
                form.post(index);
            }}>
                <div>
                    <label htmlFor="date">日付（複数選択可）</label>
                    <div className={styles.actions}>
                        <input type="date" id="date" value={date} onChange={(event) => setDate(event.target.value)} />
                        <ActionButton variant="outline" disabled={!date || form.data.dates.includes(date)} onClick={() => {
                            form.setData("dates", [...form.data.dates, date].sort());
                            setDate("");
                        }}>追加</ActionButton>
                    </div>
                    <ul>
                        {form.data.dates.map((selected) => (
                            <li key={selected} className={styles.actions}>
                                {selected}
                                <ActionButton variant="text" onClick={() => {
                                    form.setData("dates", form.data.dates.filter((value) => value !== selected));
                                }}>削除</ActionButton>
                            </li>
                        ))}
                    </ul>
                    <FieldError message={form.errors.dates ?? Object.entries(form.errors).find(([key]) => key.startsWith("dates."))?.[1]} />
                </div>
                <ScheduleFields lessonTemplates={lessonTemplates} locations={locations}
                    templateId={form.data.lesson_template_id} locationId={form.data.location_id}
                    onTemplateChange={(value) => form.setData("lesson_template_id", value)}
                    onLocationChange={(value) => form.setData("location_id", value)} errors={form.errors} />
                <div className={styles.actions}>
                    <ActionButton type="submit" disabled={form.processing || form.data.dates.length === 0}>作成する</ActionButton>
                    <LinkButton href={index} variant="outline">キャンセル</LinkButton>
                </div>
            </form>
        </AdminPage>
    );
}
