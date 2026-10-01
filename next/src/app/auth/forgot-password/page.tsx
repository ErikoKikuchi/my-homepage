import { ForgotPasswordForm } from "@/features/auth/ForgotPasswordForm";
import PageHeader from "@/components/public/common/PageHeader";
import styles from "./page.module.css";

export default async function ForgotPasswordPage() {
  return (
    <>
      <PageHeader heading="パスワードをお忘れの方はこちら"></PageHeader>
      <div className={styles.forgotPasswordForm}>
        <ForgotPasswordForm />
      </div>
    </>
  );
}
