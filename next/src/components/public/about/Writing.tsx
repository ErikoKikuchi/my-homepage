import styles from "./Writing.module.css";

interface WritingProps {
  linkName: string;
  href: string;
  linkDesc: string;
  className?: string;
}

export default function WritingBlock({
  linkName,
  href,
  linkDesc,
  className,
}: WritingProps) {
  return (
    <div className={`${styles.writingBlock} ${className ?? ""}`}>
      <a href={href} className={styles.linkItem} target="_blank" rel="noopener">
        <div className={styles.linkLabel}>
          <p className={styles.linkName}>{linkName}</p>
          <p className={styles.linkDesc}>{linkDesc}</p>
        </div>
      </a>
    </div>
  );
}
