import styles from "./LineLink.module.css";
import Image from "next/image";
import LinkButton from "@/components/ui/LinkButton/LinkButton";

export default function LineLink() {
  return (
    <div className={styles.lineGroup}>
      <Image
        src="/site-images/line-qr.png"
        alt="LINE QRコード"
        className={styles.qrCode}
        width={128}
        height={128}
      />
      <LinkButton
        href="https://lin.ee/9E3PPH9"
        external
        className={styles.linkButton}
      >
        LINEに移動する
      </LinkButton>
    </div>
  );
}
