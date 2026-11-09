import LinkButton from "@/Components/Admin/UI/LinkButton/LinkButton";
import AdminPage from "../AdminPage";
import styles from "../AdminPage.module.css";
import { admin } from "../schedule";
import type { Location } from "./LocationForm";

const yen = new Intl.NumberFormat("ja-JP");

export function LocationList({ locations, archive = false }: { locations: Location[]; archive?: boolean }) {
    return (
        <AdminPage heading={archive ? "場所アーカイブ管理" : "場所管理"}>
            <div className={styles.stack}>
                <div className={styles.bar}>
                    <h2>{archive ? "場所アーカイブ一覧" : "場所一覧"}</h2>
                    {!archive && <LinkButton href={`${admin}/location/create`}>新規登録</LinkButton>}
                </div>
                {locations.length === 0 && <p>登録されている場所はありません。</p>}
                {locations.map((location) => (
                    <article key={location.id} className={styles.card}>
                        <div>
                            <h2>{location.name}{!location.is_active && "（無効）"}{!location.is_bookable && "（予約不可）"}</h2>
                            <p>{location.address}</p>
                            <p>場所代（経費）：{location.base_fee === null ? "未設定" : `${yen.format(location.base_fee)}円`} ／ 交通費加算：{yen.format(location.price_addon_per_session ?? 0)}円</p>
                        </div>
                        <LinkButton href={`${admin}/location/${location.id}/edit`} variant="outline">編集</LinkButton>
                    </article>
                ))}
                <LinkButton href={`${admin}/${archive ? "location" : "locations/archive"}`} variant="text">
                    {archive ? "場所一覧に戻る" : "アーカイブ一覧"}
                </LinkButton>
            </div>
        </AdminPage>
    );
}

export default function LocationsIndex({ locations }: { locations: Location[] }) {
    return <LocationList locations={locations} />;
}
