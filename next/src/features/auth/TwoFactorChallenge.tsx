"use client";

import { useState, type SubmitEvent } from "react";
import { useRouter } from "next/navigation";
import ActionButton from "@/components/ui/ActionButton/ActionButton";
import LinkButton from "@/components/ui/LinkButton/LinkButton";
import { submitAccountRequest } from "@/lib/api/pilates/accountSettingsClient";
import styles from "./TwoFactorChallenge.module.css";

export default function TwoFactorChallenge() {
  const router = useRouter();
  const [code, setCode] = useState("");
  const [useRecoveryCode, setUseRecoveryCode] = useState(false);
  const [busy, setBusy] = useState(false);
  const [error, setError] = useState("");

  async function verify(event: SubmitEvent<HTMLFormElement>) {
    event.preventDefault();
    if (busy) return;
    setBusy(true);
    setError("");

    try {
      const result = await submitAccountRequest("/api/auth/two-factor-challenge", "POST",
        useRecoveryCode ? { recovery_code: code.trim() } : { code: code.trim() });
      router.replace(result.redirectTo);
    } catch (error) {
      setError((error as Error).message);
    } finally {
      setBusy(false);
    }
  }

  return (
    <div className={styles.content}>
      <form className={styles.form} onSubmit={verify}>
        <label>
          {useRecoveryCode ? "復旧コード" : "認証アプリの6桁のコード"}
          <input value={code} onChange={(event) => setCode(event.target.value)} disabled={busy}
            required inputMode={useRecoveryCode ? "text" : "numeric"} autoComplete="one-time-code"
            pattern={useRecoveryCode ? undefined : "[0-9]{6}"} maxLength={useRecoveryCode ? 100 : 6} />
        </label>
        {error && <p role="alert">{error}</p>}
        <button className={styles.submit} type="submit" disabled={busy}>{busy ? "確認中…" : "認証してログイン"}</button>
        <ActionButton variant="text" disabled={busy} onClick={() => {
          setUseRecoveryCode(!useRecoveryCode);
          setCode("");
          setError("");
        }}>{useRecoveryCode ? "認証アプリのコードを使う" : "復旧コードを使う"}</ActionButton>
      </form>
      <div className={styles.links}>
        <LinkButton variant="text" href="/auth/pilates/login">Pilatesログインへ戻る</LinkButton>
        <LinkButton variant="text" href="/auth/thinkmotion/login">ThinkMotionログインへ戻る</LinkButton>
      </div>
    </div>
  );
}
