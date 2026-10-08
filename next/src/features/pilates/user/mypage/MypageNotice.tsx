"use client";

import { useEffect, useState } from "react";
import { usePathname, useRouter, useSearchParams } from "next/navigation";
import { NOTICES, isNoticeKey } from "./notices";

export default function MypageNotice() {
  const searchParams = useSearchParams();
  const router = useRouter();
  const pathname = usePathname();
  const key = searchParams.get("notice");

  const [message] = useState(() => (isNoticeKey(key) ? NOTICES[key] : null));

  useEffect(() => {
    if (key) router.replace(pathname, { scroll: false });
  }, [key, pathname, router]);

  if (!message) return null;

  return (
    <div
      role="status"
      className="message bg-forest/30 border border-accent rounded-2xl"
    >
      <p className="pl-3 text-forest-dark whitespace-pre-line">{message}</p>
    </div>
  );
}
