import LinkButton from "@/Components/Admin/UI/LinkButton/LinkButton";
import AdminPage from "../AdminPage";
import styles from "../AdminPage.module.css";
import { admin } from "../schedule";

const sections = [
    { path: "reservations", label: "予約" },
    { path: "accounting", label: "会計" },
    { path: "training-logs", label: "自主トレログ" },
];

export default function AnalyticsIndex() {
    return (
        <AdminPage heading="分析">
            <nav className={styles.actions} aria-label="分析メニュー">
                {sections.map(({ path, label }) => (
                    <LinkButton key={path} href={`${admin}/analytics/${path}`}>{label}</LinkButton>
                ))}
            </nav>
        </AdminPage>
    );
}
