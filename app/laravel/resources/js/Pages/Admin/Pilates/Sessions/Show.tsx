import { router } from "@inertiajs/react";
import { useRef, useState, type SubmitEvent } from "react";
import ActionButton from "@/Components/Admin/UI/ActionButton/ActionButton";
import LinkButton from "@/Components/Admin/UI/LinkButton/LinkButton";
import AdminPage, { FieldError } from "../AdminPage";
import ClientInformationTable, { type ClientInformation } from "@/Components/Admin/Pilates/ClientInformationTable";
import styles from "../AdminPage.module.css";
import sessionStyles from "./Sessions.module.css";
import { admin, type NamedOption } from "../schedule";

type SessionExercise = {
    id: string;
    exercise_id: string | null;
    custom_name: string | null;
    comment: string | null;
    position: number;
};

type ExerciseRow = {
    rowId: number;
    exercise_id: string;
    custom_name: string;
    comment: string;
};

function emptyExerciseRow(rowId: number): ExerciseRow {
    return { rowId, exercise_id: "", custom_name: "", comment: "" };
}

type SessionContents = {
    id: string;
    notes: string | null;
    is_draft: boolean;
    exercises: SessionExercise[];
};

type ExerciseOption = NamedOption & {
    supplement: string | null;
    precautions: string | null;
};

type ShowSessionProps = ClientInformation & {
    lesson: { date: string; start_time: string } | null;
    sessionContents: SessionContents;
    draftOnly: boolean;
    reservationConfirmed: boolean;
    exerciseOptions: ExerciseOption[];
};

export default function ShowSession({
    client,
    lesson,
    goals,
    hopes,
    draftOnly,
    reservationConfirmed,
    exerciseOptions = [],
    sessionContents,
}: ShowSessionProps) {
    const [exerciseRows, setExerciseRows] = useState<ExerciseRow[]>(() =>
        sessionContents.exercises.length > 0
            ? sessionContents.exercises.map((exercise, rowId) => ({
                  rowId,
                  exercise_id: exercise.exercise_id ?? "",
                  custom_name: exercise.custom_name ?? "",
                  comment: exercise.comment ?? "",
              }))
            : [emptyExerciseRow(0)],
    );
    const [saving, setSaving] = useState(false);
    const [errors, setErrors] = useState<Record<string, string>>({});
    const nextExerciseRow = useRef(
        Math.max(sessionContents.exercises.length, 1),
    );
    const sessionUrl = `${admin}/sessions/${sessionContents.id}`;

    function updateExerciseRow(
        rowId: number,
        field: keyof Omit<ExerciseRow, "rowId">,
        value: string,
    ) {
        setExerciseRows((rows) =>
            rows.map((row) =>
                row.rowId === rowId ? { ...row, [field]: value } : row,
            ),
        );
    }

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
        const exercises = exerciseRows
            .map(({ exercise_id, custom_name, comment }) => ({
                exercise_id: exercise_id.trim() || null,
                custom_name: custom_name.trim() || null,
                comment: comment.trim() || null,
            }))
            .filter(
                ({ exercise_id, custom_name, comment }) =>
                    exercise_id || custom_name || comment,
            );

        setSaving(true);
        setErrors({});
        router.patch(
            sessionUrl,
            {
                is_draft: saveMode === "draft",
                notes: String(data.get("notes") ?? "").trim() || null,
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
        <AdminPage heading="セッション記録の編集">
            <section className={styles.stack}>
                <header className={sessionStyles.reservationHeading}>
                    <h2>{client.name}</h2>
                    <p>
                        {lesson
                            ? `${lesson.date} ${lesson.start_time.slice(0, 5)}`
                            : "予約情報がありません"}
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
                            defaultValue={sessionContents.notes ?? ""}
                        />
                    </section>
                    <section
                        className={sessionStyles.exerciseList}
                        aria-labelledby="session-exercises"
                    >
                        <h3 id="session-exercises">本日のピラティスの内容</h3>
                        {exerciseRows.map((row) => (
                            <fieldset
                                key={row.rowId}
                                className={sessionStyles.exercise}
                            >
                                <legend>実施項目</legend>
                                <label>
                                    種目
                                    <select
                                        disabled={exerciseOptions.length === 0}
                                        value={row.exercise_id}
                                        onChange={(event) =>
                                            updateExerciseRow(
                                                row.rowId,
                                                "exercise_id",
                                                event.currentTarget.value,
                                            )
                                        }
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
                                        rows={6}
                                        value={row.custom_name}
                                        onChange={(event) =>
                                            updateExerciseRow(
                                                row.rowId,
                                                "custom_name",
                                                event.currentTarget.value,
                                            )
                                        }
                                    />
                                </label>
                                <label
                                    className={sessionStyles.exerciseComment}
                                >
                                    この項目へのコメント
                                    <textarea
                                        rows={6}
                                        value={row.comment}
                                        onChange={(event) =>
                                            updateExerciseRow(
                                                row.rowId,
                                                "comment",
                                                event.currentTarget.value,
                                            )
                                        }
                                    />
                                </label>
                                {exerciseOptions
                                    .filter(({ id }) => id === row.exercise_id)
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
                                                setExerciseRows((rows) =>
                                                    rows.filter(
                                                        ({ rowId }) =>
                                                            rowId !== row.rowId,
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
                                    const row = emptyExerciseRow(
                                        nextExerciseRow.current++,
                                    );
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
                            <p className={styles.muted}>
                                {reservationConfirmed
                                    ? "確定保存は予約当日からできます。"
                                    : "確定保存には確定済みの予約が必要です。"}
                            </p>
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
