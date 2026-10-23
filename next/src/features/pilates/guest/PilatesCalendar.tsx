"use client";

import { useState, useEffect, useRef } from "react";
import {
  AvailabilityDateCell,
  MonthlyData,
  DaySchedule,
} from "@/types/pilates/calendar";
import { MonthGrid } from "@/components/pilates/calendar/MonthGrid";
import AvailabilityDateCellButton from "@/components/pilates/calendar/AvailabilityDateCell";
import styles from "./PilatesCalendar.module.css";
import { TimeSlotList } from "@/components/pilates/calendar/TimeSlotList";

type PilatesCalendarProps = {
  initialMonth: string;
  className?: string;
};

export default function PilatesCalendar({
  initialMonth,
}: PilatesCalendarProps) {
  const [monthData, setMonthData] =
    useState<MonthlyData<AvailabilityDateCell> | null>(null);
  const [selectedDate, setSelectedDate] = useState<string | null>(null);
  const [daySchedule, setDaySchedule] = useState<DaySchedule | null>(null);
  const [calendarError, setCalendarError] = useState<string | null>(null);

  const monthRequestId = useRef(0);
  const dayRequestId = useRef(0);

  async function loadMonth(month: string) {
    const requestId = ++monthRequestId.current;

    ++dayRequestId.current;
    setSelectedDate(null);
    setDaySchedule(null);
    setCalendarError(null);

    try {
      const query = new URLSearchParams({ month });
      const response = await fetch(
        `/api/pilates/reservation/calendar?${query.toString()}`,
      );

      if (!response.ok) {
        throw new Error("月別データの取得に失敗しました");
      }

      const data: MonthlyData<AvailabilityDateCell> = await response.json();

      if (requestId !== monthRequestId.current) return;

      // 月の読み込み中に選択された日付も解除する
      ++dayRequestId.current;
      setSelectedDate(null);
      setDaySchedule(null);
      setMonthData(data);
    } catch {
      if (requestId !== monthRequestId.current) return;

      setCalendarError(
        "カレンダーを取得できませんでした。時間をおいて再度お試しください。",
      );
    }
  }

  useEffect(() => {
    void loadMonth(initialMonth);

    return () => {
      // 終了後に取得結果を反映しない
      ++monthRequestId.current;
      ++dayRequestId.current;
    };
  }, [initialMonth]);

  function handlePrevMonth() {
    if (monthData) void loadMonth(monthData.previous);
  }

  function handleNextMonth() {
    if (monthData) void loadMonth(monthData.next);
  }

  async function loadDaySchedule(dateString: string) {
    const requestId = ++dayRequestId.current;

    setSelectedDate(dateString);
    setDaySchedule(null);
    setCalendarError(null);

    try {
      const query = new URLSearchParams({ date: dateString });
      const response = await fetch(
        `/api/pilates/reservation/slots?${query.toString()}`,
      );

      if (!response.ok) {
        throw new Error("日別データの取得に失敗しました");
      }

      const data: DaySchedule = await response.json();

      if (requestId !== dayRequestId.current) return;

      if (data.date !== dateString) {
        throw new Error("取得した日付が一致しません");
      }

      setDaySchedule(data);
    } catch {
      if (requestId !== dayRequestId.current) return;

      setCalendarError(
        "時間枠を取得できませんでした。日付を選び直してください。",
      );
    }
  }

  const selectedCell = monthData?.cells.find((cell) => {
    if (cell === null) return false;
    const dateString = `${monthData.month}-${String(cell.date).padStart(2, "0")}`;
    return dateString === selectedDate;
  });

  return (
    <>
      <div className={styles.pilatesCalendar}>
        <MonthGrid<AvailabilityDateCell>
          cells={monthData?.cells ?? []}
          renderCell={(cell) => {
            const dateString = `${monthData?.month}-${String(cell.date).padStart(2, "0")}`;
            return (
              <AvailabilityDateCellButton
                cell={cell}
                isSelected={dateString === selectedDate}
                onSelect={() => {
                  loadDaySchedule(dateString);
                }}
              />
            );
          }}
          onPrevMonth={handlePrevMonth}
          onNextMonth={handleNextMonth}
          title={monthData && <p>{monthData.month}月の空き状況</p>}
        />
      </div>
      <div className={styles.pilatesDaySchedule}>
        {calendarError && <p role="alert">{calendarError}</p>}

        {daySchedule && daySchedule.date === selectedDate && (
          <TimeSlotList
            daySchedule={daySchedule}
            status={selectedCell?.status ?? null}
          />
        )}
      </div>
    </>
  );
}
