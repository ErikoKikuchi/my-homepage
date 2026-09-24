"use client";

import { createContext, useEffect, useState } from "react";
import type { AuthPilatesUser } from "@/types/auth/auth";
import { getCurrentPilatesUser } from "@/lib/api/auth/authClient";

type PilatesAuthContextValue = {
  user: AuthPilatesUser | null;
  isLoading: boolean;
  error: Error | null;
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

  useEffect(() => {
    getCurrentPilatesUser()
      .then((user) => {
        setUser(user);
      })
      .catch((error) => {
        setError(error);
      })
      .finally(() => {
        setIsLoading(false);
      });
  }, []);

  return (
    <PilatesAuthContext.Provider value={{ user, isLoading, error }}>
      {children}
    </PilatesAuthContext.Provider>
  );
}
