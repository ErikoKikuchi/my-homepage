import { Link } from "@inertiajs/react";
import LinkButton from "@/Components/Admin/UI/LinkButton/LinkButton";
import PageHeader from "@/Components/Admin/UI/PageHeader";
import styles from "./Dashboard.module.css";

type DashboardProps = {
    pendingReservationCount: number;
};

const pendingReservationsUrl = "/pilates/admin/reservations/pending";
const quickMenu = [
    { label: "会場確認待ち", href: pendingReservationsUrl },
    { label: "予約確認・代理予約", href: "/pilates/admin/calendar" },
    { label: "スケジュール管理", href: "/pilates/admin/lesson-slots" },
    { label: "クライアント管理", href: "/pilates/admin/clients" },
    { label: "会計管理", href: "#" },
    { label: "BodyMind管理", href: "#" },
    { label: "場所管理", href: "/pilates/admin/location" },
    { label: "分析", href: "/pilates/admin/analytics" },
];

export default function Dashboard({ pendingReservationCount }: DashboardProps) {
    return (
        <>
            <PageHeader heading="管理者ダッシュボード" />

            <section className={styles.section}>
                <p className={styles.sectionLabel}>クイックメニュー</p>
                <div className={styles.quickMenu}>
                    {quickMenu.map(({ label, href }) => (
                        <LinkButton key={label} href={href} fullReload={href === "#"}>
                            {label}
                        </LinkButton>
                    ))}
                </div>
            </section>

            <section className={styles.section}>
                <p className={styles.sectionLabel}>今日のセッション</p>
            </section>

            <section className={styles.section}>
                <p className={styles.sectionLabel}>やることリスト</p>
                <ul className={styles.taskList}>
                    <li>
                        {pendingReservationCount > 0 ? (
                            <Link
                                href={pendingReservationsUrl}
                                className={styles.taskLink}
                            >
                                会場確認待ちが{pendingReservationCount}件あります
                            </Link>
                        ) : (
                            "会場確認待ちの予約はありません"
                        )}
                    </li>
                </ul>
            </section>

            <section className={styles.section}>
                <p className={styles.sectionLabel}>開発メモ</p>
                <LinkButton
                    href="https://app.notion.com/p/39d63551227080bfb153fc77b48c4341?source=copy_link"
                    external
                >
                    Notionを開く
                </LinkButton>
            </section>
        </>
    );
}
