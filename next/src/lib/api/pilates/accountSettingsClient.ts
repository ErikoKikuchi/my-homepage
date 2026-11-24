import { ensureCsrfCookie, getCsrfTokenFromCookie } from "@/lib/api/csrf";

export async function submitAccountRequest(path: string, method: "POST" | "PATCH", data?: object) {
  await ensureCsrfCookie();

  const response = await fetch(path, {
    method,
    credentials: "include",
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
      "X-XSRF-TOKEN": getCsrfTokenFromCookie(),
    },
    body: data ? JSON.stringify(data) : undefined,
  });

  if (response.status === 204) return null;
  const result = await response.json().catch(() => null);

  if (!response.ok) {
    const fieldErrors = Object.values(result?.errors ?? {}).flat();
    throw new Error(fieldErrors.find((value) => typeof value === "string")
      ?? (response.status === 429 ? "時間をおいてからもう一度お試しください。" : result?.message)
      ?? "処理に失敗しました。時間をおいて再度お試しください。");
  }

  return result;
}
