// components/public/start-here/NavCardGrid.tsx
import styles from "./NavCardGrid.module.css";
import NavCard from "./NavCard";
import { navCardItems } from "./navCardData";

export default function NavCardGrid() {
  return (
    <nav className={styles.navGrid}>
      {navCardItems.map((item) => (
        <NavCard key={item.id} item={item} />
      ))}
    </nav>
  );
}
