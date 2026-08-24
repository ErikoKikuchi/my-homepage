"use client";

import { useState } from "react";
import Link from "next/link";
import styles from "./Leaf.module.css";
import type { LeafItem } from "./leafData";

interface LeafProps {
  item: LeafItem;
  shouldFall: boolean;
  isActive: boolean;
  onActivate: (id: string | null) => void;
}

export default function Leaf({
  item,
  shouldFall,
  isActive,
  onActivate,
}: LeafProps) {
  const [hasSettled, setHasSettled] = useState(false);

  const handleAnimationEnd = () => {
    // fallアニメーション終了時のみ発火させる(swayと誤検知しないように)
    setHasSettled(true);
  };

  const handleMouseEnter = () => {
    if (hasSettled) {
      onActivate(item.id);
    }
  };

  const handleMouseLeave = () => {
    onActivate(null);
  };

  const handleTouchStart = (e: React.TouchEvent) => {
    e.preventDefault();
    if (hasSettled || isActive) {
      onActivate(item.id);
    }
  };

  const outerClassName = [
    styles.leafOuter,
    shouldFall ? styles.falling : "",
    hasSettled ? styles.settled : "",
  ]
    .filter(Boolean)
    .join(" ");

  return (
    <li className={outerClassName} onAnimationEnd={handleAnimationEnd}>
      <Link
        href={item.page}
        className={`${styles.leaf} ${isActive ? styles.active : ""}`}
        onMouseEnter={handleMouseEnter}
        onMouseLeave={handleMouseLeave}
        onTouchStart={handleTouchStart}
      >
        {item.label}
      </Link>
    </li>
  );
}
