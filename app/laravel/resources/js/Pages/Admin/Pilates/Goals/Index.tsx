import LinkButton from "@/Components/Admin/UI/LinkButton/LinkButton";
import AdminPage from "../AdminPage";
import styles from "../AdminPage.module.css";
import ClientInformationTable, {
    type ClientInformation,
} from "../ClientInformationTable";
import { admin } from "../schedule";

export default function GoalsIndex({ client, goals, hopes }: ClientInformation) {
    return (
        <AdminPage heading="ゴール設定">
            <section className={styles.stack}>
                <h2 id="client-information">{client.name}の身体情報・ゴール設定</h2>
                <ClientInformationTable
                    client={client}
                    goals={goals}
                    hopes={hopes}
                    labelledBy="client-information"
                />
                <div className={styles.actions}>
                    <LinkButton href={`${admin}/clients/${client.id}`} variant="outline">
                        クライアント詳細へ戻る
                    </LinkButton>
                </div>
            </section>
        </AdminPage>
    );
}
