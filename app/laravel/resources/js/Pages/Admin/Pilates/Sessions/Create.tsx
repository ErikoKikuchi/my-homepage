import { useRef, useState } from "react";
import ActionButton from "@/Components/Admin/UI/ActionButton/ActionButton";
import LinkButton from "@/Components/Admin/UI/LinkButton/LinkButton";
import AdminPage from "../AdminPage";
import styles from "../AdminPage.module.css";
import sessionStyles from "./Sessions.module.css";
import { admin, type NamedOption } from "../schedule";

type CreateSessionProps = {
    client: NamedOption & { body_notes: string | null };
    reservation: { id: string; date: string; startTime: string };
    goals: { id: string; hope: string | null; outlook: string | null; goal: string | null }[];
    hopes: { id: string; hope: string | null }[];
    exerciseOptions?: NamedOption[];
};

export default function CreateSession({ client, reservation, goals, hopes, exerciseOptions = [] }: CreateSessionProps) {
    const [editingInformation, setEditingInformation] = useState(false);
    const [exerciseRows, setExerciseRows] = useState([0]);
    const nextExerciseRow = useRef(1);
    const informationFields = [
        { label: "身体の特徴・初見", entries: [{ name: "body_notes", value: client.body_notes }] },
        { label: "希望（HOPE）", entries: [
            ...hopes.map(({ id, hope }) => ({ name: `hopes[${id}][hope]`, value: hope })),
            ...goals.filter(({ hope }) => hope).map(({ id, hope }) => ({ name: `goals[${id}][hope]`, value: hope })),
        ] },
        { label: "見通し（Outlook）", entries: goals.map(({ id, outlook }) => ({ name: `goals[${id}][outlook]`, value: outlook })) },
        { label: "ゴール設定", entries: goals.map(({ id, goal }) => ({ name: `goals[${id}][goal]`, value: goal })) },
    ];

    return (
        <AdminPage heading="セッション記録の作成">
            <section className={styles.stack}>
                <header className={sessionStyles.reservationHeading}>
                    <h2>{client.name}</h2>
                    <p>{reservation.date} {reservation.startTime.slice(0, 5)}</p>
                </header>
                <div className={styles.form}>
                    <div className={styles.actions}>
                        <h3 id="registered-information">登録済みの身体情報・ゴール設定</h3>
                        <ActionButton variant="text" aria-expanded={editingInformation} aria-controls="registered-information-table"
                            onClick={() => setEditingInformation((editing) => !editing)}>
                            {editingInformation ? "編集を閉じる" : "編集"}
                        </ActionButton>
                    </div>
                    <table id="registered-information-table"
                        className={`${sessionStyles.informationTable} ${sessionStyles.editableInformation}`}
                        aria-labelledby="registered-information">
                        <tbody>
                            {informationFields.map(({ label, entries }, index) => (
                                <tr key={label}>
                                    <th scope="row">{label}</th>
                                    <td>
                                        <span hidden={editingInformation}>
                                            {entries.map(({ value }) => value).filter(Boolean).join("\n") || "未登録"}
                                        </span>
                                        <div hidden={!editingInformation}>
                                            {(entries.length ? entries : [{ name: `new_information[${index}]`, value: null }])
                                                .map(({ name, value }, entryIndex) => (
                                                    <textarea key={name} name={name}
                                                        aria-label={entries.length > 1 ? `${label} ${entryIndex + 1}` : label}
                                                        rows={2} defaultValue={value ?? ""} />
                                                ))}
                                        </div>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                    {editingInformation && <p className={styles.muted}>登録済み情報の変更を保存する機能は準備中です。</p>}
                    <section className={styles.stack} aria-labelledby="session-findings">
                        <h3 id="session-findings">本日の身体所見</h3>
                        <textarea name="notes" rows={6} aria-labelledby="session-findings" />
                    </section>
                    <section className={styles.stack} aria-labelledby="session-exercises">
                        <h3 id="session-exercises">本日のピラティスの内容</h3>
                        {exerciseRows.map((row) => (
                            <fieldset key={row} className={sessionStyles.exercise}>
                                <legend>実施項目</legend>
                                <label>
                                    種目
                                    <select name={`exercises[${row}][exercise_id]`} disabled={exerciseOptions.length === 0}>
                                        <option value="">{exerciseOptions.length === 0 ? "選択できる種目は未登録" : "種目を選択"}</option>
                                        {exerciseOptions.map(({ id, name }) => <option key={id} value={id}>{name}</option>)}
                                    </select>
                                </label>
                                <label>
                                    実施内容（自由記入）
                                    <input name={`exercises[${row}][custom_name]`} />
                                </label>
                                <label>
                                    この項目へのコメント
                                    <textarea name={`exercises[${row}][comment]`} rows={2} />
                                </label>
                                {exerciseRows.length > 1 && (
                                    <div className={styles.actions}>
                                        <ActionButton variant="text" onClick={() => setExerciseRows(exerciseRows.filter((id) => id !== row))}>
                                            この項目を削除
                                        </ActionButton>
                                    </div>
                                )}
                            </fieldset>
                        ))}
                        <div className={styles.actions}>
                            <ActionButton variant="outline" onClick={() => {
                                const row = nextExerciseRow.current++;
                                setExerciseRows((rows) => [...rows, row]);
                            }}>実施項目を追加</ActionButton>
                        </div>
                    </section>
                </div>
                <div className={styles.actions}>
                    <LinkButton href={`${admin}/dashboard`} variant="outline">ダッシュボードに戻る</LinkButton>
                    <LinkButton href={`${admin}/clients/${client.id}/sessions`} variant="text">記録一覧</LinkButton>
                </div>
            </section>
        </AdminPage>
    );
}
