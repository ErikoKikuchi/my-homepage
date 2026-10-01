import PageHeader from "@/components/public/common/PageHeader";
import Section from "@/components/public/common/Section";
import styles from "./page.module.css";
import LinkButton from "@/components/ui/LinkButton/LinkButton";

export default function EditTrainingLog() {
  return (
    <>
      <div className={styles.main}>
        <PageHeader heading={"BodyMind編集"}></PageHeader>
      </div>
    </>
  );
}
