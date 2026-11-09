import { useForm } from "@inertiajs/react";
import ActionButton from "@/Components/Admin/UI/ActionButton/ActionButton";
import AdminPage from "../AdminPage";
import styles from "../AdminPage.module.css";
import { admin } from "../schedule";
import LocationForm, { type Location } from "./LocationForm";

export default function LocationsEdit({ location }: { location: Location }) {
    const mutation = useForm({});
    const id = location.id;

    return (
        <AdminPage heading="場所情報の編集">
            <LocationForm location={location} />
            <div className={styles.actions}>
                <ActionButton variant="outline" disabled={mutation.processing} onClick={() => {
                    if (!location.is_active || window.confirm("この場所をアーカイブしますか？")) {
                        mutation.patch(`${admin}/locations/${id}/${location.is_active ? "archive" : "restore"}`);
                    }
                }}>{location.is_active ? "アーカイブする" : "有効にする"}</ActionButton>
                <ActionButton variant="text" className={styles.danger} disabled={mutation.processing} onClick={() => {
                    if (window.confirm("この場所を削除しますか？")) mutation.delete(`${admin}/location/${id}`);
                }}>削除する</ActionButton>
            </div>
        </AdminPage>
    );
}
