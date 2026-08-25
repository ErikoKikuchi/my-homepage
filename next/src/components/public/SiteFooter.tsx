// next/src/components/public/SiteFooter.tsx
import Link from "next/link";
import Image from "next/image";
import styles from "./SiteFooter.module.css";

export default function SiteFooter() {
  return (
    <footer className={styles.siteFooter}>
      <nav className={styles.footerLinks}>
        <Link href="/privacy">個人情報保護方針</Link>
        <Link href="/tokusho">特定商取引法に基づく表記</Link>
        <Link href="/contact">お問い合わせ</Link>
      </nav>
      <Image
        src="/images/footer-pic.png"
        alt="からだ散歩のイメージ"
        width={120}
        height={120}
        className={styles.bottomImage}
      />
      <p className={styles.copyright}>© 2026 からだ散歩</p>
    </footer>
  );
}
