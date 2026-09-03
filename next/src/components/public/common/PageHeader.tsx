import styles from "./PageHeader.module.css";

interface PageHeaderProps {
  label?: string;
  heading: string;
  lead?: string;
}

export default function PageHeader({ label, heading, lead }: PageHeaderProps) {
  return (
    <header className={styles.pageHeader}>
      {label && <p className={styles.sectionLabel}>{label}</p>}
      <h1>{heading}</h1>
      {lead && <p className={styles.lead}>{lead}</p>}
    </header>
  );
}
