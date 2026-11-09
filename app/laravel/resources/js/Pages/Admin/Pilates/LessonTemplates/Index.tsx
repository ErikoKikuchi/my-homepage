import { useForm } from "@inertiajs/react";
import LinkButton from "@/Components/Admin/UI/LinkButton/LinkButton";
import ActionButton from "@/Components/Admin/UI/ActionButton/ActionButton";
import AdminPage from "../AdminPage";
import styles from "../AdminPage.module.css";
import { admin, lessonTime, type LessonTemplate } from "../schedule";

export default function LessonTemplatesIndex({ lessonTemplates }: { lessonTemplates: LessonTemplate[] }) {
    const mutation = useForm({});
    const base = `${admin}/lesson-templates`;

    return (
        <AdminPage heading="レッスン時間帯テンプレート管理">
            <div className={styles.stack}>
                <div className={styles.bar}>
                    <h2>時間帯一覧</h2>
                    <div className={styles.actions}>
                        <LinkButton href={`${base}/create`}>新規登録</LinkButton>
                    </div>
                </div>
                {lessonTemplates.length === 0 && <p>登録されている時間帯テンプレートはありません。</p>}
                {lessonTemplates.map((item) => (
                    <article key={item.id} className={styles.card}>
                        <h2>{lessonTime(item)}{!item.is_active && "（無効）"}</h2>
                        <div className={styles.actions}>
                            <LinkButton href={`${base}/${item.id}/edit`} variant="outline">編集</LinkButton>
                            <ActionButton variant="text" className={styles.danger} disabled={mutation.processing} onClick={() => {
                                if (window.confirm("削除しますか？")) mutation.delete(`${base}/${item.id}`);
                            }}>削除</ActionButton>
                        </div>
                    </article>
                ))}
                <LinkButton href={`${admin}/lesson-slots`} variant="text">スケジュール管理に戻る</LinkButton>
            </div>
        </AdminPage>
    );
}
