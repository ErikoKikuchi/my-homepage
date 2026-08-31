import PageHeader from "@/components/public/PageHeader";
import Section from "@/components/public/Section";
import OriginBlock from "@/components/public/about/OriginBlock";
import styles from "./page.module.css";

export default function AboutPage() {
  return (
    <main className={styles.main}>
      <PageHeader
        label="About"
        heading="手で触れ、考え、つくる人。"
        lead="理学療法士 / ピラティス / エンジニア"
      />
      <Section
        label="Origin"
        className={styles.fadeSection}
        labelClassName={styles.sectionLabelDecorated}
      >
        <OriginBlock
          number="01 — 理学療法"
          catchcopy="手で触れただけで、痛みが変わる。 
          その不思議から、理学療法士になった。"
          bodyText="私の理学療法士としての核は2年目に学び始めた「運動連鎖アプローチ®︎」。 
          パルペーション（触れること）や呼吸を通じて身体を観察し、その人が本来持っている能力を引き出すこと。 
          一人ひとりの動きと生活に繋げていくことを、15年以上、大切にしてきた。"
        ></OriginBlock>
        <OriginBlock
          number="02 — ピラティス"
          catchcopy="教科書の動きをなぞるだけでは、人は変わらない。 
          体が本来もっている機能を引き出すために、ピラティスへ。"
          bodyText="私の根幹には理学療法士がある。だからピラティスをしていても理論で考えてしまう。 
          世の中にあるようなキラキラしたピラティスが苦手。 
          自分の身体をもっと知りたい、なんで痛くなるんだろうとか気になる人が好き。 
          でも理屈ばかりではなく、動いて自分の身体の変化に気づいていくという過程を純粋に楽しんでほしい。 
          自分の身体の感覚と向き合い、ゆっくりと変化する身体を楽しんでみませんか？"
        ></OriginBlock>
        <OriginBlock
          number="03 — プログラミング"
          catchcopy="物理的な場所より先に、自分の言葉を置ける場所がほしかった。 
          それがプログラミングをはじめたきっかけ。"
          bodyText="子育てとの両立との中で、理学療法士としてのキャリアに行き詰まった時、心に浮かんだのがインターネットの中に場所を作ってみたいでした。 

          家族に相談し、このサイトも自分で作りました。私は専門を突き詰めるというよりは構造化したいタイプ。理学療法とも分野は違えど似ているこの世界は、とても面白いです"
        ></OriginBlock>
      </Section>
    </main>
  );
}
