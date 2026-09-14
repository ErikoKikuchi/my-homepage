import PageHeader from "@/components/public/common/PageHeader";
import Section from "@/components/public/common/Section";
import styles from "./page.module.css";

export default function PilatesTopPage() {
  return (
    <>
      <main className={styles.main}>
        <PageHeader
          label="Pilates"
          heading={"身体を構造から読み解き、\n自分で扱える状態へ。"}
          lead="その「なぜ」に、向き合います。"
        ></PageHeader>
        <Section
          label="Concept"
          className={styles.fadeSection}
          labelClassName={styles.sectionLabelDecorated}
          animationDelay="0.2s"
        >
          <div className={styles.pilatesDescription}>
            <p>まずは、今の身体の状態を一緒に整理します。</p>
            <p>痛みや違和感だけでなく、全身のつながりから原因を探ります。</p>
            <p>その上でコンディションを整え、</p>
            <p>ピラティスを通して「動ける状態」をつくります。</p>
          </div>
          <p className={styles.recommend}>こんな方に向いています</p>
          <ul className={styles.recommendTable}>
            <li className={styles.recommendList}>
              ・マッサージでは解決しきらない、身体の重さや凝りがある方
            </li>
            <li className={styles.recommendList}>
              ・自分の身体の使い方を知りたい方
            </li>
            <li className={styles.recommendList}>
              ・理屈も含めて理解しながら動きたい方
            </li>
          </ul>
        </Section>
        <Section
          label="Price"
          className={styles.fadeSection}
          labelClassName={styles.sectionLabelDecorated}
          animationDelay="0.2s"
        >
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
            <p className={styles.checkPoint}>
              グループセッション（最大4名）は料金÷人数
            </p>
            <p className={styles.checkPoint}>
              ※ 継続して身体の変化を見ていくことをおすすめしています。
            </p>
          </div>
        </Section>
      </main>
    </>
  );
}
