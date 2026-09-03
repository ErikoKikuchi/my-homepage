// app/(public)/code/page.tsx
import ComingSoon from "@/components/public/common/ComingSoon";
import styles from "./page.module.css";

export default function CodePage() {
  return (
    <>
      <ComingSoon />
      <p className={styles.description}>
        ITを学び始めて。子供に伝えたいことをまとめた場所を、
        <br />
        近日公開予定です。
      </p>
    </>
  );
}
