// app/(public)/start-here/page.tsx

import Section from "@/components/public/Section";
import NavCardGrid from "@/components/public/start-here/NavCardGrid";
import styles from "./page.module.css";

export default function StartHerePage() {
  return (
    <main className={styles.main}>
      <h1 className={styles.catch}>
        身体の動き
        <br />
        動きを生む構造
        <br />
        その人なりの使い方のクセを、
        <br />
        丁寧に読み解く。
      </h1>

      <Section
        label="Profile"
        className={styles.fadeSection}
        labelClassName={styles.sectionLabelDecorated}
      >
        <p>
          理学療法士として15年以上、
          <br />
          ピラティスインストラクターとして約10年、
          <br />
          身体と向き合い続けてきました。
        </p>
        <p>
          うまく動けない理由を探し、
          <br />
          どうしてその使い方になるのかを考え、
          <br />
          一人ひとりに合わせて返していく。
        </p>
        <p>
          その繰り返しの中で、
          <br />
          「理解すること」と「再現できること」の間にある差を強く意識するようになりました。
        </p>
        <p>
          現在は、今まで理学療法士として、ピラティスインストラクターとして培ってきたこと、
          <br />
          自分の身体やクライアントさんの身体を通して感じてきたことを
          <br />
          プログラミングを通じて思考として構造化し、形にすることに挑戦しています。
        </p>
      </Section>

      <Section
        label="Concept"
        className={styles.fadeSection}
        labelClassName={styles.sectionLabelDecorated}
        animationDelay="0.2s"
      >
        <p>このサイトは「再現」よりも「理解」を大切にしています。</p>
        <p>
          回数をこなすことより、自分の身体を観察すること。
          <br />
          「効いた感覚」よりも「わかった」という変化を。
          <br />
          指示に従うだけでなく、自分で調整できるようになること。
        </p>
        <p>
          私が見ているのは「動き」そのものではなく、
          <br />
          その動きを生み出している「使い方のクセ」です。
        </p>
        <p>
          身体には、それぞれの使い方のパターンがあります。
          <br />
          そのパターンは、感覚や認識の積み重なりによってつくられています。
        </p>
        <p>
          外側の形を真似るのではなく、
          <br />
          内側で何が起きているのかを理解し、組み替えていく。
        </p>
        <p>そうやって、自分自身の動きの構造を捉え直していく。</p>
        <p>
          そしてこのプロセスは、身体に限らず思考にも共通しています。
          <br />
          理解したことを構造として捉え、再現できる形にしていく。
        </p>
        <p>ここは、そのための場所です。</p>
      </Section>

      <Section
        label="このサイトについて"
        className={styles.fadeSection}
        animationDelay="0.4s"
      >
        <NavCardGrid />
      </Section>
    </main>
  );
}
