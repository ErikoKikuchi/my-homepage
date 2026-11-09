import { useForm } from "@inertiajs/react";
import LinkButton from "@/Components/Admin/UI/LinkButton/LinkButton";
import ActionButton from "@/Components/Admin/UI/ActionButton/ActionButton";
import { FieldError } from "../AdminPage";
import styles from "../AdminPage.module.css";
import { admin, type LessonTemplate } from "../schedule";

export default function TemplateForm({ template }: { template?: LessonTemplate }) {
    const form = useForm({
        start_time: template?.start_time ?? "",
        end_time: template?.end_time ?? "",
        is_active: template?.is_active ?? true,
    });
    const index = `${admin}/lesson-templates`;

    return (
        <form className={styles.form} onSubmit={(event) => {
            event.preventDefault();
            if (template) form.put(`${index}/${template.id}`);
            else form.post(index);
        }}>
            {(["start_time", "end_time"] as const).map((field) => (
                <div key={field}>
                    <label htmlFor={field}>{field === "start_time" ? "開始時間（必須）" : "終了時間（必須）"}</label>
                    <input id={field} type="time" required value={form.data[field]}
                        onChange={(event) => form.setData(field, event.target.value)} />
                    <FieldError message={form.errors[field]} />
                </div>
            ))}
            <label>
                <input type="checkbox" checked={form.data.is_active}
                    onChange={(event) => form.setData("is_active", event.target.checked)} />
                有効にする（新規スケジュール作成時の選択肢に表示）
            </label>
            <FieldError message={form.errors.is_active} />
            <div className={styles.actions}>
                <ActionButton type="submit" disabled={form.processing}>{template ? "更新する" : "作成する"}</ActionButton>
                <LinkButton href={index} variant="outline">キャンセル</LinkButton>
            </div>
        </form>
    );
}
