import LinkButton from "@/Components/Admin/UI/LinkButton/LinkButton";
import AdminPage from "../AdminPage";
import styles from "../AdminPage.module.css";
import { admin, type NamedOption } from "../schedule";

type Session = { id: string; session_date: string };

const sessionDate = new Intl.DateTimeFormat("ja-JP", {
    dateStyle: "medium",
    timeStyle: "short",
    timeZone: "Asia/Tokyo",
});

export default function SessionsIndex({ client, sessions }: { client: NamedOption; sessions: Session[] }) {
    return (
        <AdminPage heading="セッション記録">
            <section className={styles.stack}>
                <h2>{client.name}さんのセッション記録</h2>
                {sessions.length === 0 ? <p>セッション記録はありません。</p> : (
                    <ul className={styles.stack}>
                        {sessions.map((session) => (
                            <li key={session.id} className={styles.card}>
                                <time dateTime={session.session_date}>{sessionDate.format(new Date(session.session_date))}</time>
                            </li>
                        ))}
                    </ul>
                )}
                <div className={styles.actions}>
                    <LinkButton href={`${admin}/sessions`} variant="outline">クライアントを選ぶ</LinkButton>
                    <LinkButton href={`${admin}/clients/${client.id}`} variant="text">クライアント詳細へ</LinkButton>
                </div>
            </section>
        </AdminPage>
    );
}
