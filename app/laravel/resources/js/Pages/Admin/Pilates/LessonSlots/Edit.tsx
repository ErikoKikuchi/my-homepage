import { useForm } from "@inertiajs/react";
import ActionButton from "@/Components/Admin/UI/ActionButton/ActionButton";
import LinkButton from "@/Components/Admin/UI/LinkButton/LinkButton";
import AdminPage, { FieldError } from "../AdminPage";
import ScheduleFields from "../ScheduleFields";
import styles from "../AdminPage.module.css";
import { admin, dateInputValue, type LessonSlot, type LessonTemplate, type NamedOption } from "../schedule";

type Props = { lessonTemplates: LessonTemplate[]; locations: NamedOption[]; lessonSlot: LessonSlot };

export default function LessonSlotsEdit({ lessonTemplates, locations, lessonSlot }: Props) {
    const form = useForm({
        date: dateInputValue(lessonSlot.date),
        lesson_template_id: lessonSlot.lesson_template_id,
        location_id: lessonSlot.location_id ?? "",
    });
    const templates = lessonTemplates.some((template) => template.id === lessonSlot.lesson_template_id)
        ? lessonTemplates
        : [...lessonTemplates, lessonSlot.lesson_template];
    const availableLocations = lessonSlot.location && !locations.some((location) => location.id === lessonSlot.location_id)
        ? [...locations, lessonSlot.location]
        : locations;
    const index = `${admin}/lesson-slots`;

    return (
        <AdminPage heading="レッスン枠編集">
            <form className={styles.form} onSubmit={(event) => {
                event.preventDefault();
                form.put(`${index}/${lessonSlot.id}`);
            }}>
                <div>
                    <label htmlFor="date">日付（必須）</label>
                    <input type="date" id="date" required value={form.data.date} onChange={(event) => form.setData("date", event.target.value)} />
                    <FieldError message={form.errors.date} />
                </div>
                <ScheduleFields lessonTemplates={templates} locations={availableLocations}
                    templateId={form.data.lesson_template_id} locationId={form.data.location_id}
                    onTemplateChange={(value) => form.setData("lesson_template_id", value)}
                    onLocationChange={(value) => form.setData("location_id", value)} errors={form.errors} />
                <div className={styles.actions}>
                    <ActionButton type="submit" disabled={form.processing}>更新する</ActionButton>
                    <LinkButton href={index} variant="outline">キャンセル</LinkButton>
                </div>
            </form>
        </AdminPage>
    );
}
