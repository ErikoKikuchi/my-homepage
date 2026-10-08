import "server-only";
import { cookies } from "next/headers";
import { MypageData } from "@/types/pilates/mypage";

function requireEnv(name: string): string {
  const value = process.env[name];
  if (!value) {
    throw new Error(`環境変数 ${name} が設定されていません`);
  }
  return value;
}

export async function getMypageData(): Promise<MypageData> {
  const backendUrl = requireEnv("BACKEND_URL");
  const origin = requireEnv("APP_ORIGIN");

  const cookieStore = await cookies();

  const response = await fetch(`${backendUrl}/api/pilates/mypage`, {
    headers: {
      Accept: "application/json",
      Cookie: cookieStore.toString(),
      Origin: origin,
      Referer: `${origin}/`,
    },
    cache: "no-store",
  });

  if (!response.ok) {
    const body = await response.text();

    console.log("mypage API error body:", body);
    throw new Error("マイページの取得に失敗しました");
  }

  return response.json();
}
