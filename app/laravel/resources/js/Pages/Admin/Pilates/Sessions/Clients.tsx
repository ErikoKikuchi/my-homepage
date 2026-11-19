import LinkButton from "@/Components/Admin/UI/LinkButton/LinkButton";
import AdminPage from "../AdminPage";
import styles from "../AdminPage.module.css";
import sessionStyles from "./Sessions.module.css";
import { admin, type NamedOption } from "../schedule";

type ClientPage = {
    data: NamedOption[];
    prev_page_url: string | null;
    next_page_url: string | null;
    current_page: number;
    last_page: number;
};

type Purchase = {
    id: string;
    date: string;
    client: NamedOption;
    type: "session" | "venue_addon";
    passes: number;
    amount: number | null;
};

type RecentPurchases = {
    period: "30days" | "3months";
    buyerCount: number;
    purchases: Omit<ClientPage, "data"> & { data: Purchase[]; total: number };
};

const yen = new Intl.NumberFormat("ja-JP", { style: "currency", currency: "JPY" });

function PurchaseHistory({ period, buyerCount, purchases }: RecentPurchases) {
    return (
        <section className={styles.stack} aria-labelledby="recent-purchases">
            <h2 id="recent-purchases">最近の回数券購入</h2>
            <nav className={styles.actions} aria-label="購入履歴の期間">
                {([["30days", "直近30日"], ["3months", "直近3か月"]] as const).map(([value, label]) => (
                    <LinkButton
                        key={value}
                        href={`${admin}/accounting?period=${value}`}
                        variant={period === value ? "primary" : "outline"}
                        aria-current={period === value ? "page" : undefined}
                    >
                        {label}
                    </LinkButton>
                ))}
            </nav>
            <p>購入 {purchases.total}件 · 購入者 {buyerCount}人</p>
            {purchases.data.length > 0 ? (
                <div style={{ overflowX: "auto" }}>
                    <table className={sessionStyles.informationTable}>
                        <thead>
                            <tr>
                                <th scope="col">購入日</th>
                                <th scope="col">クライアント</th>
                                <th scope="col">購入内容</th>
                                <th scope="col">金額</th>
                            </tr>
                        </thead>
                        <tbody>
                            {purchases.data.map((purchase) => (
                                <tr key={purchase.id}>
                                    <td><time dateTime={purchase.date}>{purchase.date}</time></td>
                                    <td>
                                        <LinkButton href={`${admin}/clients/${purchase.client.id}/accounting`} variant="text">
                                            {purchase.client.name}
                                        </LinkButton>
                                    </td>
                                    <td>{purchase.type === "venue_addon" ? "会場追加券" : "回数券"} {purchase.passes}回分</td>
                                    <td>{purchase.amount === null ? "未記録" : yen.format(purchase.amount)}</td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
            ) : <p>この期間の購入記録はありません。</p>}
            {purchases.last_page > 1 && (
                <nav className={styles.actions} aria-label="購入履歴のページ">
                    {purchases.prev_page_url && <LinkButton href={purchases.prev_page_url} variant="outline">前へ</LinkButton>}
                    <span>{purchases.current_page} / {purchases.last_page}</span>
                    {purchases.next_page_url && <LinkButton href={purchases.next_page_url} variant="outline">次へ</LinkButton>}
                </nav>
            )}
        </section>
    );
}

export default function SessionClients({
    clients,
    section = "sessions",
    recentPurchases,
}: {
    clients: ClientPage;
    section?: "sessions" | "accounting";
    recentPurchases?: RecentPurchases;
}) {
    return (
        <AdminPage heading={section === "accounting" ? "会計管理" : "セッション記録"}>
            {recentPurchases && <PurchaseHistory {...recentPurchases} />}
            <section className={styles.stack}>
                <h2>クライアントを選ぶ</h2>
                {clients.data.length === 0 ? <p>表示できるクライアントがいません。</p> : (
                    <ul className={styles.stack}>
                        {clients.data.map((client) => (
                            <li key={client.id} className={styles.card}>
                                <LinkButton href={`${admin}/clients/${client.id}/${section}`} variant="text">
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
