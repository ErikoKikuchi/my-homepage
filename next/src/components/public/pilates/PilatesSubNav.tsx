"use client";

import Link from "next/link";
import { LogoutButton } from "@/components/auth/LogoutButton";
import { usePilatesAuth } from "@/hooks/usePilatesAuth";
import styles from "./PilatesSubNav.module.css";

export default function PilatesSubNav() {
  const { user, isLoading } = usePilatesAuth();

  // 取得中は何も出さない(ログインリンクが一瞬見えるチラつきを防ぐ)
  if (isLoading) return null;

  // ログイン中
  return (
    <nav className={styles.subNav} aria-label="ピラティスメニュー">
      <ul className={styles.list}>
        {user ? (
          <>
            <li>
              <Link href="/pilates/mypage" className={styles.link}>
                マイページ
              </Link>
            </li>
            {user.canUseTrainingLog && (
              <li>
                <Link
                  href="/pilates/mypage/training-log"
                  className={styles.link}
                >
                  自主トレログ
                </Link>
              </li>
            )}
            <li>
              <LogoutButton />
            </li>
          </>
        ) : (
          <li>
            <Link href="/auth/pilates/login" className={styles.link}>
              ログイン
            </Link>
          </li>
        )}
      </ul>
    </nav>
  );
}
