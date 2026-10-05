// lib/api/pilates/reservationServer.ts
import "server-only";
import { cookies } from "next/headers";
import { redirect } from "next/navigation";
import { ReservationDetail } from "@/types/pilates/reservation";

function requireEnv(name: string): string {
  const value = process.env[name];
  if (!value) {
    throw new Error(`環境変数 ${name} が設定されていません`);
  }
  return value;
}

export async function getReservationDetail(
  date: string,
  start: string,
): Promise<ReservationDetail> {
  const backendUrl = requireEnv("BACKEND_URL");
  const origin = requireEnv("APP_ORIGIN");

  const cookieStore = await cookies();
  const query = new URLSearchParams({ date, start });

  const response = await fetch(
    `${backendUrl}/api/pilates/reservations/create?${query}`,
    {
      headers: {
        Accept: "application/json",
        Cookie: cookieStore.toString(),
        Origin: origin,
        Referer: `${origin}/`,
      },
      cache: "no-store",
    },
  );

  if (response.status === 401) {
    redirect("/auth/pilates/login");
  }
  if (!response.ok) {
    throw new Error("予約詳細の取得に失敗しました");
  }

  return response.json();
}
