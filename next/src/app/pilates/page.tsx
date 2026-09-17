import PageHeader from "@/components/public/common/PageHeader";
import Section from "@/components/public/common/Section";
import PriceTable from "@/components/pilates/top/Price";
import PilatesReservation from "@/components/pilates/top/PilatesReservation";
import styles from "./page.module.css";
import LinkButton from "@/components/ui/LinkButton/LinkButton";

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
            <p>「なんとなく不調」を、そのままにしない。</p>
            <br />
            <p>
              身体の状態を観察して、
              <br /> 動き方や身体のつながりから
              <br />
              「なぜ今こうなっているのか」を一緒に考えます。
            </p>
            <br />
            <br />
            <p>
              仮説を立てて、動いてみる。
              <br />
              変化を感じて、また確かめる。
            </p>
            <br />
            <br />
            <p>
              そうやって自分の身体への理解を深め
              <br />
              少しずつ「自分で扱える身体」へ。
            </p>
            <br />
          </div>
          <p className={styles.recommend}>こんな方に向いています</p>
          <ul className={styles.recommendTable}>
            <li className={styles.recommendList}>
              ・痛みや違和感の「理由」を知りたい
            </li>
            <li className={styles.recommendList}>
              ・自分の身体のことを、自分でも説明できるようになりたい
            </li>
            <li className={styles.recommendList}>
              ・言われた通りに動くのではなく、納得して身体を動かしたい
            </li>
            <li className={styles.recommendList}>
              ・その場だけ楽になるのではなく、自分で調整できるようになりたい
            </li>
          </ul>
        </Section>
        <Section
          label="Price"
          className={styles.fadeSection}
          labelClassName={styles.sectionLabelDecorated}
          animationDelay="0.2s"
        >
          <PriceTable></PriceTable>
        </Section>
        <Section
          label="Location"
          className={styles.fadeSection}
          labelClassName={styles.sectionLabelDecorated}
          animationDelay="0.2s"
        >
          <p className={styles.location}>
            北海道安平町及び苫小牧駅周辺エリアで実施中。
          </p>
          <p className={styles.location}>
            （詳細はご予約後にご案内しています。）
          </p>
        </Section>
        <Section
          label="Reservation/Contact"
          className={styles.fadeSection}
          labelClassName={styles.sectionLabelDecorated}
          animationDelay="0.2s"
        >
          <PilatesReservation></PilatesReservation>
          <LinkButton
            href="/contact"
            variant="outline"
            className={styles.contactButton}
          >
            お問い合わせはこちら →
          </LinkButton>
        </Section>
      </main>
    </>
  );
}
