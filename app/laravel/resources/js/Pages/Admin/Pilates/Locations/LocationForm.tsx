import { useForm } from "@inertiajs/react";
import LinkButton from "@/Components/Admin/UI/LinkButton/LinkButton";
import ActionButton from "@/Components/Admin/UI/ActionButton/ActionButton";
import { FieldError } from "../AdminPage";
import styles from "../AdminPage.module.css";
import { admin } from "../schedule";

export type Location = {
    id: string;
    name: string;
    address: string | null;
    base_fee: number | null;
    price_addon_per_session: number | null;
    map_url: string | null;
    is_active: boolean;
    is_bookable: boolean;
};

const fields = [
    { name: "name", label: "名称（必須）", type: "text", required: true },
    { name: "address", label: "住所（必須）", type: "text", required: true },
    { name: "base_fee", label: "場所代", type: "number" },
    {
        name: "price_addon_per_session",
        label: "交通費加算（円）",
        type: "number",
    },
    { name: "map_url", label: "地図URL", type: "url" },
] as const;

export default function LocationForm({ location }: { location?: Location }) {
    const form = useForm({
        name: location?.name ?? "",
        address: location?.address ?? "",
        base_fee: location?.base_fee?.toString() ?? "",
        price_addon_per_session:
            location?.price_addon_per_session?.toString() ?? "0",
        map_url: location?.map_url ?? "",
        is_bookable: location?.is_bookable ?? true,
    });
    const index = `${admin}/location`;

    return (
        <form
            className={styles.form}
            onSubmit={(event) => {
                event.preventDefault();
                form.transform((data) => ({
                    ...data,
                    price_addon_per_session:
                        data.price_addon_per_session || "0",
                }));
                if (location) form.put(`${index}/${location.id}`);
                else form.post(index);
            }}
        >
            {fields.map(({ name, label, type, ...options }) => (
                <div key={name}>
                    <label htmlFor={name}>{label}</label>
                    <input
                        id={name}
                        type={type}
                        min={type === "number" ? 0 : undefined}
                        required={"required" in options}
                        value={form.data[name]}
                        onChange={(event) =>
                            form.setData(name, event.target.value)
                        }
                    />
                    <FieldError message={form.errors[name]} />
                </div>
            ))}
            <label>
                <input
                    type="checkbox"
                    checked={form.data.is_bookable}
                    onChange={(event) =>
                        form.setData("is_bookable", event.target.checked)
                    }
                />
                予約可能にする
            </label>
            <FieldError message={form.errors.is_bookable} />
            <div className={styles.actions}>
                <ActionButton type="submit" disabled={form.processing}>
                    {location ? "更新する" : "登録する"}
                </ActionButton>
                <LinkButton href={index} variant="outline">
                    一覧に戻る
                </LinkButton>
            </div>
        </form>
    );
}
