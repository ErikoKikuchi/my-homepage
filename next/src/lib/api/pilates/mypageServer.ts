import "server-only";
import { cookies } from "next/headers";
import type { AccountSettingsData, HopeHistoryEntry, MypageData } from "@/types/pilates/mypage";

function requireEnv(name: string): string {
  const value = process.env[name];
  if (!value) {
    throw new Error(`環境変数 ${name} が設定されていません`);
  }
  return value;
}

async function getMypageResource<T>(path = ""): Promise<T> {
  const backendUrl = requireEnv("BACKEND_URL");
  const origin = requireEnv("APP_ORIGIN");

  const cookieStore = await cookies();

  const response = await fetch(`${backendUrl}/api/pilates/mypage${path}`, {
    headers: {
      Accept: "application/json",
      Cookie: cookieStore.toString(),
      Origin: origin,
      Referer: `${origin}/`,
    },
    cache: "no-store",
  });

  if (!response.ok) {
    console.error("マイページ取得エラー", {
      status: response.status,
    });

    throw new Error("マイページの取得に失敗しました");
  }

  return response.json();
}

export function getMypageData() {
  return getMypageResource<MypageData>();
}

export function getAccountSettings() {
  return getMypageResource<AccountSettingsData>("/settings");
}

export function getHopeHistory() {
  return getMypageResource<{ hopes: HopeHistoryEntry[]; hasClient: boolean }>("/hopes");
}
