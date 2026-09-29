import { useContext } from "react";
import { ThinkMotionAuthContext } from "@/contexts/ThinkMotionAuthContext";

export function useThinkMotionAuth() {
  const context = useContext(ThinkMotionAuthContext);

  if (context === undefined) {
    throw new Error("useAuth must be used within AuthProvider");
  }

  return context;
}
