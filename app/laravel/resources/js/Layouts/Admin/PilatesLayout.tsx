import type { ReactNode } from "react";
import AdminPilatesSiteNav from "@/Components/Admin/Common/AdminPilatesSiteNav";
import styles from "./PilatesLayout.module.css";

type PilatesLayoutProps = {
    children: ReactNode;
    src?: string;
};

export default function PilatesLayout({
    children,
    src = "/images/footer-pic.png",
}: PilatesLayoutProps) {
    return (
        <>
            <AdminPilatesSiteNav />
            {children}
            <img
                src={src}
                alt="からだ散歩のイメージ"
                width={120}
                height={120}
                className={styles.bottomImage}
            />
            <p className={styles.copyright}>© 2026 からだ散歩</p>
        </>
    );
}
