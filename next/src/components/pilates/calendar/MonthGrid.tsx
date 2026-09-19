import styles from "./MonthGrid.module.css";

type MonthGridProps<T> = {
  cells: (T | null)[];
  renderCell: (cell: T) => React.ReactNode;
  onPrevMonth: () => void;
  onNextMonth: () => void;
  title?: React.ReactNode;
};

const WEEKDAYS = ["日", "月", "火", "水", "木", "金", "土"];

export function MonthGrid<T>({
  cells,
  renderCell,
  onPrevMonth,
  onNextMonth,
  title,
}: MonthGridProps<T>) {
  return (
    <div className={styles.calendar}>
      <div className={styles.controls}>
        <button
          type="button"
          className={styles.navButton}
          onClick={onPrevMonth}
        >
          前月
        </button>
        {title}
        <button
          type="button"
          className={styles.navButton}
          onClick={onNextMonth}
        >
          翌月
        </button>
      </div>

      <div className={styles.weekdays}>
        {WEEKDAYS.map((weekday) => (
          <div key={weekday}>{weekday}</div>
        ))}
      </div>

      <div className={styles.dates}>
        {cells.map((cell, index) =>
          cell === null ? (
            <div key={index} className={styles.emptyDate} />
          ) : (
            <div key={index} className={styles.date}>
              {renderCell(cell)}
            </div>
          ),
        )}
      </div>
    </div>
  );
}
