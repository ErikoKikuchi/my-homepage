"use client";

import { LogoutButton } from "./LogoutButton";
import { logoutPilates } from "@/lib/api/auth/authPilates";
import { usePilatesAuth } from "@/hooks/usePilatesAuth";

export function PilatesLogoutButton() {
  const { refresh } = usePilatesAuth();
  return (
    <LogoutButton
      logoutRequest={logoutPilates}
      refresh={refresh}
      loginPath="/pilates/login"
    />
  );
}
