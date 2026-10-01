"use client";

import { useState, useEffect } from "react";
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

  async function loadMonth(month: string) {
    const response = await fetch(
      `/api/pilates/reservation/calendar?month=${month}`,
    );
    if (!response.ok) {
      console.error("calendar API error:", await response.text());
      return;
    }

    const data = await response.json();
    setMonthData(data);
  }
  useEffect(() => {
    // eslint-disable-next-line react-hooks/set-state-in-effect -- 外部APIからの初期データ取得のため正当なEffect(react-hooks#34743で議論中の誤検出)
    loadMonth(initialMonth);
  }, [initialMonth]);

  function handlePrevMonth() {
    if (monthData) loadMonth(monthData.previous);
  }

  function handleNextMonth() {
    if (monthData) loadMonth(monthData.next);
  }

  async function loadDaySchedule(dateString: string) {
    setSelectedDate(dateString);
    const response = await fetch(
      `/api/pilates/reservation/slots?date=${dateString}`,
    );
    if (!response.ok) {
      console.error("calendar API error:", await response.text());
      return;
    }

    const data = await response.json();

    setDaySchedule(data);
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
        {daySchedule && (
          <TimeSlotList
            daySchedule={daySchedule}
            status={selectedCell?.status ?? null}
          />
        )}
      </div>
    </>
  );
}
