"use client";

import Link from "next/link";
import { LogoutButton } from "@/components/auth/LogoutButton";
import { useThinkMotionAuth } from "@/hooks/useThinkMotionAuth";
import styles from "./ThinkMotionSubNav.module.css";

export default function ThinkMotionSubNav() {
  const { user, isLoading } = useThinkMotionAuth();

  // 取得中は何も出さない(ログインリンクが一瞬見えるチラつきを防ぐ)
  if (isLoading) return null;

  // ログイン中
  return (
    <nav className={styles.subNav} aria-label="ThinkMotionメニュー">
      <ul className={styles.list}>
        {user ? (
          <>
            <li>
              <Link href="/thinkmotion/mypage" className={styles.link}>
                マイページ
              </Link>
            </li>
            <li>
              <LogoutButton />
            </li>
          </>
        ) : (
          <li>
            <Link href="/auth/thinkmotion/login" className={styles.link}>
              ログイン
            </Link>
          </li>
        )}
      </ul>
    </nav>
  );
}
