import type { AvailabilityDateCell } from "@/types/pilates/calendar";

type AvailabilityDateCellButtonProps = {
  cell: AvailabilityDateCell;
  onSelect: () => void;
};

export function AvailabilityDateCellButton({
  cell,
  onSelect,
}: AvailabilityDateCellButtonProps) {
  return (
    <button type="button" onClick={onSelect}>
      <span>{cell.date}</span>
      <span>{cell.status}</span>
    </button>
  );
}
