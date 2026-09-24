import styles from "./page.module.css";
import PageHeader from "@/components/public/common/PageHeader";
import { PilatesLoginForm } from "@/components/auth/PilatesLoginForm";

export default function PilatesLogin() {
  return (
    <>
      <PageHeader heading="Pilates ログインフォーム"></PageHeader>
      <div className={styles.pilatesLoginForm}>
        <PilatesLoginForm></PilatesLoginForm>
      </div>
    </>
  );
}
