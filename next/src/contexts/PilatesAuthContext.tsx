"use client";

import { createContext, useCallback, useEffect, useState } from "react";
import type { AuthPilatesUser } from "@/types/auth/auth";
import { getCurrentPilatesUser } from "@/lib/api/auth/authPilates";

type PilatesAuthContextValue = {
  user: AuthPilatesUser | null;
  isLoading: boolean;
  error: Error | null;
  refresh: () => Promise<void>;
};

export const PilatesAuthContext = createContext<
  PilatesAuthContextValue | undefined
>(undefined);

export function PilatesAuthProvider({
  children,
}: {
  children: React.ReactNode;
}) {
  const [user, setUser] = useState<AuthPilatesUser | null>(null);
  const [isLoading, setIsLoading] = useState(true);
  const [error, setError] = useState<Error | null>(null);

  const refresh = useCallback(async () => {
    try {
      setError(null);
      setUser(await getCurrentPilatesUser());
    } catch (e) {
      setUser(null);
      setError(e instanceof Error ? e : new Error("unknown error"));
    } finally {
      setIsLoading(false);
    }
  }, []);

  useEffect(() => {
    let cancelled = false;
    (async () => {
      try {
        const u = await getCurrentPilatesUser();
        if (!cancelled) setUser(u);
      } catch (e) {
        if (!cancelled) {
          setUser(null);
          setError(e instanceof Error ? e : new Error("unknown error"));
        }
      } finally {
        if (!cancelled) setIsLoading(false);
      }
    })();

    return () => {
      cancelled = true;
    };
  }, []);

  return (
    <PilatesAuthContext.Provider value={{ user, isLoading, error, refresh }}>
      {children}
    </PilatesAuthContext.Provider>
  );
}
