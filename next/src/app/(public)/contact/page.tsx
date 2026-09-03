// next/src/app/(public)/contact/page.tsx
import InquiryPreview from "@/components/public/contact/InquiryPreview";
import { inquiryItems } from "@/components/public/contact/inquiryData";
import styles from "./page.module.css";

export default function ContactPage() {
  return (
    <>
      <main className={styles.contactMain}>
        <h1 className={styles.pageTitle}>お問い合わせ</h1>
        <div className={styles.inquiryGrid}>
          {inquiryItems.map((item) => (
            <InquiryPreview key={item.id} item={item} />
          ))}
        </div>
      </main>
    </>
  );
}
