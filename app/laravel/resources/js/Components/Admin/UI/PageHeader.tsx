import styles from "./PageHeader.module.css";

interface PageHeaderProps {
    label?: string;
    heading: string;
}

export default function PageHeader({ label, heading }: PageHeaderProps) {
    return (
        <header className={styles.pageHeader}>
            {label && <p className={styles.sectionLabel}>{label}</p>}
            <h1>{heading}</h1>
        </header>
    );
}
