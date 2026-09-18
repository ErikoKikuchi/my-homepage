import styles from "./Price.module.css";

export default function PriceTable() {
  return (
    <>
      <p className={styles.time}>1回 60分</p>
      <div className={styles.priceList}>
        <div className={styles.priceRow}>
          <span className={styles.priceItem}>1回券</span>
          <span className={styles.priceAmount}>¥5,000</span>
        </div>
        <div className={styles.priceList}>
          <div className={styles.priceRow}>
            <span className={styles.priceItem}>3回券</span>
            <span className={styles.priceAmount}>¥14,000</span>
          </div>
        </div>
        <div className={styles.priceList}>
          <div className={styles.priceRow}>
            <span className={styles.priceItem}>10回券</span>
            <span className={styles.priceAmount}>¥45,000</span>
          </div>
        </div>
      </div>
      <div className={styles.commentList}>
        <p className={styles.checkPoint}>
          グループセッション（最大4名）は料金÷人数
        </p>
        <p className={styles.checkPoint}>
          苫小牧業務提携先での実施の際は上記料金に1回につき一律2,000円加算させていただいています。
        </p>
        <p className={styles.checkPoint}>
          ※ 継続して身体の変化を見ていくことをおすすめしています。
        </p>
      </div>
    </>
  );
}
