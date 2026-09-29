import PageHeader from "@/components/public/common/PageHeader";
import Section from "@/components/public/common/Section";
import PriceTable from "@/components/pilates/top/Price";
import PilatesReservation from "@/components/pilates/top/PilatesReservation";
import styles from "./page.module.css";
import LinkButton from "@/components/ui/LinkButton/LinkButton";

export default function MyPage() {
  return (
    <>
      <div className={styles.main}>
        <PageHeader heading={"マイページ"}></PageHeader>
      </div>
    </>
  );
}
