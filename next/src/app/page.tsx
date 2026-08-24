"use client";

import { useState } from "react";
import ConceptWords from "@/components/public/top/ConceptWords";
import Leaves from "@/components/public/top/Leaves";

export default function Page() {
  const [leavesStarted, setLeavesStarted] = useState(false);

  return (
    <>
      <ConceptWords onComplete={() => setLeavesStarted(true)} />
      <Leaves start={leavesStarted} />
    </>
  );
}
