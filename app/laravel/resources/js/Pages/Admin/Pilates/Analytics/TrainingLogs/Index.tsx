import LinkButton from "@/Components/Admin/UI/LinkButton/LinkButton";
import AdminPage from "../../AdminPage";
import styles from "../../AdminPage.module.css";
import { admin } from "../../schedule";

export default function TrainingLogsAnalyticsIndex() {
    return (
        <AdminPage heading="自主トレログ分析">
            <div className={styles.stack}>
                <p>集計項目は準備中です。</p>
                <LinkButton href={`${admin}/analytics`} variant="outline">分析トップに戻る</LinkButton>
            </div>
        </AdminPage>
    );
}
