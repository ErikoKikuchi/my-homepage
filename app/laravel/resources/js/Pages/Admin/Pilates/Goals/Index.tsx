import { useForm } from "@inertiajs/react";
import { useState, type SubmitEvent } from "react";
import ActionButton from "@/Components/Admin/UI/ActionButton/ActionButton";
import LinkButton from "@/Components/Admin/UI/LinkButton/LinkButton";
import AdminPage, { FieldError } from "../AdminPage";
import styles from "../AdminPage.module.css";
import sessionStyles from "../Sessions/Sessions.module.css";
import ClientInformationTable, {
    type ClientInformation,
} from "@/Components/Admin/Pilates/ClientInformationTable";
import { admin } from "../schedule";

type Goal = ClientInformation["goals"][number] & { id: string; created_at: string };
type Hope = ClientInformation["hopes"][number] & { id: string; created_at: string; achieved_at: string | null };
type BodyNote = { id: string; body_note: string; observed_on: string | null };

type Props = Omit<ClientInformation, "goals" | "hopes"> & {
    goals: Goal[];
    hopes: Hope[];
    bodyHistory: BodyNote[];
    goalHistory: Goal[];
    hopeHistory: Hope[];
    legacyBodyNote: string | null;
};

const historyDate = new Intl.DateTimeFormat("ja-JP", {
    dateStyle: "medium",
    timeZone: "Asia/Tokyo",
});

const historyDay = new Intl.DateTimeFormat("sv-SE", {
    year: "numeric",
    month: "2-digit",
    day: "2-digit",
    timeZone: "Asia/Tokyo",
});

function groupHistory({ bodyHistory, hopeHistory, goalHistory, legacyBodyNote }: Pick<Props, "bodyHistory" | "hopeHistory" | "goalHistory" | "legacyBodyNote">) {
    const days = new Map<string, Map<string, string[]>>();

    function add(date: string | null, label: string, value: string | null) {
        if (!value?.trim()) return;

        const day = date ? historyDay.format(new Date(date)) : "";
        const fields = days.get(day) ?? new Map<string, string[]>();
        const values = fields.get(label) ?? [];
        fields.set(label, [...values, value]);
        days.set(day, fields);
    }

    for (const note of bodyHistory) {
        add(note.observed_on, "身体の特徴・所見", note.body_note);
    }
    add(null, "身体の特徴・所見", legacyBodyNote);

    for (const hope of hopeHistory) {
        add(hope.created_at, hope.achieved_at ? "希望（HOPE） ✓ 達成済み" : "希望（HOPE）", hope.hope);
    }
    for (const goal of goalHistory) {
        add(goal.created_at, "見通し", goal.outlook);
        add(goal.created_at, "ゴール設定", goal.goal);
    }

    return [...days.entries()].sort(([left], [right]) => right.localeCompare(left));
}

export default function GoalsIndex({
    client, goals, hopes, bodyHistory, goalHistory, hopeHistory, legacyBodyNote,
}: Props) {
    const history = groupHistory({ bodyHistory, hopeHistory, goalHistory, legacyBodyNote });
    const [editing, setEditing] = useState(false);
    const clientUrl = `${admin}/clients/${client.id}`;
    const achievement = useForm({});
    const form = useForm({
        body_note: "",
        new_hope: "",
        hopes: {} as Record<string, string>,
        outlook: {} as Record<string, string>,
        goal: {} as Record<string, string>,
        outlook_append: "",
        goal_append: "",
    });

    function beginEditing() {
        form.clearErrors();
        form.setData({
            body_note: "",
            outlook_append: "",
            goal_append: "",
            new_hope: "",
            hopes: Object.fromEntries(hopes.map(({ id, hope }) => [id, hope ?? ""])),
            outlook: Object.fromEntries(goals.filter(({ outlook }) => outlook != null).map(({ id, outlook }) => [id, outlook ?? ""])),
            goal: Object.fromEntries(goals.filter(({ goal }) => goal != null).map(({ id, goal }) => [id, goal ?? ""])),
        });
        setEditing(true);
    }

    function save(event: SubmitEvent<HTMLFormElement>) {
        event.preventDefault();
        if (form.processing) return;
        form.patch(`${clientUrl}/goals`, {
            preserveScroll: true,
            onSuccess: () => setEditing(false),
        });
    }

    return (
        <AdminPage heading="ゴール設定">
            <section className={styles.stack}>
                <h2 id="client-information">{client.name}の身体情報・ゴール設定</h2>
                <ClientInformationTable
                    client={client}
                    goals={goals}
                    hopes={hopes}
                    bodyNotes={[
                        ...bodyHistory.map(({ body_note, observed_on }) => ({
                            value: body_note,
                            date: observed_on,
                        })),
                        { value: legacyBodyNote },
                    ]}
                    labelledBy="client-information"
                />
                {editing ? (
                    <form className={styles.form} onSubmit={save}>
                        <label>
                            身体の特徴・所見を追記
                            <textarea rows={3} value={form.data.body_note}
                                onChange={(event) => form.setData("body_note", event.target.value)} />
                        </label>
                        {hopes.map(({ id }, index) => (
                            <label key={id}>
                                希望（HOPE）{index + 1}
                                <textarea rows={2} value={form.data.hopes[id] ?? ""}
                                    onChange={(event) => form.setData("hopes", {
                                        ...form.data.hopes,
                                        [id]: event.target.value,
                                    })} />
                            </label>
                        ))}
                        {hopes.length > 0 && (
                            <p className={styles.muted}>希望を空欄にして保存すると、履歴に移ります。</p>
                        )}
                        <label>
                            希望（HOPE）を追加
                            <textarea rows={2} value={form.data.new_hope}
                                onChange={(event) => form.setData("new_hope", event.target.value)} />
                        </label>
                        {([
                            ["outlook", "outlook_append", "見通し"],
                            ["goal", "goal_append", "ゴール"],
                        ] as const).map(([field, appendField, label]) => {
                            const entries = goals.filter((entry) => entry[field] != null);

                            return (
                                <fieldset key={field} className={styles.stack}>
                                    <legend>{label}</legend>
                                    {entries.map(({ id, created_at }, index) => (
                                        <label key={id}>
                                            {label} {index + 1}
                                            {created_at && (
                                                <> （<time dateTime={created_at}>{historyDate.format(new Date(created_at))}</time>）</>
                                            )}
                                            <textarea rows={3} value={form.data[field][id] ?? ""}
                                                onChange={(event) => form.setData(field, {
                                                    ...form.data[field],
                                                    [id]: event.target.value,
                                                })} />
                                        </label>
                                    ))}
                                    <label>
                                        {label}{entries.length > 0 ? "を追記" : "を登録"}
                                        <textarea rows={2} value={form.data[appendField]}
                                            onChange={(event) => form.setData(appendField, event.target.value)} />
                                    </label>
                                </fieldset>
                            );
                        })}
                        {Object.entries(form.errors).map(([field, message]) => (
                            <FieldError key={field} message={message} />
                        ))}
                        <div className={styles.actions}>
                            <ActionButton type="submit" disabled={form.processing}>一括保存</ActionButton>
                            <ActionButton variant="text" disabled={form.processing}
                                onClick={() => setEditing(false)}>キャンセル</ActionButton>
                        </div>
                    </form>
                ) : (
                    <div className={styles.actions}>
                        <ActionButton disabled={achievement.processing} onClick={beginEditing}>
                            {client.body_notes || hopes.length || goals.length ? "編集・追記" : "登録"}
                        </ActionButton>
                    </div>
                )}
                {!editing && hopes.length > 0 && (
                    <section className={styles.stack}>
                        <h3>HOPEの達成を記録</h3>
                        {hopes.map(({ id, hope }) => (
                            <div key={id} className={styles.card}>
                                <p>{hope}</p>
                                <ActionButton variant="outline" disabled={achievement.processing} onClick={() => {
                                    if (window.confirm("このHOPEを達成済みにしますか？")) {
                                        achievement.patch(`${clientUrl}/goals/hopes/${id}/achieve`, { preserveScroll: true });
                                    }
                                }}>達成済みにする</ActionButton>
                            </div>
                        ))}
                    </section>
                )}
                {history.length > 0 && (
                    <details>
                        <summary>履歴を見る</summary>
                        <div className={styles.stack}>
                            {history.map(([day, fields]) => (
                                <section key={day} className={styles.stack}>
                                    <h3 id={day ? "history-" + day : "history-undated"}>
                                        {day ? (
                                            <time dateTime={day}>{historyDate.format(new Date(day))}</time>
                                        ) : "日付の記録なし"}
                                    </h3>
                                    <table
                                        className={sessionStyles.informationTable}
                                        aria-labelledby={day ? "history-" + day : "history-undated"}
                                    >
                                        <tbody>
                                            {[...fields.entries()].map(([label, values]) => (
                                                <tr key={label}>
                                                    <th scope="row">{label}</th>
                                                    <td>{values.join("\n")}</td>
                                                </tr>
                                            ))}
                                        </tbody>
                                    </table>
                                </section>
                            ))}
                        </div>
                    </details>
                )}
                <div className={styles.actions}>
                    <LinkButton href={clientUrl} variant="outline">クライアント詳細へ戻る</LinkButton>
                </div>
            </section>
        </AdminPage>
    );
}
