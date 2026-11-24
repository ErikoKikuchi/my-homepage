"use client";

import { useState, type SubmitEvent } from "react";
import Image from "next/image";
import ActionButton from "@/components/ui/ActionButton/ActionButton";
import { submitAccountRequest } from "@/lib/api/pilates/accountSettingsClient";
import type { AccountSettingsData } from "@/types/pilates/mypage";
import styles from "./AccountSettings.module.css";

type Operation = "setup" | "confirm" | "recovery" | "disable";
const labels = {
  disabled: "未設定",
  pending: "設定途中",
  enabled: "有効",
};

export default function AccountTwoFactor({ initialStatus }: { initialStatus: AccountSettingsData["twoFactor"] }) {
  const [status, setStatus] = useState(initialStatus);
  const [operation, setOperation] = useState<Operation | null>(null);
  const [password, setPassword] = useState("");
  const [code, setCode] = useState("");
  const [setup, setSetup] = useState<{ qrSvg: string; setupKey: string } | null>(null);
  const [recoveryCodes, setRecoveryCodes] = useState<string[]>([]);
  const [busy, setBusy] = useState(false);
  const [error, setError] = useState("");

  function begin(next: Operation | null) {
    setOperation(next);
    setPassword("");
    setCode("");
    setError("");
    setSetup(null);
    setRecoveryCodes([]);
  }

  async function submit(event: SubmitEvent<HTMLFormElement>) {
    event.preventDefault();
    if (!operation || busy) return;
    setBusy(true);
    setError("");

    try {
      const result = await submitAccountRequest(`/api/pilates/mypage/settings/two-factor/${operation}`, "POST", { password, code });
      setStatus(result.twoFactor);
      if (operation === "setup") {
        setSetup({ qrSvg: result.qrSvg, setupKey: result.setupKey });
        setOperation("confirm");
      } else {
        begin(null);
        setRecoveryCodes(result.recoveryCodes ?? []);
      }
    } catch (error) {
      setError((error as Error).message);
    } finally {
      setBusy(false);
    }
  }

  return (
    <section className={styles.section}>
      <h2>二要素認証</h2>
      <p>現在の状態：{labels[status]}</p>
      {!operation && (
        <div className={styles.actions}>
          {status !== "enabled" && (
            <ActionButton onClick={() => begin("setup")}>{status === "pending" ? "設定を再開する" : "二要素認証を設定する"}</ActionButton>
          )}
          {status === "enabled" && <ActionButton variant="outline" onClick={() => begin("recovery")}>復旧コードを表示する</ActionButton>}
          {status !== "disabled" && <ActionButton variant="outline" onClick={() => begin("disable")}>{status === "pending" ? "途中の設定を取り消す" : "二要素認証を無効にする"}</ActionButton>}
        </div>
      )}

      {operation && (
        <form className={styles.form} onSubmit={submit}>
          {setup && (
            <div className={styles.form}>
              <p>認証アプリでQRコードを読み取るか、設定キーを手入力してください。</p>
              <Image unoptimized src={`data:image/svg+xml,${encodeURIComponent(setup.qrSvg)}`}
                width={192} height={192} alt="認証アプリ設定用QRコード" />
              <p>設定キー <code>{setup.setupKey}</code></p>
            </div>
          )}
          <label>
            現在のパスワード
            <input type="password" autoComplete="current-password" required value={password}
              onChange={(event) => setPassword(event.target.value)} disabled={busy} />
          </label>
          {operation === "confirm" && (
            <label>
              認証アプリの6桁のコード
              <input type="text" inputMode="numeric" autoComplete="one-time-code" pattern="[0-9]{6}" maxLength={6}
                required value={code} onChange={(event) => setCode(event.target.value)} disabled={busy} />
            </label>
          )}
          {error && <p role="alert">{error}</p>}
          <div className={styles.actions}>
            <button type="submit" className={styles.submit} disabled={busy}>{busy ? "処理中…" : {
              setup: "設定を続ける",
              confirm: "コードを確認して有効にする",
              recovery: "復旧コードを表示する",
              disable: "設定を解除する",
            }[operation]}</button>
            <ActionButton variant="text" disabled={busy} onClick={() => begin(null)}>閉じる</ActionButton>
          </div>
        </form>
      )}

      {recoveryCodes.length > 0 && (
        <div className={styles.form}>
          <h3>復旧コード</h3>
          <p>認証アプリが使えないときに使います。各コードは1回限りです。安全な場所に保管してください。</p>
          <ul className={styles.codes}>{recoveryCodes.map((value) => <li key={value}><code>{value}</code></li>)}</ul>
          <div className={styles.actions}><ActionButton variant="text" onClick={() => setRecoveryCodes([])}>復旧コードを閉じる</ActionButton></div>
        </div>
      )}
    </section>
  );
}
