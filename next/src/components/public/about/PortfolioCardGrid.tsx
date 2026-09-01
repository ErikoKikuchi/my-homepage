import styles from "./PortfolioCardGrid.module.css";
import PortfolioCard from "./PortfolioCard";
import { PortfolioItems } from "./portfolioData";

export default function PortfolioCardGrid() {
  return (
    <nav className={styles.portfolioGrid}>
      {PortfolioItems.map((item) => (
        <PortfolioCard key={item.cardTag} item={item} />
      ))}
    </nav>
  );
}
