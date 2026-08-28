// components/public/start-here/NavCard.tsx
import Link from "next/link";
import styles from "./NavCard.module.css";
import type { NavCardItem } from "./navCardData";

interface NavCardProps {
  item: NavCardItem;
}

export default function NavCard({ item }: NavCardProps) {
  return (
    <Link href={item.href} className={styles.navCard}>
      <p className={styles.navCardName}>{item.name}</p>
      {item.description.map((line, i) => (
        <p key={i} className={styles.navCardDesc}>
          {line}
        </p>
      ))}
    </Link>
  );
}
