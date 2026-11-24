"use client";

import { useId, useState } from "react";
import LineLink from "@/components/ui/Line/LineLink";
import ActionButton from "@/components/ui/ActionButton/ActionButton";
import type { DaySchedule } from "@/types/pilates/calendar";
import styles from "./AlternativeTimeRequest.module.css";

export default function AlternativeTimeRequest({ schedule }: { schedule: DaySchedule }) {
  const timeId = useId();
  const [time, setTime] = useState("");
  const [copyStatus, setCopyStatus] = useState("");
  const min = schedule.earliestRequestTime;
  const max = schedule.closedPeriod === "afternoon" ? "11:59" : "23:59";
  const isValidTime = time !== "" && time >= min && time <= max;
  const message = isValidTime
    ? `${schedule.date}（${schedule.dayOfWeek}）の${time}頃にピラティスを希望しています。空き状況を教えていただけますか？`
    : "";

  if (!schedule.canRequest || min > max) return null;

  async function copyMessage() {
    try {
      await navigator.clipboard.writeText(message);
      setCopyStatus("コピーしました。LINEに貼り付けて送信してください。");
    } catch {
      setCopyStatus("相談文を選択してコピーし、LINEに貼り付けてください。");
    }
  }

  return (
    <section className={styles.request} aria-labelledby={`${timeId}-heading`}>
      <h3 id={`${timeId}-heading`}>別の時間をLINEで相談する</h3>
      <p>希望の開始時刻を選ぶと、LINEに送る相談文ができます。</p>
      <div className={styles.timeField}>
        <label htmlFor={timeId}>希望の開始時刻</label>
        <input
          id={timeId}
          className={styles.timeInput}
          type="time"
          min={min}
          max={max}
          value={time}
          aria-describedby={`${timeId}-hint`}
          aria-invalid={Boolean(time) && !isValidTime}
          onChange={(event) => {
            setTime(event.target.value);
            setCopyStatus("");
          }}
        />
        <p id={`${timeId}-hint`} className={styles.hint}>
          {time && !isValidTime
            ? `${min}〜${max}の間で選んでください。`
            : min !== "00:00" || max !== "23:59"
              ? `相談できる時間帯は ${min}〜${max} です。`
              : "例 14:30"}
        </p>
      </div>
      {message && (
        <div className={styles.preview}>
          <h4>LINEに送る相談文</h4>
          <p className={styles.message}>{message}</p>
          <div className={styles.copy}>
            <ActionButton variant="outline" onClick={copyMessage}>相談文をコピー</ActionButton>
          </div>
          <p className={styles.hint} role="status">
            {copyStatus || "相談文をコピーし、LINEに貼り付けて送信してください。"}
          </p>
          <LineLink />
        </div>
      )}
    </section>
  );
}
