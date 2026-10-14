import PageHeader from "@/components/public/common/PageHeader";
import Section from "@/components/public/common/Section";
import styles from "./page.module.css";
import LinkButton from "@/components/ui/LinkButton/LinkButton";
import { Suspense } from "react";
import MypageNotice from "@/features/pilates/user/mypage/MypageNotice";
import LineLink from "@/components/ui/Line/LineLink";
import { getMypageData } from "@/lib/api/pilates/mypageServer";

type UpcomingReservation = {
  uuid: string;
  date: string;
  location: string | null;
};

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
                ＊システムの仕様により、既に登録済の方も初回のご利用までは表示されます。
              </p>
            </div>
          )}
          <div className={styles.anotherLinkButton}>
            <LinkButton href="/api/pilates/calendar" variant="primary">
              予約する
            </LinkButton>
            <LinkButton href="/api/pilates/archive" variant="primary">
              過去の予約
            </LinkButton>
            <LinkButton href="/api/pilates/tickets" variant="primary">
              回数券購入
            </LinkButton>
            <LinkButton href="/api/pilates/training-logs" variant="primary">
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
            <div className={styles.reservation}>
              <p>予約日:{nextReservationInfo.date}</p>
              <p>開催場所：{nextReservationInfo.location}</p>
              <p>回数券残数：{remainingTicketCounts}枚</p>
              <p>
                ご予約は、現在の回数券残数に1回分を加えた回数までを目安にお願いいたします。
                目安を超えるご予約をご希望の場合はご相談ください。
              </p>
            </div>
          )}
          {!nextReservationInfo && (
            <div className={styles.reservation}>
              <p>次回のご予約はありません</p>
              <p>回数券残数：{remainingTicketCounts}枚</p>
              <p>
                ご予約は、現在の回数券残数に1回分を加えた回数までを目安にお願いいたします。
                目安を超えるご予約をご希望の場合はご相談ください。
              </p>
            </div>
          )}
        </div>
      </Section>
      <Section
        label="今後のご予約"
        className={styles.fadeSection}
        labelClassName={styles.sectionLabelDecorated}
        animationDelay="0.2s"
      >
        <div>
          <table className={styles.upcomingReservations}>
            <thead>
              <tr>
                <th>予約日</th>
                <th>場所</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              {upcomingReservations.length > 0 ? (
                upcomingReservations.map((reservation) => (
                  <tr key={reservation.uuid}>
                    <td>{reservation.date}</td>
                    <td>{reservation.location ?? "未定"}</td>
                    <td>
                      <LinkButton href="/api/pilates/reservations">
                        確認/キャンセル
                      </LinkButton>
                    </td>
                  </tr>
                ))
              ) : (
                <tr>
                  <td colSpan={3}>今後の予約はありません。</td>
                </tr>
              )}
            </tbody>
          </table>
        </div>
      </Section>
      <Section
        label="LINEで問い合わせる"
        className={styles.fadeSection}
        labelClassName={styles.sectionLabelDecorated}
        animationDelay="0.2s"
      >
        <div>
          <LineLink></LineLink>
          <p>
            予約後の場所のご案内・リマインド等の直接のやり取りは
            LINEで行っておりますので、ご登録お願いいたします。
          </p>
        </div>
      </Section>
      <footer>
        <div>
          <LinkButton href="/pilates/terms" variant="text">
            利用規約(Pilates)
          </LinkButton>
        </div>
      </footer>
    </>
  );
}
