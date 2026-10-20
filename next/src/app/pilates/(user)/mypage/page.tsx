import PageHeader from "@/components/public/common/PageHeader";
import Section from "@/components/public/common/Section";
import styles from "./page.module.css";
import LinkButton from "@/components/ui/LinkButton/LinkButton";
import { Suspense } from "react";
import MypageNotice from "@/features/pilates/user/mypage/MypageNotice";
import LineLink from "@/components/ui/Line/LineLink";
import { getMypageData } from "@/lib/api/pilates/mypageServer";
import PilatesReservation from "@/components/pilates/top/PilatesReservation";

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
        <PageHeader heading="マイページ" />
        <div className={styles.anotherLinkButton}>
          <LinkButton href="/pilates/mypage/settings" variant="text">
            アカウント設定
          </LinkButton>
        </div>
        <Suspense fallback={null}>
          <MypageNotice />
        </Suspense>

        <Section
          label="次回のご予約"
          className={styles.fadeSection}
          labelClassName={styles.sectionLabelDecorated}
          animationDelay="0.2s"
        >
          <div className={styles.reservation}>
            {nextReservationInfo ? (
              <>
                <p>予約日：{nextReservationInfo.date}</p>
                <p>開催場所：{nextReservationInfo.location}</p>
              </>
            ) : (
              <p>次回のご予約はありません</p>
            )}
            <p>回数券残数：{remainingTicketCounts}枚</p>
            <p>
              ご予約は、現在の回数券残数に1回分を加えた回数までを目安にお願いいたします。
              目安を超えるご予約をご希望の場合はご相談ください。
            </p>
          </div>
          <div className={styles.anotherLinkButton}>
            <PilatesReservation />
            <LinkButton href="/pilates/mypage/tickets-shop" variant="primary">
              回数券購入
            </LinkButton>
          </div>
        </Section>

        <Section
          label="今後のご予約"
          className={styles.fadeSection}
          labelClassName={styles.sectionLabelDecorated}
          animationDelay="0.2s"
        >
          <div>
            {upcomingReservations.length > 0 ? (
              upcomingReservations.map((reservation) => (
                <table
                  className={styles.upcomingReservations}
                  key={reservation.uuid}
                >
                  <thead className={styles.thead}>
                    <tr className={styles.row}>
                      <th className={styles.theme}>予約日</th>
                      <th className={styles.theme}>場所</th>
                      <th className={styles.theme}></th>
                    </tr>
                  </thead>
                  <tbody className={styles.body}>
                    <tr className={styles.row}>
                      <td className={styles.data}>{reservation.date}</td>
                      <td className={styles.data}>
                        {reservation.location ?? "未定"}
                      </td>
                      <td className={styles.data}>
                        <LinkButton
                          href={`/pilates/mypage/reservations/${reservation.uuid}`}
                        >
                          確認/キャンセル
                        </LinkButton>
                      </td>
                    </tr>
                  </tbody>
                </table>
              ))
            ) : (
              <p>今後の予約はありません。</p>
            )}
          </div>
          <div className={styles.anotherLinkButton}>
            <LinkButton
              href="/pilates/mypage/past-reservation"
              variant="primary"
            >
              過去のご予約
            </LinkButton>
          </div>
        </Section>

        <Section
          label="からだの目標"
          className={styles.fadeSection}
          labelClassName={styles.sectionLabelDecorated}
          animationDelay="0.2s"
        >
          <div className={styles.anotherLinkButton}>
            <LinkButton href="/pilates/mypage/goals" variant="primary">
              からだの目標
            </LinkButton>
          </div>
        </Section>

        <Section
          label="自主トレログ～BodyMind～"
          className={styles.fadeSection}
          labelClassName={styles.sectionLabelDecorated}
          animationDelay="0.2s"
        >
          <div className={styles.anotherLinkButton}>
            <LinkButton href="/pilates/mypage/training-logs" variant="primary">
              自主トレログを開く
            </LinkButton>
          </div>
        </Section>

        <Section
          label="LINE連携・お問い合わせ"
          className={styles.fadeSection}
          labelClassName={styles.sectionLabelDecorated}
          animationDelay="0.2s"
        >
          <div className={styles.lineLink}>
            {notLineLinkedClient && (
              <p>
                LINE連携がまだ完了していません。下記よりご登録をお願いします。
              </p>
            )}
            <p>
              予約後の場所のご案内・リマインド等の直接のやり取りは
              LINEで行っております。お問い合わせもこちらからどうぞ。
            </p>
            <LineLink />
            {notLineLinkedClient && (
              <p className={styles.message}>
                ＊システムの仕様により、既に登録済の方も初回のご利用までは表示されます。
              </p>
            )}
          </div>
        </Section>
      </div>
      <footer className={styles.footer}>
        <div>
          <LinkButton href="/pilates/terms" variant="text">
            利用規約(Pilates)
          </LinkButton>
        </div>
      </footer>
    </>
  );
}
