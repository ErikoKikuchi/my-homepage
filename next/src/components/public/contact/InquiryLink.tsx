// next/src/components/public/contact/InquiryLink.tsx
import styles from "./InquiryLink.module.css";
import type { InquiryItem } from "./inquiryData";

interface InquiryLinkProps {
  item: InquiryItem;
}

export default function InquiryLink({ item }: InquiryLinkProps) {
  return (
    <a
      href={item.formUrl}
      target="_blank"
      rel="noopener noreferrer"
      className={styles.inquiryLink}
    >
      {item.label}
    </a>
  );
}
