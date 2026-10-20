import PageHeader from "@/components/public/common/PageHeader";
import styles from "./page.module.css";

export default async function TrainingLogs() {
  return (
    <>
      <div className={styles.main}>
        <PageHeader heading={"BodyMind"}></PageHeader>
      </div>
    </>
  );
}
