import { useForm } from "@inertiajs/react";
import ActionButton from "@/Components/Admin/UI/ActionButton/ActionButton";
import { FieldError } from "../AdminPage";
import styles from "../AdminPage.module.css";
import { admin, closurePeriodLabels, type ClosurePeriod } from "../schedule";

export type ReservationClosure = {
    date: string;
    period: ClosurePeriod;
};

export default function ReservationClosureSettings({ closures }: { closures: ReservationClosure[] }) {
    const endpoint = `${admin}/reservation-closures`;
    const form = useForm<ReservationClosure>({ date: "", period: "all_day" });
    const removal = useForm({});

    return (
        <section className={styles.stack}>
            <h2>受付不可日の設定</h2>
            <p>同じ日付を選んで保存すると、設定する時間帯を変更できます。</p>
            <form className={styles.form} onSubmit={(event) => {
                event.preventDefault();
                form.post(endpoint, { preserveScroll: true, onSuccess: () => form.reset() });
            }}>
                <div>
                    <label htmlFor="closure-date">日付</label>
                    <input id="closure-date" type="date" required value={form.data.date}
                        onChange={(event) => form.setData("date", event.target.value)} />
                    <FieldError message={form.errors.date} />
                </div>
                <div>
                    <label htmlFor="closure-period">時間帯</label>
                    <select id="closure-period" value={form.data.period}
                        onChange={(event) => form.setData("period", event.target.value as ReservationClosure["period"])}>
                        {Object.entries(closurePeriodLabels).map(([value, label]) => (
                            <option key={value} value={value}>{label}</option>
                        ))}
                    </select>
                    <FieldError message={form.errors.period} />
                </div>
                <div className={styles.actions}>
                    <ActionButton type="submit" disabled={form.processing || removal.processing || !form.data.date}>保存する</ActionButton>
                </div>
            </form>
            {closures.length === 0 && <p>受付不可日は設定されていません。</p>}
            {closures.map(({ date, period }) => (
                <article key={date} className={styles.card}>
                    <p>{date}　{closurePeriodLabels[period]}</p>
                    <div className={styles.actions}>
                        <ActionButton variant="outline" onClick={() => form.setData({ date, period })}>編集</ActionButton>
                        <ActionButton variant="text" className={styles.danger} disabled={removal.processing || form.processing} onClick={() => {
                            if (window.confirm(`${date}の受付不可設定を解除しますか？`)) {
                                removal.delete(`${endpoint}/${date}`, { preserveScroll: true });
                            }
                        }}>解除</ActionButton>
                    </div>
                </article>
            ))}
        </section>
    );
}
