import AdminPiltesSiteNav from "@/Components/Admin/Common/AdminPiltesSiteNav";
import { Image } from "lucide-react";

export default function PilatesLayout({
    children,
}: {
    children: React.ReactNode;
}) {
    return (
        <>
            <AdminPiltesSiteNav />
            {children}
            <Image
                src="/site-images/footer-pic.png"
                alt="からだ散歩のイメージ"
                width={120}
                height={120}
                className={styles.bottomImage}
            />
            <p className={styles.copyright}>© 2026 からだ散歩</p>
        </>
    );
}
export const metadata: Metadata = {
    openGraph: {
        images: ["/site-images/PilatesOGP.png"],
    },
};
