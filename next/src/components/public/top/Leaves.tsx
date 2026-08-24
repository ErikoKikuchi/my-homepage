"use client";

import { useState, useEffect } from "react";
import Leaf from "./Leaf";
import { leafItems } from "./leafData";
import styles from "./Leaves.module.css";

interface LeavesProps {
  start: boolean;
}

const DROP_INTERVAL_MS = 200;

function Leaves({ start }: LeavesProps) {
  const [droppedCount, setDroppedCount] = useState(0);
  const [activeId, setActiveId] = useState<string | null>(null);

  useEffect(() => {
    if (!start) return;
    if (droppedCount >= leafItems.length) return;

    const timer = setTimeout(() => {
      setDroppedCount((prev) => prev + 1);
    }, DROP_INTERVAL_MS);

    return () => clearTimeout(timer);
  }, [start, droppedCount]);

  const activeItem = leafItems.find((item) => item.id === activeId);

  return (
    <>
      <ul className={styles.leafMenu}>
        {leafItems.map((item, index) => (
          <Leaf
            key={item.id}
            item={item}
            shouldFall={index < droppedCount}
            isActive={item.id === activeId}
            onActivate={setActiveId}
          />
        ))}
      </ul>

      <div
        className={`${styles.leafDescription} ${
          activeItem ? styles.visible : ""
        }`}
      >
        <p>{activeItem?.description ?? ""}</p>
      </div>
    </>
  );
}

export default Leaves;
