"use client";

import { useState } from "react";
import styles from "./InquiryPreview.module.css";
import type { InquiryItem } from "./inquiryData";
import LinkButton from "@/components/ui/LinkButton/LinkButton";

interface InquiryPreviewProps {
  item: InquiryItem;
}

export default function InquiryPreview({ item }: InquiryPreviewProps) {
  const [isOpen, setIsOpen] = useState(false);

  return (
    <div className={styles.inquiryPreview}>
      <h3 className={styles.title}>{item.title}</h3>
      <p className={styles.description}>{item.description}</p>

      <button
        type="button"
        onClick={() => setIsOpen((prev) => !prev)}
        className={styles.toggleButton}
        aria-expanded={isOpen}
      >
        {isOpen ? "詳細項目を閉じる" : "詳細項目を見る"}
      </button>

      {isOpen && (
        <ul className={styles.topicList}>
          {item.topics.map((topic) => (
            <li key={topic}>{topic}</li>
          ))}
        </ul>
      )}
      <div className={styles.buttonWrapper}>
        <LinkButton href={item.googleFormUrl} external>
          Googleフォームへ進む
        </LinkButton>
      </div>
    </div>
  );
}
