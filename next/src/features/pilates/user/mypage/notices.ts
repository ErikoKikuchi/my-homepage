export const NOTICES = {
  reserved: "予約の申請を受け付けました。",
} as const;

export type NoticeKey = keyof typeof NOTICES;

export function isNoticeKey(key: string | null): key is NoticeKey {
  return key !== null && Object.hasOwn(NOTICES, key);
}
