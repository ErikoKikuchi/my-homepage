import PageHeader from "@/components/public/common/PageHeader";
import Section from "@/components/public/common/Section";
import styles from "./page.module.css";
import LinkButton from "@/components/ui/LinkButton/LinkButton";

export default function MyPage() {
  return (
    <>
      <div className={styles.main}>
        <PageHeader heading={"回数券購入はこちら"}></PageHeader>
      </div>
    </>
  );
}
