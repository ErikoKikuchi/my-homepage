import type { AvailabilityDateCell } from "@/types/pilates/calendar";
import styles from "@/components/pilates/calendar/AvailabilityDateCell.module.css";

type AvailabilityDateCellButtonProps = {
  cell: AvailabilityDateCell;
  isSelected: boolean;
  onSelect: () => void;
};

export default function AvailabilityDateCellButton({
  cell,
  isSelected,
  onSelect,
}: AvailabilityDateCellButtonProps) {
  const statusIcons = {
    available: "〇",
    contact_only: "△",
    full: "☓",
  };
  const isClickable =
    cell.status === "available" || cell.status === "contact_only";

  return (
    <button
      type="button"
      onClick={isClickable ? onSelect : undefined}
      disabled={!isClickable}
      className={isSelected ? styles.active : styles.normal}
    >
      <span>{cell.date}</span>
      <span>{cell.status === null ? "－" : statusIcons[cell.status]}</span>
    </button>
  );
}
