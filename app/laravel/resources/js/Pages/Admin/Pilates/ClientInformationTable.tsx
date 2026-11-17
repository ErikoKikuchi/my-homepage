import styles from "./Sessions/Sessions.module.css";

export type ClientInformation = {
    client: { id: string; name: string; body_notes: string | null };
    goals: {
        hope: string | null;
        outlook: string | null;
        goal: string | null;
    }[];
    hopes: { hope: string | null }[];
};

export default function ClientInformationTable({
    client,
    goals,
    hopes,
    labelledBy,
}: ClientInformation & { labelledBy: string }) {
    const fields = [
        { label: "身体の特徴・所見", values: [client.body_notes] },
        {
            label: "希望（HOPE）",
            values: [...new Set([
                ...hopes.map(({ hope }) => hope),
                ...goals.map(({ hope }) => hope),
            ])],
        },
        {
            label: "見通し（Outlook）",
            values: goals.map(({ outlook }) => outlook),
        },
        {
            label: "ゴール設定",
            values: goals.map(({ goal }) => goal),
        },
    ];

    return (
        <table className={styles.informationTable} aria-labelledby={labelledBy}>
            <tbody>
                {fields.map(({ label, values }) => (
                    <tr key={label}>
                        <th scope="row">{label}</th>
                        <td>{values.filter(Boolean).join("\n") || "未登録"}</td>
                    </tr>
                ))}
            </tbody>
        </table>
    );
}
