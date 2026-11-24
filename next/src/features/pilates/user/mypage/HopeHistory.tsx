"use client";

import { useState, useTransition } from "react";
import { useRouter } from "next/navigation";
import ActionButton from "@/components/ui/ActionButton/ActionButton";
import { ensureCsrfCookie, getCsrfTokenFromCookie } from "@/lib/api/csrf";
import type { HopeHistoryEntry } from "@/types/pilates/mypage";
import styles from "./HopeHistory.module.css";

const dateFormat = new Intl.DateTimeFormat("ja-JP", {
  dateStyle: "medium",
  timeZone: "Asia/Tokyo",
});

export default function HopeHistory({ hopes, hasClient }: { hopes: HopeHistoryEntry[]; hasClient: boolean }) {
  const router = useRouter();
  const [isRefreshing, startTransition] = useTransition();
  const [savingId, setSavingId] = useState<string | null>(null);
  const [edit, setEdit] = useState<{ id: string; text: string } | null>(null);
  const [newHope, setNewHope] = useState("");
  const busy = savingId !== null || isRefreshing;
  const [notice, setNotice] = useState("");
  const [error, setError] = useState("");
  const active = hopes.filter((hope) => hope.is_active);
  const history = hopes.filter((hope) => !hope.is_active);

  async function save(id: string | null, text?: string) {
    if (busy) return;
    const base = "/api/pilates/mypage/hopes";
    const url = id === null ? base : `${base}/${encodeURIComponent(id)}${text === undefined ? "/achieve" : ""}`;
    setSavingId(id ?? "new");
    setNotice("");
    setError("");

    try {
      await ensureCsrfCookie();
      const response = await fetch(url, {
        method: id === null ? "POST" : "PATCH",
        credentials: "include",
        headers: {
          Accept: "application/json",
          ...(text !== undefined ? { "Content-Type": "application/json" } : {}),
          "X-XSRF-TOKEN": getCsrfTokenFromCookie(),
        },
        body: text !== undefined ? JSON.stringify({ hope: text.trim() }) : undefined,
      });

      if (response.status === 409) {
        setEdit(null);
        setError(id === null ? "すでに現在の目標が登録されています。" : "この目標はすでに履歴に移っています。");
        startTransition(() => router.refresh());
        return;
      }
      if (!response.ok) throw new Error();

      setEdit(null);
      if (id === null) {
        setNewHope("");
        setNotice("新しい目標を登録しました。");
      } else {
        setNotice(text !== undefined ? "目標の変更を保存しました。" : "目標を達成済みにしました。");
      }
      startTransition(() => router.refresh());
    } catch {
      setError("目標を保存できませんでした。時間をおいて再度お試しください。");
    } finally {
      setSavingId(null);
    }
  }

  return (
    <div className={styles.groups}>
      {notice && <p role="status">{notice}</p>}
      {error && <p role="alert">{error}</p>}
      <section className={styles.groups}>
        <h2>現在の目標</h2>
        {active.length === 0 ? (
          <>
            <p>現在の目標はありません。</p>
            {hasClient ? (
              <div className={styles.card}>
                <div className={styles.editor}>
                  <label>
                    新しい目標
                    <textarea rows={3} maxLength={16000} value={newHope} disabled={busy}
                      onChange={(event) => setNewHope(event.target.value)} />
                  </label>
                  <div className={styles.actions}>
                    <ActionButton disabled={busy || !newHope.trim()} onClick={() => void save(null, newHope)}>
                      {savingId === "new" ? "保存中…" : "目標を登録する"}
                    </ActionButton>
                  </div>
                </div>
              </div>
            ) : <p>目標の登録にはクライアント情報との連携が必要です。</p>}
          </>
        ) : (
          <ul className={styles.list}>
            {active.map((hope) => (
              <li key={hope.id} className={styles.card}>
                {edit?.id === hope.id ? (
                  <div className={styles.editor}>
                    <label>
                      目標の内容
                      <textarea rows={3} maxLength={16000} value={edit.text} disabled={busy}
                        onChange={(event) => setEdit({ id: hope.id, text: event.target.value })} />
                    </label>
                    <div className={styles.actions}>
                      <ActionButton disabled={busy || !edit.text.trim() || edit.text.trim() === hope.hope}
                        onClick={() => void save(hope.id, edit.text)}>
                        {savingId === hope.id ? "保存中…" : "変更を保存"}
                      </ActionButton>
                      <ActionButton variant="text" disabled={busy} onClick={() => setEdit(null)}>キャンセル</ActionButton>
                    </div>
                  </div>
                ) : (
                  <>
                    <p className={styles.text}>{hope.hope}</p>
                    <time dateTime={hope.created_at}>登録日 {dateFormat.format(new Date(hope.created_at))}</time>
                    <div className={styles.actions}>
                      <ActionButton variant="outline" disabled={busy || edit !== null}
                        onClick={() => { setError(""); setNotice(""); setEdit({ id: hope.id, text: hope.hope }); }}>
                        編集する
                      </ActionButton>
                      <ActionButton variant="outline" disabled={busy || edit !== null} onClick={() => {
                        if (window.confirm("この目標を達成済みにしますか？")) void save(hope.id);
                      }}>
                        {savingId === hope.id ? "保存中…" : "達成済みにする"}
                      </ActionButton>
                    </div>
                  </>
                )}
              </li>
            ))}
          </ul>
        )}
      </section>
      <section className={styles.groups}>
        <h2>目標の履歴</h2>
        {history.length === 0 ? <p>目標の履歴はありません。</p> : (
          <ul className={styles.list}>
            {history.map((hope) => (
              <li key={hope.id} className={styles.card}>
                {hope.achieved_at && <strong className={styles.badge}>✓ 達成済み</strong>}
                <p className={styles.text}>{hope.hope}</p>
                <time dateTime={hope.created_at}>登録日 {dateFormat.format(new Date(hope.created_at))}</time>
                {hope.achieved_at && <time dateTime={hope.achieved_at}>達成日 {dateFormat.format(new Date(hope.achieved_at))}</time>}
              </li>
            ))}
          </ul>
        )}
      </section>
    </div>
  );
}
