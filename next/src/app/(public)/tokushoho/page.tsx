// next/src/app/(public)/tokushoho/page.tsx
import { tokushohoItems } from "@/components/public/tokushoho/tokushohoData";
import styles from "./page.module.css";

export default function TokushohoPage() {
  return (
    <main className={styles.tokushohoMain}>
      <h1 className={styles.pageTitle}>特定商取引法に基づく表記</h1>
      <dl className={styles.itemList}>
        {tokushohoItems.map((item) => (
          <div key={item.label} className={styles.itemRow}>
            <dt className={styles.itemLabel}>{item.label}</dt>
            <dd className={styles.itemValue}>
              {item.value.split("\n").map((line, i) => (
                <span key={i}>
                  {line}
                  <br />
                </span>
              ))}
            </dd>
          </div>
        ))}
      </dl>
    </main>
  );
}
