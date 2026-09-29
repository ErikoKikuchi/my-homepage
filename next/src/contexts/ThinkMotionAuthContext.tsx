"use client";

import { createContext, useCallback, useEffect, useState } from "react";
import type { AuthThinkMotionUser } from "@/types/auth/auth";
import { getCurrentThinkMotionUser } from "@/lib/api/auth/authThinkMotion";

type ThinkMotionAuthContextValue = {
  user: AuthThinkMotionUser | null;
  isLoading: boolean;
  error: Error | null;
  refresh: () => Promise<void>;
};

export const ThinkMotionAuthContext = createContext<
  ThinkMotionAuthContextValue | undefined
>(undefined);

export function ThinkMotionAuthProvider({
  children,
}: {
  children: React.ReactNode;
}) {
  const [user, setUser] = useState<AuthThinkMotionUser | null>(null);
  const [isLoading, setIsLoading] = useState(true);
  const [error, setError] = useState<Error | null>(null);

  const refresh = useCallback(async () => {
    try {
      setError(null);
      setUser(await getCurrentThinkMotionUser());
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
        const u = await getCurrentThinkMotionUser();
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
    <ThinkMotionAuthContext.Provider
      value={{ user, isLoading, error, refresh }}
    >
      {children}
    </ThinkMotionAuthContext.Provider>
  );
}
