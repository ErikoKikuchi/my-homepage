import { useForm } from "@inertiajs/react";
import { useState } from "react";
import ActionButton from "@/Components/Admin/UI/ActionButton/ActionButton";
import LinkButton from "@/Components/Admin/UI/LinkButton/LinkButton";
import AdminPage, { FieldError } from "../AdminPage";
import styles from "../AdminPage.module.css";
import photoStyles from "./Index.module.css";
import { admin } from "../schedule";

type Exercise = { id: string; name: string; supplement: string | null; precautions: string | null; photos: (string | null)[] };
const url = `${admin}/exercises`;
const photoSlots = [0, 1, 2];

function ExerciseForm({ exercise, onClose }: { exercise: Exercise | null; onClose: () => void }) {
    const form = useForm({
        name: exercise?.name ?? "",
        supplement: exercise?.supplement ?? "",
        precautions: exercise?.precautions ?? "",
        photos: [null, null, null] as (File | null)[],
        remove_photos: [false, false, false],
    });

    return (
        <form className={styles.form} onSubmit={(event) => {
            event.preventDefault();
            if (form.processing) return;
            const options = { forceFormData: true, preserveScroll: true, onSuccess: onClose };
            if (exercise) {
                form.transform((data) => ({ ...data, _method: "PATCH" }));
                form.post(`${url}/${exercise.id}`, options);
            } else {
                form.post(url, options);
            }
        }}>
            <label>種目名
                <input required maxLength={255} value={form.data.name} onChange={(event) => form.setData("name", event.target.value)} />
            </label>
            <FieldError message={form.errors.name} />
            <label>補足
                <textarea rows={3} maxLength={16000} value={form.data.supplement} onChange={(event) => form.setData("supplement", event.target.value)} />
            </label>
            <FieldError message={form.errors.supplement} />
            <label>注意事項
                <textarea rows={3} maxLength={16000} value={form.data.precautions} onChange={(event) => form.setData("precautions", event.target.value)} />
            </label>
            <FieldError message={form.errors.precautions} />
            <fieldset className={styles.stack}>
                <legend>写真（最大3枚）</legend>
                <p className={styles.muted}>JPEG・PNG・WebP、1枚5MBまで</p>
                <FieldError message={form.errors.photos} />
                {photoSlots.map((slot) => {
                    const photo = exercise?.photos[slot];
                    return (
                        <div key={slot}>
                            {photo && <img className={photoStyles.thumbnail} src={photo} alt={`登録済みの写真 ${slot + 1}`} />}
                            {photo && (
                                <label>
                                    <input type="checkbox" checked={form.data.remove_photos[slot]}
                                        disabled={form.processing || form.data.photos[slot] !== null}
                                        onChange={(event) => {
                                            const checked = event.target.checked;
                                            form.setData("remove_photos", form.data.remove_photos.map((value, index) => index === slot ? checked : value));
                                        }} />
                                    この写真を削除
                                </label>
                            )}
                            <label>写真 {slot + 1}{photo && "（差し替え）"}
                                <input type="file" accept="image/jpeg,image/png,image/webp" disabled={form.processing || form.data.remove_photos[slot]}
                                    onChange={(event) => {
                                        const file = event.target.files?.[0] ?? null;
                                        form.setData("photos", form.data.photos.map((value, index) => index === slot ? file : value));
                                    }} />
                            </label>
                            <FieldError message={form.errors[`photos.${slot}`]} />
                        </div>
                    );
                })}
            </fieldset>
            <div className={styles.actions}>
                <ActionButton type="submit" disabled={form.processing}>保存</ActionButton>
                <ActionButton variant="text" disabled={form.processing} onClick={onClose}>キャンセル</ActionButton>
            </div>
        </form>
    );
}

export default function ExercisesIndex({ exercises }: { exercises: Exercise[] }) {
    const [formTarget, setFormTarget] = useState<Exercise | null | undefined>(undefined);
    const formOpen = formTarget !== undefined;

    return (
        <AdminPage heading="ピラティス種目管理">
            <div className={styles.stack}>
                <div className={styles.actions}>
                    <ActionButton onClick={() => setFormTarget(null)} disabled={formOpen}>種目を登録</ActionButton>
                    <LinkButton href={`${admin}/dashboard`} variant="text">ダッシュボードに戻る</LinkButton>
                </div>
                {formTarget === null && <ExerciseForm exercise={null} onClose={() => setFormTarget(undefined)} />}
                {exercises.length === 0 && <p>登録済みの種目はありません。</p>}
                <ul className={styles.stack}>
                    {exercises.map((exercise) => {
                        const cover = exercise.photos.find(Boolean);
                        return (
                            <li key={exercise.id}>
                                <div className={styles.card}>
                                    <div className={styles.actions}>
                                        {cover && <img className={photoStyles.thumbnail} src={cover} alt="" loading="lazy" />}
                                        <h2>{exercise.name}</h2>
                                    </div>
                                    <ActionButton variant="text" disabled={formOpen} onClick={() => setFormTarget(exercise)}>編集</ActionButton>
                                </div>
                                {formTarget?.id === exercise.id && <ExerciseForm exercise={exercise} onClose={() => setFormTarget(undefined)} />}
                            </li>
                        );
                    })}
                </ul>
            </div>
        </AdminPage>
    );
}
