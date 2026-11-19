import styles from "@/Pages/Admin/Pilates/Sessions/Sessions.module.css";

type InformationEntry = { value: string | null; date?: string | null };

export type ClientInformation = {
    client: { id: string; name: string; body_notes: string | null };
    goals: {
        outlook: string | null;
        goal: string | null;
        created_at?: string | null;
    }[];
    hopes: { hope: string | null; created_at?: string | null }[];
};

const inputDate = new Intl.DateTimeFormat("ja-JP", {
    dateStyle: "medium",
    timeZone: "Asia/Tokyo",
});

export default function ClientInformationTable({
    client,
    goals,
    hopes,
    labelledBy,
    bodyNotes = [{ value: client.body_notes }],
}: ClientInformation & { labelledBy: string; bodyNotes?: InformationEntry[] }) {
    const hopeEntries = new Map<string, InformationEntry>();
    for (const { hope, created_at } of hopes) {
        hopeEntries.set(JSON.stringify([hope, created_at]), { value: hope, date: created_at });
    }

    const fields: { label: string; entries: InformationEntry[] }[] = [
        { label: "身体の特徴・所見", entries: bodyNotes },
        { label: "希望（HOPE）", entries: [...hopeEntries.values()] },
        {
            label: "見通し（Outlook）",
            entries: goals.map(({ outlook, created_at }) => ({ value: outlook, date: created_at })),
        },
        {
            label: "ゴール設定",
            entries: goals.map(({ goal, created_at }) => ({ value: goal, date: created_at })),
        },
    ];

    return (
        <table className={styles.informationTable} aria-labelledby={labelledBy}>
            <tbody>
                {fields.map(({ label, entries }) => {
                    const filled = entries.filter(({ value }) => value?.trim());

                    return (
                        <tr key={label}>
                            <th scope="row">{label}</th>
                            <td>
                                {filled.length === 0 ? "未登録" : filled.map(({ value, date }, index) => (
                                    <div key={index}>
                                        {date && (
                                            <strong>
                                                <time dateTime={date}>{inputDate.format(new Date(date))}</time>
                                                {"　"}
                                            </strong>
                                        )}
                                        {value}
                                    </div>
                                ))}
                            </td>
                        </tr>
                    );
                })}
            </tbody>
        </table>
    );
}
