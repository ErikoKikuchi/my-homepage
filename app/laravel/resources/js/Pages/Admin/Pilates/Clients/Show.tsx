import axios from "axios";
import { useState } from "react";
import ActionButton from "@/Components/Admin/UI/ActionButton/ActionButton";
import LinkButton from "@/Components/Admin/UI/LinkButton/LinkButton";
import AdminPage, { FieldError } from "../AdminPage";
import styles from "../AdminPage.module.css";
import { admin } from "../schedule";

type Client = {
    id: string; name: string; gender: string | null; line_linked: boolean; is_active: boolean;
    body_notes: string | null; personality_notes: string | null;
};

const links = [
    ["goals", "ゴール設定"], ["sessions", "セッション履歴"], ["intake-forms", "初回問診情報"],
    ["accounting", "会計情報"], ["training-logs", "BodyMind緊急メッセージ"],
];

function AccountLink({ url, accountLinked, canLinkAccount }: {
    url: string; accountLinked: boolean; canLinkAccount: boolean;
}) {
    const [linked, setLinked] = useState(accountLinked);
    const [email, setEmail] = useState("");
    const [confirmed, setConfirmed] = useState(false);
    const [busy, setBusy] = useState(false);
    const [error, setError] = useState("");

    return (
        <section className={styles.stack}>
            <h2>Webアカウント</h2>
            <p role="status">{linked ? "紐づけ済み" : "未連携"}</p>
            {!linked && canLinkAccount && (
                <form className={styles.form} onSubmit={async (event) => {
                    event.preventDefault();
                    if (!confirmed || busy) return;
                    setBusy(true);
                    setError("");
                    try {
                        await axios.post(`${url}/account`, { email, identity_confirmed: confirmed }, {
                            headers: { Accept: "application/json" },
                        });
                        setLinked(true);
                    } catch (failure) {
                        setError(axios.isAxiosError(failure) ? failure.response?.data?.message ?? "紐づけできませんでした。" : "紐づけできませんでした。");
                    } finally {
                        setBusy(false);
                    }
                }}>
                    <p>来店時に、ご本人のアカウントに登録したメールアドレスを確認してください。</p>
                    <label>登録メールアドレス
                        <input type="email" required maxLength={255} autoComplete="off" value={email}
                            onChange={(event) => { setEmail(event.target.value); setConfirmed(false); }} />
                    </label>
                    <label>
                        <input type="checkbox" required checked={confirmed}
                            onChange={(event) => setConfirmed(event.target.checked)} />
                        来店時にご本人と登録メールアドレスを確認しました
                    </label>
                    <FieldError message={error} />
                    <div className={styles.actions}>
                        <ActionButton type="submit" disabled={!confirmed || busy}>アカウントを紐づける</ActionButton>
                    </div>
                </form>
            )}
        </section>
    );
}

export default function ClientsShow({ client, accountLinked, canLinkAccount }: {
    client: Client; accountLinked: boolean; canLinkAccount: boolean;
}) {
    const [record, setRecord] = useState(client);
    const [editing, setEditing] = useState(false);
    const [name, setName] = useState(client.name);
    const [busy, setBusy] = useState(false);
    const [error, setError] = useState("");
    const url = `${admin}/clients/${client.id}`;

    async function update(patch: Partial<Pick<Client, "name" | "line_linked" | "is_active">>) {
        if (busy) return;
        setBusy(true);
        setError("");
        try {
            const { data } = await axios.patch<{ client: Client }>(url, patch, { headers: { Accept: "application/json" } });
            setRecord((current) => ({ ...current, ...data.client }));
            if (patch.name !== undefined) setEditing(false);
        } catch (failure) {
            setError(axios.isAxiosError(failure) ? failure.response?.data?.message ?? "更新できませんでした。" : "更新できませんでした。");
        } finally {
            setBusy(false);
        }
    }

    return (
        <AdminPage heading="クライアント詳細">
            <div className={styles.stack}>
                <section className={styles.stack}>
                    <h2>基本情報</h2>
                    {editing ? (
                        <form className={styles.form} onSubmit={(event) => { event.preventDefault(); void update({ name }); }}>
                            <label>氏名<input value={name} onChange={(event) => setName(event.target.value)} required /></label>
                            <div className={styles.actions}>
                                <ActionButton type="submit" disabled={busy}>保存</ActionButton>
                                <ActionButton variant="outline" disabled={busy} onClick={() => setEditing(false)}>キャンセル</ActionButton>
                            </div>
                        </form>
                    ) : <div className={styles.actions}>
                        <p>氏名：{record.name}</p>
                        <ActionButton variant="text" disabled={busy} onClick={() => { setName(record.name); setEditing(true); }}>編集</ActionButton>
                    </div>}
                    <p>性別：{record.gender ?? "未登録"}</p>
                    <label>
                        <input type="checkbox" checked={record.line_linked} disabled={busy}
                            onChange={(event) => { void update({ line_linked: event.target.checked }); }} />
                        LINE連携済み
                    </label>
                    <label>
                        <input type="checkbox" checked={record.is_active} disabled={busy} onChange={(event) => {
                            const active = event.target.checked;
                            if (active || window.confirm("アーカイブ状態に変更します。よろしいですか？")) void update({ is_active: active });
                        }} />
                        現在利用中 {record.is_active ? "（有効）" : "（アーカイブ済み）"}
                    </label>
                    <FieldError message={error} />
                </section>
                <AccountLink url={url} accountLinked={accountLinked} canLinkAccount={canLinkAccount && record.is_active} />
                <section className={styles.stack}>
                    <h2>カルテ情報（参照のみ）</h2>
                    <div className={styles.grid}>
                        <div><h3>身体の特徴・所見</h3><p>{record.body_notes || "記録なし"}</p></div>
                        <div><h3>性格・傾向</h3><p>{record.personality_notes || "記録なし"}</p></div>
                    </div>
                    <p className={styles.muted}>編集はカルテ記載画面から行ってください。</p>
                </section>
                <section className={styles.stack}>
                    <h2>関連情報</h2>
                    <div className={styles.actions}>
                        {links.map(([path, label]) => <LinkButton key={path} href={`${url}/${path}`} variant="outline">{label}</LinkButton>)}
                        <span className={styles.muted}>予約履歴（準備中）</span>
                    </div>
                </section>
                <LinkButton href={`${admin}/clients`} variant="text">クライアント一覧に戻る</LinkButton>
            </div>
        </AdminPage>
    );
}
