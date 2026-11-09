import type { ReactNode } from "react";
import LinkButton from "@/Components/Admin/UI/LinkButton/LinkButton";
import AdminPage from "../AdminPage";
import styles from "../AdminPage.module.css";
import { admin, dateInputValue } from "../schedule";

type ClientSummary = {
    id: string;
    name: string;
    phone: string | null;
    has_unpaid_fee: boolean;
    line_linked: boolean;
    lesson_slots_via_reservations_max_date: string | null;
};

export type Account = {
    id: string;
    is_client: boolean;
    profile?: { display_name: string } | null;
    client: ClientSummary | null;
};

type Page<T> = {
    data: T[];
    prev_page_url: string | null;
    next_page_url: string | null;
    current_page: number;
    last_page: number;
};

export type AccountPage = Page<Account>;
export type StandaloneClientPage = Page<ClientSummary>;

function ClientCard({ name, client, registered, children }: {
    name: string; client: ClientSummary | null; registered: boolean; children?: ReactNode;
}) {
    const latest = client?.lesson_slots_via_reservations_max_date;

    return (
        <article className={styles.card}>
            <div>
                <h2>{name}</h2>
                <p>{registered ? "クライアント" : "クライアント未登録"}{client?.has_unpaid_fee && " ／ 未払いあり"}{client?.line_linked && " ／ LINE連携済み"}</p>
                <p>{client?.phone ?? "電話番号未登録"}</p>
                <p>直近予約日: {latest ? dateInputValue(latest) : "—"}</p>
            </div>
            {client && registered
                ? <LinkButton href={`${admin}/clients/${client.id}`} variant="outline">詳細</LinkButton>
                : children}
        </article>
    );
}

function Pagination({ page, label }: { page: Page<unknown>; label: string }) {
    if (page.last_page <= 1) return null;

    return (
        <nav className={styles.actions} aria-label={label}>
            {page.prev_page_url && <LinkButton href={page.prev_page_url} variant="outline">前へ</LinkButton>}
            <span>{page.current_page} / {page.last_page}</span>
            {page.next_page_url && <LinkButton href={page.next_page_url} variant="outline">次へ</LinkButton>}
        </nav>
    );
}

export default function ClientList({ page, standaloneClients, pendingUsers, provisionalClients, archived = false, register, registerStandalone }: {
    page: AccountPage;
    standaloneClients: StandaloneClientPage;
    pendingUsers?: AccountPage;
    provisionalClients?: StandaloneClientPage;
    archived?: boolean;
    register?: (account: Account) => ReactNode;
    registerStandalone?: (client: ClientSummary) => ReactNode;
}) {
    return (
        <AdminPage heading={archived ? "クライアントアーカイブ" : "クライアント管理"}>
            <div className={styles.stack}>
                <div className={styles.bar}>
                    <h2>{archived ? "アーカイブ一覧" : "クライアント一覧"}</h2>
                    <LinkButton href={`${admin}/clients${archived ? "" : "/archive"}`} variant="text">
                        {archived ? "クライアント一覧に戻る" : "アーカイブを見る"}
                    </LinkButton>
                </div>
                {page.data.length === 0 && standaloneClients.data.length === 0 && <p>対象の方はいません。</p>}
                <div className={styles.grid}>
                    {page.data.map((account) => (
                        <ClientCard key={account.id} client={account.client} registered
                            name={account.client?.name ?? `ユーザー（${account.id}）`} />
                    ))}
                    {standaloneClients.data.map((client) => (
                        <ClientCard key={client.id} name={client.name} client={client} registered />
                    ))}
                </div>
                <Pagination page={page} label="Webアカウントがある方のページ切り替え" />
                <Pagination page={standaloneClients} label="Webアカウント未連携の登録済みクライアントのページ切り替え" />
                {pendingUsers && provisionalClients && (
                    <section className={styles.stack} aria-labelledby="provisional-clients">
                        <h2 id="provisional-clients">クライアント化するリスト</h2>
                        {pendingUsers.data.length === 0 && provisionalClients.data.length === 0 && <p>登録待ちの方はいません。</p>}
                        <div className={styles.grid}>
                            {pendingUsers.data.map((account) => (
                                <ClientCard key={account.id} client={account.client} registered={false}
                                    name={account.client?.name ?? account.profile?.display_name ?? `ユーザー（${account.id}）`}>
                                    {register?.(account)}
                                </ClientCard>
                            ))}
                            {provisionalClients.data.map((client) => (
                                <ClientCard key={client.id} name={client.name} client={client} registered={false}>
                                    {registerStandalone?.(client)}
                                </ClientCard>
                            ))}
                        </div>
                        <Pagination page={pendingUsers} label="Webアカウントがある登録待ちの方のページ切り替え" />
                        <Pagination page={provisionalClients} label="Webアカウント未連携の登録待ちの方のページ切り替え" />
                    </section>
                )}
            </div>
        </AdminPage>
    );
}
