import { useForm } from "@inertiajs/react";
import LinkButton from "@/Components/Admin/UI/LinkButton/LinkButton";
import ActionButton from "@/Components/Admin/UI/ActionButton/ActionButton";
import AdminPage from "../AdminPage";
import styles from "../AdminPage.module.css";
import { admin, dateInputValue, lessonTime, type LessonSlot } from "../schedule";

export default function LessonSlotsIndex({ lessonSlots }: { lessonSlots: LessonSlot[] }) {
    const mutation = useForm({});
    const base = `${admin}/lesson-slots`;

    return (
        <AdminPage heading="スケジュール管理">
            <div className={styles.stack}>
                <div className={styles.bar}>
                    <h2>スケジュール一覧</h2>
                    <div className={styles.actions}>
                        <LinkButton href={`${admin}/lesson-templates`} variant="outline">時間帯テンプレート管理</LinkButton>
                        <LinkButton href={`${base}/create`}>新規登録</LinkButton>
                    </div>
                </div>
                {lessonSlots.length === 0 && <p>登録されているレッスン枠はありません。</p>}
                {lessonSlots.map((item) => (
                    <article key={item.id} className={styles.card}>
                        <div><h2>{dateInputValue(item.date)}</h2><p>{lessonTime(item.lesson_template)}</p><p>{item.location?.name}</p></div>
                        <div className={styles.actions}>
                            <LinkButton href={`${base}/${item.id}/edit`} variant="outline">編集</LinkButton>
                            <ActionButton variant="text" className={styles.danger} disabled={mutation.processing} onClick={() => {
                                if (window.confirm("削除しますか？")) mutation.delete(`${base}/${item.id}`);
                            }}>削除</ActionButton>
                        </div>
                    </article>
                ))}
            </div>
        </AdminPage>
    );
}
