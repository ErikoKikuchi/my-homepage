import PageHeader from "@/components/public/common/PageHeader";
import Section from "@/components/public/common/Section";
import styles from "./page.module.css";
import LinkButton from "@/components/ui/LinkButton/LinkButton";

export default function CreateTrainingLog() {
  return (
    <>
      <div className={styles.main}>
        <PageHeader heading={"今日の記録を作成"}></PageHeader>
      </div>
    </>
  );
}
