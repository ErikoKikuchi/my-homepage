import PilatesCalendar from "@/components/pilates/calendar/PilatesCalendar";
import styles from "./page.module.css";

export default function ReservationPage() {
  const now = new Date();
  const initialMonth = `${now.getFullYear()}-${String(now.getMonth() + 1).padStart(2, "0")}`;

  return <PilatesCalendar initialMonth={initialMonth} />;
}
