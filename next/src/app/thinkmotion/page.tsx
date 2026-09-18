import PageHeader from "@/components/public/common/PageHeader";
import Section from "@/components/public/common/Section";
import styles from "./page.module.css";
import LinkButton from "@/components/ui/LinkButton/LinkButton";
import ArticleListSection from "@/components/thinkmotion/article/ArticleListSection";
import {
  getMyStudies,
  getCaseDiscussions,
  getReadings,
} from "@/components/thinkmotion/article/articles";

export default function ThinkMotionTopPage() {
  return (
    <>
      <main className={styles.main}>
        <PageHeader
          label="ThinkMotion"
          heading={"書くために考えるのではなく、\n考えを磨くために書く。"}
          lead="臨床で生まれた思考を記録し、言語化し、検討し、読み合うためのプラットフォーム"
        ></PageHeader>
        <Section
          label="Concept"
          className={styles.fadeSection}
          labelClassName={styles.sectionLabelDecorated}
          animationDelay="0.2s"
        >
          <div className={styles.pilatesDescription}>
            <div>
              <div className={styles.border}></div>
              <p className={styles.subtitle}>思考を磨くプロセス</p>
              <div className={styles.border}></div>
            </div>
            <div>
              <div className={styles.space}>
                <p className={styles.theme}>01 &nbsp;残す</p>
                <p className={styles.concept}>
                  違和感や判断の背景を、その場で記録する
                </p>
              </div>
              <div className={styles.space}>
                <p className={styles.theme}>02 &nbsp;言語化する</p>
                <p className={styles.concept}>
                  なぜそう考えたのかを言葉にし、構造を明確にする
                </p>
              </div>
              <div className={styles.space}>
                <p className={styles.theme}>03 &nbsp;検討する</p>
                <p className={styles.concept}>
                  症例を通して、思考の精度を上げる
                </p>
              </div>
              <div className={styles.space}>
                <p className={styles.theme}>04 &nbsp;読む</p>
                <p className={styles.concept}>
                  他者の記録を読み、自分の思考に取り込む
                </p>
              </div>
              <p className={styles.conceptAfter}>
                この循環を繰り返すことで、思考は磨かれていく
              </p>
            </div>
          </div>
        </Section>
        <Section
          label="Contents"
          className={styles.fadeSection}
          labelClassName={styles.sectionLabelDecorated}
          animationDelay="0.2s"
        >
          <div>
            <div className={styles.border}></div>
            <p className={styles.subtitle}>My Studies</p>
            <div className={styles.border}></div>
          </div>
          <div className={styles.space}></div>
          <ArticleListSection
            description="思考がどのように磨かれていくか、その過程をたどります"
            posts={getMyStudies()}
          />
          <div className={styles.space}></div>
          <div className={styles.border}></div>
          <p className={styles.subtitle}>Case Study</p>
          <div className={styles.border}></div>
          <ArticleListSection
            description="判断の理由や優先順位を、症例を通して深める場です。専門家限定「テーマ別ルーム」は整形疾患・脳血管障害・神経内科疾患・内部障害などに分かれ各テーマの思考を深める場です。"
            posts={getCaseDiscussions()}
          />
          <div className={styles.space}></div>
          <div className={styles.border}></div>
          <p className={styles.subtitle}>Readings</p>
          <div className={styles.border}></div>
          <ArticleListSection
            description="様々な文献を通じて思考を高める場です。"
            posts={getReadings()}
          />
        </Section>
        <Section
          label="Link"
          className={styles.fadeSection}
          labelClassName={styles.sectionLabelDecorated}
          animationDelay="0.2s"
        >
          <p className={styles.linkDescription}>
            -コンテンツは登録・ログイン後にアクセスできます。-
          </p>
          <div className={styles.buttonGroup}>
            <div>
              <LinkButton href="/thinkmotion/index" variant="primary">
                思考の過程を見る
              </LinkButton>
              <p className={styles.buttonDescription}>
                公開されている思考の過程をたどります。
              </p>
            </div>
            <div>
              <LinkButton href="/thinkmotion/login" variant="outline">
                思考を記録し、深める
              </LinkButton>
              <p className={styles.buttonDescription}>
                ログインして、自分の思考を記録します。
              </p>
            </div>
          </div>
        </Section>
        <Section
          label="guide"
          className={styles.fadeSection}
          labelClassName={styles.sectionLabelDecorated}
          animationDelay="0.2s"
        >
          <div className={styles.buttonGroup}>
            <LinkButton href="/thinkmotion/howToUse">使い方</LinkButton>
            <LinkButton href="/thinkmotion/terms" variant="outline">
              利用規約
            </LinkButton>
          </div>
        </Section>
      </main>
    </>
  );
}
