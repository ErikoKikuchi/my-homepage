import PageHeader from "@/components/public/common/PageHeader";
import Section from "@/components/public/common/Section";
import styles from "./page.module.css";
import LinkButton from "@/components/ui/LinkButton/LinkButton";
import { Suspense } from "react";
import MypageNotice from "@/features/pilates/user/mypage/MypageNotice";
import LineLink from "@/components/ui/Line/LineLink";
import { getMypageData } from "@/lib/api/pilates/mypageServer";

export default async function MyPage() {
  const {
    notLineLinkedClient,
    nextReservationInfo,
    remainingTicketCounts,
    upcomingReservations,
  } = await getMypageData();
  return (
    <>
      <div className={styles.main}>
        <PageHeader heading={"マイページ"}></PageHeader>
      </div>
      <Suspense fallback={null}>
        <MypageNotice />
      </Suspense>
      <Section
        label="クイックメニュー"
        className={styles.fadeSection}
        labelClassName={styles.sectionLabelDecorated}
        animationDelay="0.2s"
      >
        <div className={styles.quickMenu}>
          {notLineLinkedClient && (
            <div>
              <p>
                LINE連携がまだ完了していません。下記よりご登録をお願いします。
              </p>
              <LineLink></LineLink>
              <p className={styles.message}>
                ＊システムの使用により初回のご利用までは表示されます。
              </p>
            </div>
          )}
          <div className={styles.anotherLinkButton}>
            <LinkButton
              href="/calendar"
              variant="primary"
              className={styles.reservationButton}
            >
              予約する
            </LinkButton>
            <LinkButton
              href="/archive"
              variant="primary"
              className={styles.reservationButton}
            >
              過去の予約
            </LinkButton>
            <LinkButton
              href="/tickets"
              variant="primary"
              className={styles.reservationButton}
            >
              回数券購入
            </LinkButton>
            <LinkButton
              href="/training-logs"
              variant="primary"
              className={styles.reservationButton}
            >
              自主トレログ～BodyMind～
            </LinkButton>
          </div>
        </div>
      </Section>

      <Section
        label="次回のご予約"
        className={styles.fadeSection}
        labelClassName={styles.sectionLabelDecorated}
        animationDelay="0.2s"
      >
        <div>
          {nextReservationInfo && (
            <>
              <p>予約日:{nextReservationInfo.date}</p>
              <p>開催場所：{nextReservationInfo.location}</p>
              <p>回数券残数：{remainingTicketCounts}枚</p>
              <p>
                ご予約は、現在の回数券残数に1回分を加えた回数までを目安にお願いいたします。
                目安を超えるご予約をご希望の場合はご相談ください。
              </p>
            </>
          )}
          {!nextReservationInfo && (
            <>
              <p>次回のご予約はありません</p>
              <p>回数券残数：{remainingTicketCounts}枚</p>
              <p>
                ご予約は、現在の回数券残数に1回分を加えた回数までを目安にお願いいたします。
                目安を超えるご予約をご希望の場合はご相談ください。
              </p>
            </>
          )}
        </div>
      </Section>
      <Section
        label="今後のご予約"
        className={styles.fadeSection}
        labelClassName={styles.sectionLabelDecorated}
        animationDelay="0.2s"
      >
        <div></div>
      </Section>
      <Section
        label="LINE登録"
        className={styles.fadeSection}
        labelClassName={styles.sectionLabelDecorated}
        animationDelay="0.2s"
      >
        <div></div>
      </Section>
    </>
  );
}
