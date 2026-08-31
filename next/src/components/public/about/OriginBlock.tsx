import styles from "./OriginBlock.module.css";

interface OriginBlockProps {
  number: string;
  catchcopy: string;
  bodyText: string;
  className?: string;
}

export default function OriginBlock({
  number,
  catchcopy,
  bodyText,
  className,
}: OriginBlockProps) {
  return (
    <div className={`${styles.originBlock} ${className ?? ""}`}>
      <p className={styles.number}>{number}</p>
      <p className={styles.catchcopy}>{catchcopy}</p>
      <p className={styles.bodyText}>{bodyText}</p>
    </div>
  );
}
