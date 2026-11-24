// 1つの時間帯
export type TimeSlot = {
  start: string;
  end: string;
  locationName?: string;
  isAvailable: boolean;
  isBlocked: boolean;
};

type ClosedPeriod = "all_day" | "morning" | "afternoon" | null;

// 月表示の1セル
export type AvailabilityDateCell = {
  date: number;
  canSelect: boolean;
  closedPeriod: ClosedPeriod;
  status: "available" | "contact_only" | "full" | "unavailable" | null;
};

// 日付クリック後の1日分の詳細
export type DaySchedule = {
  date: string;
  dayOfWeek: string;
  times: TimeSlot[];
  closedPeriod: ClosedPeriod;
  canRequest: boolean;
  earliestRequestTime: string;
};
//月全体のデータ
export type MonthlyData<T> = {
  cells: (T | null)[];
  month: string;
  previous: string;
  next: string;
};
