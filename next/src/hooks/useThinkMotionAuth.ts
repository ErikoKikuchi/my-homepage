import { useContext } from "react";
import { ThinkMotionAuthContext } from "@/contexts/ThinkMotionAuthContext";

export function useThinkMotionAuth() {
  const context = useContext(ThinkMotionAuthContext);

  if (context === undefined) {
    throw new Error(
      "useThinkMotionAuth must be used within ThinkMotionAuthProvider",
    );
  }

  return context;
}
