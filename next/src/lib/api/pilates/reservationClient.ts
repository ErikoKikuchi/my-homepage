import { ensureCsrfCookie, getCsrfTokenFromCookie } from "@/lib/api/csrf";

type ReservationIntent = {
  date: string;
  start: string;
};

export async function saveReservationIntent(
  intent: ReservationIntent,
): Promise<void> {
  await ensureCsrfCookie();

  const response = await fetch("/api/pilates/reservation/intent", {
    method: "POST",
    credentials: "include",
    headers: {
      "Content-Type": "application/json",
      Accept: "application/json",
      "X-XSRF-TOKEN": getCsrfTokenFromCookie(),
    },
    body: JSON.stringify(intent),
  });

  if (!response.ok) {
    throw new Error("予約情報の保存に失敗しました");
  }
}
