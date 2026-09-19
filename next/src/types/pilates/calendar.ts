// 1つの時間帯
export type TimeSlot = {
  start: string;
  end: string;
  locationName?: string;
};

// 月表示の1セル
export type AvailabilityDateCell = {
  date: number;
  status: "available" | "contact_only" | "full" | null;
};

// 日付クリック後の1日分の詳細
export type DaySchedule = {
  date: string;
  dayOfWeek: string;
  times: TimeSlot[];
};
//月全体のデータ
export type MonthlyData<T> = {
  cells: (T | null)[];
  month: string;
  previous: string;
  next: string;
};

export type TrainingLogCell = {};

export type CalendarMonthResponse<T> = {};
