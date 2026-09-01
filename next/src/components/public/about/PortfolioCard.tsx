import styles from "./PortfolioCard.module.css";
import type { PortfolioItem } from "./portfolioData";

interface PortfolioCardProps {
  item: PortfolioItem;
  className?: string;
}

export default function PortfolioCard({ item, className }: PortfolioCardProps) {
  return (
    <div className={`${styles.portfolioCard} ${className ?? ""}`}>
      <a
        href={item.href}
        className={styles.portfolioCardLink}
        target="_blank"
        rel="noopener"
      >
        <p className={styles.cardTag}>{item.cardTag}</p>
        <p className={styles.cardTitle}>{item.cardTitle}</p>
        <p className={styles.cardDesc}>{item.cardDesc}</p>
        {item.stackTags && item.stackTags.length > 0 && (
          <div className={styles.cardStack}>
            {item.stackTags.map((tag) => (
              <span key={tag} className={styles.stackTag}>
                {tag}
              </span>
            ))}
          </div>
        )}
      </a>
    </div>
  );
}
