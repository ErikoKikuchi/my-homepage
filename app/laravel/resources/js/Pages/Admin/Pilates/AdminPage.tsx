import { usePage } from "@inertiajs/react";
import type { ReactNode } from "react";
import PageHeader from "@/Components/Admin/UI/PageHeader";
import styles from "./AdminPage.module.css";

type Flash = { message?: string; success?: string; error?: string };

export default function AdminPage({ heading, children }: { heading: string; children?: ReactNode }) {
    const { flash } = usePage<{ flash?: Flash }>().props;
    const message = flash?.success ?? flash?.message;

    return (
        <>
            <PageHeader heading={heading} />
            <main className={styles.page}>
                {message && <p className={styles.notice} role="status">{message}</p>}
                {flash?.error && <p className={styles.error} role="alert">{flash.error}</p>}
                {children}
            </main>
        </>
    );
}

export function FieldError({ message }: { message?: string }) {
    return message ? <p className={styles.error} role="alert">{message}</p> : null;
}
