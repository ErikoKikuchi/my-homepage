"use client";

import { useState, useEffect } from "react";
import styles from "./ConceptWords.module.css";

const CONCEPT_WORDS = [
  "あなたの身体には、理由がある。",
  "その痛み、どこから来ていますか？",
  "ここから、一緒に読み解く。",
  "身体を知ることは、自分を知ること。",
  "身体の声を、言葉にする場所。",
  "からだ散歩、はじめましょう。",
];

interface ConceptWordsProps {
  onComplete?: () => void;
}

function ConceptWords({ onComplete }: ConceptWordsProps) {
  const [currentIndex, setCurrentIndex] = useState(0);
  type AnimationPhase = "fade-in" | "visible" | "fade-out";
  const [phase, setPhase] = useState<AnimationPhase>("fade-in");

  useEffect(() => {
    let delay = 0;
    let next: () => void = () => {};

    if (phase === "fade-in") {
      delay = 1500;
      next = () => setPhase("visible");
    } else if (phase === "visible") {
      delay = 3000;
      next = () => setPhase("fade-out");
    } else if (phase === "fade-out") {
      delay = 800;
      next = () => {
        if (currentIndex < CONCEPT_WORDS.length - 1) {
          setCurrentIndex((prev) => prev + 1);
          setPhase("fade-in");
        } else {
          onComplete?.();
        }
      };
    }

    const timer = setTimeout(next, delay);
    return () => clearTimeout(timer);
  }, [phase, currentIndex, onComplete]);
  return (
    // visible状態はアニメーションクラスがないため、styles[phase]はundefinedになり "" にフォールバックするように記載
    <p className={`${styles.concept} ${styles[phase] ?? ""}`}>
      {CONCEPT_WORDS[currentIndex]}
    </p>
  );
}
export default ConceptWords;
