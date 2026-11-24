"use client";

import { useState, type SubmitEvent } from "react";
import { useRouter } from "next/navigation";
import ActionButton from "@/components/ui/ActionButton/ActionButton";
import { submitAccountRequest } from "@/lib/api/pilates/accountSettingsClient";
import type { AccountSettingsData } from "@/types/pilates/mypage";
import AccountTwoFactor from "./AccountTwoFactor";
import styles from "./AccountSettings.module.css";

const path = "/api/pilates/mypage/settings";
const fields = [
  { key: "name", label: "氏名", type: "text", autoComplete: "name" },
  { key: "name_kana", label: "ふりがな", type: "text" },
  { key: "phone", label: "電話番号", type: "tel", autoComplete: "tel" },
  {
    key: "occupation",
    label: "職業",
    type: "text",
    autoComplete: "organization-title",
  },
] as const;

export default function AccountSettings({
  settings,
}: {
  settings: AccountSettingsData;
}) {
  const router = useRouter();
  const [profile, setProfile] = useState(settings.profile);
  const [draft, setDraft] = useState(settings.profile);
  const [editing, setEditing] = useState(false);
  const [saving, setSaving] = useState(false);
  const [profileMessage, setProfileMessage] = useState("");
  const [profileError, setProfileError] = useState("");
  const [sending, setSending] = useState(false);
  const [resetMessage, setResetMessage] = useState("");
  const [resetError, setResetError] = useState("");

  async function save(event: SubmitEvent<HTMLFormElement>) {
    event.preventDefault();
    if (!draft || saving) return;
    setSaving(true);
    setProfileMessage("");
    setProfileError("");

    try {
      const result = await submitAccountRequest(path, "PATCH", draft);
      setProfile(result.profile);
      setDraft(result.profile);
      setEditing(false);
      setProfileMessage("基本情報を保存しました。");
      router.refresh();
    } catch (error) {
      setProfileError((error as Error).message);
    } finally {
      setSaving(false);
    }
  }

  async function sendResetLink() {
    if (sending) return;
    setSending(true);
    setResetMessage("");
    setResetError("");

    try {
      await submitAccountRequest(`${path}/password-reset`, "POST");
      setResetMessage(
        "再設定メールを送信しました。メール内のリンクを開いてください。変更後は新しいパスワードでログインしてください。",
      );
    } catch (error) {
      setResetError((error as Error).message);
    } finally {
      setSending(false);
    }
  }

  return (
    <div className={styles.settings}>
      <section className={styles.section}>
        <h2>基本情報</h2>
        {profileError && <p role="alert">{profileError}</p>}
        {profileMessage && <p role="status">{profileMessage}</p>}
        {profile ? (
          <form className={styles.form} onSubmit={save}>
            <table className={styles.table}>
              <tbody>
                {fields.map(({ key, label, type, ...options }) => (
                  <tr key={key}>
                    <th scope="row">
                      {editing ? (
                        <label htmlFor={`profile-${key}`}>{label}</label>
                      ) : (
                        label
                      )}
                    </th>
                    <td>
                      {editing && draft ? (
                        <input
                          id={`profile-${key}`}
                          type={type}
                          {...options}
                          maxLength={255}
                          required={key === "name"}
                          value={draft[key] ?? ""}
                          disabled={saving}
                          onChange={(event) =>
                            setDraft({ ...draft, [key]: event.target.value })
                          }
                        />
                      ) : (
                        profile[key] || "未登録"
                      )}
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
            <div className={styles.actions}>
              {editing ? (
                <>
                  <button
                    type="submit"
                    className={styles.submit}
                    disabled={saving}
                  >
                    {saving ? "保存中…" : "変更を保存"}
                  </button>
                  <ActionButton
                    variant="text"
                    disabled={saving}
                    onClick={() => {
                      setEditing(false);
                      setProfileError("");
                    }}
                  >
                    キャンセル
                  </ActionButton>
                </>
              ) : (
                <ActionButton
                  onClick={() => {
                    setDraft(profile);
                    setEditing(true);
                    setProfileMessage("");
                  }}
                >
                  基本情報を編集する
                </ActionButton>
              )}
            </div>
            <p>
              ＊職業は整体に必要な範囲でご記入ください。（デスクワーク・重いものを運ぶなど）
            </p>
          </form>
        ) : (
          <p>基本情報を編集するには、クライアント情報との連携が必要です。</p>
        )}
      </section>

      <section className={styles.section}>
        <h2>パスワードリセット</h2>
        <p>
          登録済みのメールアドレス（{settings.email}）に再設定リンクを送ります。
        </p>
        <div className={styles.actions}>
          <ActionButton
            variant="outline"
            disabled={sending}
            onClick={() => void sendResetLink()}
          >
            {sending ? "送信中…" : "パスワード再設定メールを送る"}
          </ActionButton>
        </div>
        {resetMessage && <p role="status">{resetMessage}</p>}
        {resetError && <p role="alert">{resetError}</p>}
      </section>

      <AccountTwoFactor initialStatus={settings.twoFactor} />
    </div>
  );
}
