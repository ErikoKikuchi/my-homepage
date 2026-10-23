import { useContext } from "react";
import { PilatesAuthContext } from "@/contexts/PilatesAuthContext";

export function usePilatesAuth() {
  const context = useContext(PilatesAuthContext);

  if (context === undefined) {
    throw new Error("usePilatesAuth must be used within PilatesAuthProvider");
  }

  return context;
}
