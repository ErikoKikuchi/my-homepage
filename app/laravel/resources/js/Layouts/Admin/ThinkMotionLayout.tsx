import type { ReactNode } from "react";
import AdminThinkMotionSiteNav from "@/Components/Admin/Common/AdminThinkMotionSiteNav";
import styles from "./ThinkMotionLayout.module.css";

type ThinkMotionLayoutProps = {
    children: ReactNode;
    src?: string;
};

export default function ThinkMotionLayout({
    children,
    src = "/images/footer-pic.png",
}: ThinkMotionLayoutProps) {
    return (
        <>
            <AdminThinkMotionSiteNav />
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
