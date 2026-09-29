"use client";

import { LogoutButton } from "./LogoutButton";
import { logoutThinkMotion } from "@/lib/api/auth/authThinkMotion";
import { useThinkMotionAuth } from "@/hooks/useThinkMotionAuth";

export function ThinkMotionLogoutButton() {
  const { refresh } = useThinkMotionAuth();
  return (
    <LogoutButton
      logoutRequest={logoutThinkMotion}
      refresh={refresh}
      loginPath="/thinkmotion/login"
    />
  );
}
