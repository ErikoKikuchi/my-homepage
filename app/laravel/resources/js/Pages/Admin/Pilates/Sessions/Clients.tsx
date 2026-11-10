import LinkButton from "@/Components/Admin/UI/LinkButton/LinkButton";
import AdminPage from "../AdminPage";
import styles from "../AdminPage.module.css";
import { admin, type NamedOption } from "../schedule";

type ClientPage = {
    data: NamedOption[];
    prev_page_url: string | null;
    next_page_url: string | null;
    current_page: number;
    last_page: number;
};

export default function SessionClients({ clients }: { clients: ClientPage }) {
    return (
        <AdminPage heading="セッション記録">
            <section className={styles.stack}>
                <h2>クライアントを選ぶ</h2>
                {clients.data.length === 0 ? <p>表示できるクライアントがいません。</p> : (
                    <ul className={styles.stack}>
                        {clients.data.map((client) => (
                            <li key={client.id} className={styles.card}>
                                <LinkButton href={`${admin}/clients/${client.id}/sessions`} variant="text">
                                    {client.name}
                                </LinkButton>
                            </li>
                        ))}
                    </ul>
                )}
                {clients.last_page > 1 && (
                    <nav className={styles.actions} aria-label="クライアント一覧のページ切り替え">
                        {clients.prev_page_url && <LinkButton href={clients.prev_page_url} variant="outline">前へ</LinkButton>}
                        <span>{clients.current_page} / {clients.last_page}</span>
                        {clients.next_page_url && <LinkButton href={clients.next_page_url} variant="outline">次へ</LinkButton>}
                    </nav>
                )}
                <LinkButton href={`${admin}/dashboard`} variant="text">ダッシュボードに戻る</LinkButton>
            </section>
        </AdminPage>
    );
}
