import {
  DaySchedule,
  TimeSlot,
  AvailabilityDateCell,
} from "@/types/pilates/calendar";
import styles from "./TimeSlotList.module.css";
import LineLink from "@/components/ui/Line/LineQrCode";
import { Fragment } from "react";

type TimeSlotListProps = {
  daySchedule: DaySchedule;
  status: AvailabilityDateCell["status"];
  onSelectTime: (time: TimeSlot) => void;
};

export function TimeSlotList({
  daySchedule,
  status,
  onSelectTime,
}: TimeSlotListProps) {
  return (
    <div>
      <h2 className={styles.title}>
        {daySchedule.date}({daySchedule.dayOfWeek})の空き状況
      </h2>
      <div className={styles.time}>
        {daySchedule.times.map((time) => (
          <Fragment key={time.start}>
            <div>
              {time.start}~{time.end}
            </div>
            <button
              className={styles.timeButton}
              onClick={() => onSelectTime(time)}
            >
              予約申請
            </button>
            {time.locationName && (
              <div className={styles.location}>{time.locationName}開催</div>
            )}
          </Fragment>
        ))}
      </div>

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
