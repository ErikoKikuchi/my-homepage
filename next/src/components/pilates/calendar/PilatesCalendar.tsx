"use client";

import { useState, useEffect } from "react";
import { AvailabilityDateCell, MonthlyData } from "@/types/pilates/calendar";
import { MonthGrid } from "@/components/pilates/calendar/MonthGrid";
import { AvailabilityDateCellButton } from "./AvailabilityDateCell";
import styles from "./PilatesCalendar.module.css";

type PilatesCalendarProps = {
  initialMonth: string;
  className?: string;
};

export default function PilatesCalendar({
  initialMonth,
  className,
}: PilatesCalendarProps) {
  const [monthData, setMonthData] =
    useState<MonthlyData<AvailabilityDateCell> | null>(null);

  async function loadMonth(month: string) {
    const response = await fetch(
      `/api/pilates/reservation/calendar?month=${month}`,
    );

    const data = await response.json();

    setMonthData(data);
    console.log(data);
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

  return (
    <div className={styles.pilatesCalendar}>
      <MonthGrid<AvailabilityDateCell>
        cells={monthData?.cells ?? []}
        renderCell={(cell) => (
          <AvailabilityDateCellButton cell={cell} onSelect={() => {}} />
        )}
        onPrevMonth={handlePrevMonth}
        onNextMonth={handleNextMonth}
      />
    </div>
  );
}
