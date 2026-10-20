"use client";

import {
  DaySchedule,
  AvailabilityDateCell,
  TimeSlot,
} from "@/types/pilates/calendar";
import styles from "./TimeSlotList.module.css";
import LineLink from "@/components/ui/Line/LineLink";
import ActionButton from "@/components/ui/ActionButton/ActionButton";
import { usePilatesAuth } from "@/hooks/usePilatesAuth";
import { useRouter } from "next/navigation";
import { saveReservationIntent } from "@/lib/api/pilates/reservationClient";

type TimeSlotListProps = {
  daySchedule: DaySchedule;
  status: AvailabilityDateCell["status"];
};

export function TimeSlotList({ daySchedule, status }: TimeSlotListProps) {
  const { user, isLoading } = usePilatesAuth();
  const router = useRouter();

  async function handleReservationApply(time: TimeSlot) {
    if (isLoading) {
      return;
    }

    if (user) {
      router.push(
        `/pilates/reservation/detail?date=${daySchedule.date}&start=${time.start}`,
      );
      return;
    }
    await saveReservationIntent({
      date: daySchedule.date,
      start: time.start,
    });

    router.push("/auth/pilates/login");
  }

  return (
    <div>
      <h2 className={styles.title}>
        {daySchedule.date}({daySchedule.dayOfWeek})の空き状況
      </h2>
      <div className={styles.time}>
        {daySchedule.times.map((time) => (
          <div key={time.start} className={styles.timeGroup}>
            <div>
              {time.start}〜{time.end}
            </div>

            {status === "available" && (
              <ActionButton
                variant="primary"
                className={styles.timeButton}
                onClick={() => {
                  handleReservationApply(time);
                }}
              >
                予約申請
              </ActionButton>
            )}

            {time.locationName && (
              <div className={styles.location}>{time.locationName}開催</div>
            )}
          </div>
        ))}
      </div>
      {status === "full" && (
        <>
          <p>公開中の予約枠は満席です。</p>
          <p>
            別の時間をご希望の場合は、ご相談いただけます。対応できない場合もあります。
          </p>
        </>
      )}
      {(status === "available" ||
        status === "contact_only" ||
        status === "full") && (
        <ActionButton
          variant="outline"
          onClick={() => {
            const query = new URLSearchParams({
              date: daySchedule.date,
            });
            router.push(`/pilates/reservation/request?${query.toString()}`);
          }}
        >
          別の時間を相談する
        </ActionButton>
      )}
      {status === "contact_only" && (
        <div className={styles.contactNotice}>
          <p className={styles.message}>
            直前のご予約は下記のQRコードよりお問い合わせください。
          </p>

          <LineLink></LineLink>
        </div>
      )}
    </div>
  );
}
