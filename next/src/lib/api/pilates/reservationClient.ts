import { ensureCsrfCookie, getCsrfTokenFromCookie } from "@/lib/api/csrf";
import { AuthApiError } from "@/lib/api/auth/authApi";

type ReservationIntent = {
  date: string;
  start: string;
};

type Reservation = {
  date: string;
  time: string;
  phone?: string;
  participants: number;
  participantNames?: string[];
  note: string;
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

export async function createReservation(data: Reservation): Promise<void> {
  await ensureCsrfCookie();
  const { participantNames, ...rest } = data;

  const response = await fetch("/api/pilates/reservations", {
    method: "POST",
    credentials: "include",
    headers: {
      "Content-Type": "application/json",
      Accept: "application/json",
      "X-XSRF-TOKEN": getCsrfTokenFromCookie(),
    },
    body: JSON.stringify({
      ...rest,
      participants_names: participantNames,
    }),
  });

  if (!response.ok) {
    // 500のHTMLなど、JSONでない応答でも落ちないようにする
    const body = await response
      .json()
      .catch(() => ({ message: "予約情報の保存に失敗しました" }));
    throw new AuthApiError(response.status, body);
  }
}
