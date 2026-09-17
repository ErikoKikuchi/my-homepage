import ArticleListCard from @/components/thinkmotion/article/ArticleListCard;
import type{ArticlePost[]} from @/types/thinkmotion/ArticlePost;
import Link from "next/link";
import styles from "./ArticleListSection.module.css";


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
