"use client";

import { useState, useEffect } from "react";
import {
  AvailabilityDateCell,
  MonthlyData,
  DaySchedule,
  TimeSlot,
} from "@/types/pilates/calendar";
import { MonthGrid } from "@/components/pilates/calendar/MonthGrid";
import AvailabilityDateCellButton from "./AvailabilityDateCell";
import styles from "./PilatesCalendar.module.css";

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
    const data = await response.json();
    setDaySchedule(data);
  }

  return (
    <div className={styles.pilatesCalendar}>
      <MonthGrid<AvailabilityDateCell>
        cells={monthData?.cells ?? []}
        renderCell={(cell) => {
          const dateString = `${monthData?.month}-${String(cell.date).padStart(2, "0")}`;
          console.log(daySchedule);
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
  );
}
