import axios from "axios";
import { useState } from "react";
import ActionButton from "@/Components/Admin/UI/ActionButton/ActionButton";
import LinkButton from "@/Components/Admin/UI/LinkButton/LinkButton";
import AdminPage, { FieldError } from "../AdminPage";
import styles from "../AdminPage.module.css";
import sessionStyles from "../Sessions/Sessions.module.css";
import { admin } from "../schedule";

type Client = {
    id: string;
    name: string;
    gender: string | null;
    line_linked: boolean;
    is_active: boolean;
    body_notes: string | null;
    personality_notes: string | null;
    name_kana: string | null;
    phone: string | null;
    occupation: string | null;
    relationship_note: string | null;
};

const basicFields = [
    { key: "name", label: "氏名" },
    { key: "name_kana", label: "かな" },
    { key: "gender", label: "性別" },
    { key: "phone", label: "電話番号" },
    { key: "occupation", label: "職業" },
    { key: "personality_notes", label: "性格・傾向" },
    { key: "relationship_note", label: "関係性（ほかの人との関連情報）" },
] as const;
type BasicField = (typeof basicFields)[number]["key"];
const genderLabels: Record<string, string> = {
    male: "男性",
    female: "女性",
    other: "その他",
};

type RecentReservation = {
    id: string;
    date: string;
    startTime: string | null;
    location: string | null;
    status: string;
};

const links = [
    ["goals", "ゴール設定"],
    ["sessions", "セッション記録"],
    ["intake-forms", "初回問診情報"],
    ["accounting", "会計情報"],
    ["training-logs", "BodyMind緊急メッセージ"],
];

function AccountLink({
    url,
    accountLinked,
    canLinkAccount,
}: {
    url: string;
    accountLinked: boolean;
    canLinkAccount: boolean;
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
                <form
                    className={styles.form}
                    onSubmit={async (event) => {
                        event.preventDefault();
                        if (!confirmed || busy) return;
                        setBusy(true);
                        setError("");
                        try {
                            await axios.post(
                                `${url}/account`,
                                { email, identity_confirmed: confirmed },
                                {
                                    headers: { Accept: "application/json" },
                                },
                            );
                            setLinked(true);
                        } catch (failure) {
                            setError(
                                axios.isAxiosError(failure)
                                    ? (failure.response?.data?.message ??
                                          "紐づけできませんでした。")
                                    : "紐づけできませんでした。",
                            );
                        } finally {
                            setBusy(false);
                        }
                    }}
                >
                    <p>
                        来店時に、ご本人のアカウントに登録したメールアドレスを確認してください。
                    </p>
                    <label>
                        登録メールアドレス
                        <input
                            type="email"
                            required
                            maxLength={255}
                            autoComplete="off"
                            value={email}
                            onChange={(event) => {
                                setEmail(event.target.value);
                                setConfirmed(false);
                            }}
                        />
                    </label>
                    <label>
                        <input
                            type="checkbox"
                            required
                            checked={confirmed}
                            onChange={(event) =>
                                setConfirmed(event.target.checked)
                            }
                        />
                        来店時にご本人と登録メールアドレスを確認しました
                    </label>
                    <FieldError message={error} />
                    <div className={styles.actions}>
                        <ActionButton
                            type="submit"
                            disabled={!confirmed || busy}
                        >
                            アカウントを紐づける
                        </ActionButton>
                    </div>
                </form>
            )}
        </section>
    );
}

export default function ClientsShow({
    client,
    accountLinked,
    canLinkAccount,
    recentReservations,
}: {
    client: Client;
    accountLinked: boolean;
    canLinkAccount: boolean;
    recentReservations: RecentReservation[];
}) {
    const [record, setRecord] = useState(client);
    const [editing, setEditing] = useState(false);
    const [busy, setBusy] = useState(false);
    const [error, setError] = useState("");
    const url = `${admin}/clients/${client.id}`;

    async function update(
        patch: Partial<Pick<Client, BasicField | "line_linked" | "is_active">>,
    ) {
        if (busy) return;
        setBusy(true);
        setError("");
        try {
            const { data } = await axios.patch<{ client: Partial<Client> }>(
                url,
                patch,
                { headers: { Accept: "application/json" } },
            );
            setRecord((current) => ({ ...current, ...data.client }));
            return true;
        } catch (failure) {
            setError(
                axios.isAxiosError(failure)
                    ? (failure.response?.data?.message ??
                          "更新できませんでした。")
                    : "更新できませんでした。",
            );
        } finally {
            setBusy(false);
        }
    }

    return (
        <AdminPage heading="クライアント詳細">
            <div className={styles.stack}>
                <section className={styles.stack}>
                    <div className={styles.actions}>
                        <h2>基本情報</h2>
                        {!editing && (
                            <ActionButton
                                variant="text"
                                disabled={busy}
                                onClick={() => {
                                    setError("");
                                    setEditing(true);
                                }}
                            >
                                編集
                            </ActionButton>
                        )}
                    </div>
                    <form
                        className={styles.stack}
                        onSubmit={(event) => {
                            event.preventDefault();
                            if (!editing) return;
                            const data = new FormData(event.currentTarget);
                            const patch: Partial<Pick<Client, BasicField>> = {};
                            for (const { key } of basicFields) {
                                const value = String(data.get(key) ?? "");
                                if (value !== (record[key] ?? ""))
                                    patch[key] = value;
                            }
                            if (Object.keys(patch).length === 0) {
                                setEditing(false);
                                return;
                            }
                            void update(patch).then((saved) => {
                                if (saved) setEditing(false);
                            });
                        }}
                    >
                        <table
                            className={sessionStyles.informationTable}
                            aria-label="基本情報"
                        >
                            <tbody>
                                {basicFields.map(({ key, label }) => (
                                    <tr key={key}>
                                        <th scope="row">{label}</th>
                                        <td
                                            className={
                                                editing
                                                    ? sessionStyles.clientField
                                                    : undefined
                                            }
                                        >
                                            {!editing ? (
                                                (key === "gender"
                                                    ? (genderLabels[
                                                          record.gender ?? ""
                                                      ] ?? record.gender)
                                                    : record[key]) || "未登録"
                                            ) : key === "gender" ? (
                                                <select
                                                    aria-label={label}
                                                    name={key}
                                                    defaultValue={
                                                        record.gender ?? ""
                                                    }
                                                    disabled={busy}
                                                >
                                                    <option value="">
                                                        未登録
                                                    </option>
                                                    {Object.entries(
                                                        genderLabels,
                                                    ).map(([value, text]) => (
                                                        <option
                                                            key={value}
                                                            value={value}
                                                        >
                                                            {text}
                                                        </option>
                                                    ))}
                                                </select>
                                            ) : key === "personality_notes" ||
                                              key === "relationship_note" ? (
                                                <textarea
                                                    aria-label={label}
                                                    name={key}
                                                    defaultValue={
                                                        record[key] ?? ""
                                                    }
                                                    maxLength={16000}
                                                    rows={3}
                                                    disabled={busy}
                                                />
                                            ) : (
                                                <input
                                                    aria-label={label}
                                                    name={key}
                                                    type={
                                                        key === "phone"
                                                            ? "tel"
                                                            : "text"
                                                    }
                                                    defaultValue={
                                                        record[key] ?? ""
                                                    }
                                                    required={key === "name"}
                                                    maxLength={255}
                                                    disabled={busy}
                                                />
                                            )}
                                        </td>
                                    </tr>
                                ))}
                                <tr>
                                    <th scope="row">LINE連携</th>
                                    <td>
                                        <label>
                                            <input
                                                type="checkbox"
                                                checked={record.line_linked}
                                                disabled={busy}
                                                onChange={(event) => {
                                                    void update({
                                                        line_linked:
                                                            event.target
                                                                .checked,
                                                    });
                                                }}
                                            />
                                            LINE連携済み
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">利用状況</th>
                                    <td>
                                        <label>
                                            <input
                                                type="checkbox"
                                                checked={record.is_active}
                                                disabled={busy}
                                                onChange={(event) => {
                                                    const active =
                                                        event.target.checked;
                                                    if (
                                                        active ||
                                                        window.confirm(
                                                            "アーカイブ状態に変更します。よろしいですか？",
                                                        )
                                                    )
                                                        void update({
                                                            is_active: active,
                                                        });
                                                }}
                                            />
                                            現在利用中{" "}
                                            {record.is_active
                                                ? "（有効）"
                                                : "（アーカイブ済み）"}
                                        </label>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        {editing && (
                            <div className={styles.actions}>
                                <ActionButton type="submit" disabled={busy}>
                                    保存
                                </ActionButton>
                                <ActionButton
                                    variant="outline"
                                    disabled={busy}
                                    onClick={() => {
                                        setError("");
                                        setEditing(false);
                                    }}
                                >
                                    キャンセル
                                </ActionButton>
                            </div>
                        )}
                    </form>
                    <FieldError message={error} />
                </section>
                <AccountLink
                    url={url}
                    accountLinked={accountLinked}
                    canLinkAccount={canLinkAccount && record.is_active}
                />
                <section className={styles.stack}>
                    <h2>カルテ情報（参照のみ）</h2>
                    <table
                        className={sessionStyles.informationTable}
                        aria-label="カルテ情報"
                    >
                        <tbody>
                            <tr>
                                <th scope="row">身体の特徴・所見</th>
                                <td>{record.body_notes || "記録なし"}</td>
                            </tr>
                        </tbody>
                    </table>
                    <p className={styles.muted}>
                        編集はセッション記録画面から行ってください。
                    </p>
                </section>
                <section className={styles.stack}>
                    <h2>関連情報</h2>
                    <div className={styles.actions}>
                        {links.map(([path, label]) => (
                            <LinkButton
                                key={path}
                                href={`${url}/${path}`}
                                variant="outline"
                            >
                                {label}
                            </LinkButton>
                        ))}
                    </div>
                    <h3 id="client-reservation-history">直近の予約履歴</h3>
                    {recentReservations.length === 0 ? (
                        <p>予約履歴はありません。</p>
                    ) : (
                        <table
                            className={sessionStyles.informationTable}
                            aria-labelledby="client-reservation-history"
                        >
                            <thead>
                                <tr>
                                    <th scope="col">予約日時</th>
                                    <th scope="col">会場</th>
                                    <th scope="col">状態</th>
                                </tr>
                            </thead>
                            <tbody>
                                {recentReservations.map(
                                    ({
                                        id,
                                        date,
                                        startTime,
                                        location,
                                        status,
                                    }) => (
                                        <tr key={id}>
                                            <td>
                                                {date}
                                                {startTime &&
                                                    ` ${startTime.slice(0, 5)}`}
                                            </td>
                                            <td>{location ?? "未定"}</td>
                                            <td>{status}</td>
                                        </tr>
                                    ),
                                )}
                            </tbody>
                        </table>
                    )}
                </section>
                <LinkButton href={`${admin}/clients`} variant="text">
                    クライアント一覧に戻る
                </LinkButton>
            </div>
        </AdminPage>
    );
}
