import styles from "./page.module.css";
import PageHeader from "@/components/public/common/PageHeader";
import { RegisterForm } from "@/features/auth/RegisterForm";

export default function PilatesLogin() {
  return (
    <>
      <PageHeader heading="新規登録"></PageHeader>
      <div className={styles.registerForm}>
        <RegisterForm></RegisterForm>
      </div>
    </>
  );
}
