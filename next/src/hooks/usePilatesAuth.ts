import { useContext } from "react";
import { PilatesAuthContext } from "@/contexts/PilatesAuthContext";

export function usePilatesAuth() {
  const context = useContext(PilatesAuthContext);

  if (context === undefined) {
    throw new Error("useAuth must be used within AuthProvider");
  }

  return context;
}
