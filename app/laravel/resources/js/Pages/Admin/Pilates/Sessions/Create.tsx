import { router } from "@inertiajs/react";
import { useRef, useState, type SubmitEvent } from "react";
import ActionButton from "@/Components/Admin/UI/ActionButton/ActionButton";
import LinkButton from "@/Components/Admin/UI/LinkButton/LinkButton";
import AdminPage, { FieldError } from "../AdminPage";
import ClientInformationTable, { type ClientInformation } from "@/Components/Admin/Pilates/ClientInformationTable";
import styles from "../AdminPage.module.css";
import sessionStyles from "./Sessions.module.css";
import { admin, type NamedOption } from "../schedule";

type CreateSessionProps = ClientInformation & {
    reservation: { id: string; date: string; startTime: string };
    draftOnly: boolean;
    exerciseOptions?: (NamedOption & {
        supplement: string | null;
        precautions: string | null;
    })[];
};

export default function CreateSession({
    client,
    reservation,
    goals,
    hopes,
    draftOnly,
    exerciseOptions = [],
}: CreateSessionProps) {
    const [exerciseRows, setExerciseRows] = useState([0]);
    const [selectedExercises, setSelectedExercises] = useState<
        Record<number, string>
    >({});
    const [saving, setSaving] = useState(false);
    const [errors, setErrors] = useState<Record<string, string>>({});
    const nextExerciseRow = useRef(1);
    const sessionsUrl = `${admin}/clients/${client.id}/sessions`;

    function saveSession(event: SubmitEvent<HTMLFormElement>) {
        event.preventDefault();
        if (saving) return;

        const submitter = event.nativeEvent.submitter;

        const saveMode =
            submitter instanceof HTMLButtonElement ? submitter.value : null;

        if (saveMode !== "draft" && saveMode !== "confirmed") {
            return;
        }
        const data = new FormData(event.currentTarget);
        const readText = (name: string) =>
            String(data.get(name) ?? "").trim() || null;
        const exercises = exerciseRows
            .map((row) => ({
                exercise_id: readText(`exercises[${row}][exercise_id]`),
                custom_name: readText(`exercises[${row}][custom_name]`),
                comment: readText(`exercises[${row}][comment]`),
            }))
            .filter(
                ({ exercise_id, custom_name, comment }) =>
                    exercise_id || custom_name || comment,
            );

        setSaving(true);
        setErrors({});
        router.post(
            sessionsUrl,
            {
                reservation_id: reservation.id,
                is_draft: saveMode === "draft",
                notes: readText("notes"),
                exercises,
            },
            {
                preserveScroll: true,
                onError: setErrors,
                onFinish: () => setSaving(false),
            },
        );
    }

    return (
        <AdminPage heading="セッション記録の作成">
            <section className={styles.stack}>
                <header className={sessionStyles.reservationHeading}>
                    <h2>{client.name}</h2>
                    <p>
                        {reservation.date} {reservation.startTime.slice(0, 5)}
                    </p>
                </header>
                <form className={styles.form} onSubmit={saveSession}>
                    <div className={styles.actions}>
                        <h3 id="registered-information">
                            登録済みの身体情報・ゴール設定
                        </h3>
                        <LinkButton
                            href={`${admin}/clients/${client.id}/goals`}
                            variant="text"
                        >
                            編集
                        </LinkButton>
                    </div>
                    <ClientInformationTable
                        client={client}
                        goals={goals}
                        hopes={hopes}
                        labelledBy="registered-information"
                    />
                    <section
                        className={styles.stack}
                        aria-labelledby="session-findings"
                    >
                        <h3 id="session-findings">本日の身体所見</h3>
                        <textarea
                            name="notes"
                            rows={6}
                            aria-labelledby="session-findings"
                        />
                    </section>
                    <section
                        className={sessionStyles.exerciseList}
                        aria-labelledby="session-exercises"
                    >
                        <h3 id="session-exercises">本日のピラティスの内容</h3>
                        {exerciseRows.map((row) => (
                            <fieldset
                                key={row}
                                className={sessionStyles.exercise}
                            >
                                <legend>実施項目</legend>
                                <label>
                                    種目
                                    <select
                                        name={`exercises[${row}][exercise_id]`}
                                        disabled={exerciseOptions.length === 0}
                                        value={selectedExercises[row] ?? ""}
                                        onChange={(event) => {
                                            const id = event.target.value;
                                            setSelectedExercises((current) => ({
                                                ...current,
                                                [row]: id,
                                            }));
                                        }}
                                    >
                                        <option value="">
                                            {exerciseOptions.length === 0
                                                ? "選択できる種目は未登録"
                                                : "種目を選択"}
                                        </option>
                                        {exerciseOptions.map(({ id, name }) => (
                                            <option key={id} value={id}>
                                                {name}
                                            </option>
                                        ))}
                                    </select>
                                </label>
                                <label>
                                    実施内容（自由記入）
                                    <textarea
                                        name={`exercises[${row}][custom_name]`}
                                        rows={1}
                                    />
                                </label>
                                <label
                                    className={sessionStyles.exerciseComment}
                                >
                                    この項目へのコメント
                                    <textarea
                                        name={`exercises[${row}][comment]`}
                                        rows={1}
                                    />
                                </label>
                                {exerciseOptions
                                    .filter(
                                        ({ id }) =>
                                            id === selectedExercises[row],
                                    )
                                    .map(
                                        ({ id, supplement, precautions }) =>
                                            (supplement || precautions) && (
                                                <div
                                                    key={id}
                                                    className={
                                                        sessionStyles.exerciseDetails
                                                    }
                                                >
                                                    {supplement && (
                                                        <p>
                                                            補足：{supplement}
                                                        </p>
                                                    )}
                                                    {precautions && (
                                                        <p>
                                                            注意事項：
                                                            {precautions}
                                                        </p>
                                                    )}
                                                </div>
                                            ),
                                    )}
                                {exerciseRows.length > 1 && (
                                    <div
                                        className={
                                            sessionStyles.exerciseActions
                                        }
                                    >
                                        <ActionButton
                                            variant="text"
                                            onClick={() =>
                                                setExerciseRows(
                                                    exerciseRows.filter(
                                                        (id) => id !== row,
                                                    ),
                                                )
                                            }
                                        >
                                            この項目を削除
                                        </ActionButton>
                                    </div>
                                )}
                            </fieldset>
                        ))}
                        <div className={styles.actions}>
                            <ActionButton
                                variant="outline"
                                onClick={() => {
                                    const row = nextExerciseRow.current++;
                                    setExerciseRows((rows) => [...rows, row]);
                                }}
                            >
                                実施項目を追加
                            </ActionButton>
                        </div>
                    </section>
                    {Object.keys(errors).length > 0 && (
                        <div role="group" aria-label="保存時の入力エラー">
                            {Object.entries(errors).map(([field, message]) => (
                                <FieldError key={field} message={message} />
                            ))}
                        </div>
                    )}
                    <div className={styles.actions}>
                        <ActionButton
                            type="submit"
                            name="save_mode"
                            value="draft"
                            variant="outline"
                            disabled={saving}
                        >
                            下書き保存
                        </ActionButton>
                        {draftOnly ? (
                            <p className={styles.muted}>確定保存は予約当日からできます。</p>
                        ) : (
                            <ActionButton
                                type="submit"
                                name="save_mode"
                                value="confirmed"
                                disabled={saving}
                            >
                                確定保存
                            </ActionButton>
                        )}
                    </div>
                </form>
                <div className={styles.actions}>
                    <LinkButton href={`${admin}/dashboard`} variant="outline">
                        ダッシュボードに戻る
                    </LinkButton>
                    <LinkButton
                        href={`${admin}/clients/${client.id}/sessions`}
                        variant="text"
                    >
                        記録一覧
                    </LinkButton>
                </div>
            </section>
        </AdminPage>
    );
}
