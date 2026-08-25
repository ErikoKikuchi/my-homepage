import styles from "./ComingSoon.module.css";

export default function ComingSoon() {
  return (
    <div className={styles.container}>
      <p className={styles.label}>Coming Soon</p>
      <h1 className={styles.heading}>只今準備中です</h1>
      <p className={styles.description}>
        ITを学び始めて。子供に伝えたいことをまとめた場所を、
        <br />
        近日公開予定です。
      </p>
    </div>
  );
}
