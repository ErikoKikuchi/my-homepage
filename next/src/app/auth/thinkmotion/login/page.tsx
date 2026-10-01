import styles from "./page.module.css";
import PageHeader from "@/components/public/common/PageHeader";
import { ThinkMotionLoginForm } from "@/features/auth/ThinkMotionLoginForm";

export default function ThinkMotionLogin() {
  return (
    <>
      <PageHeader heading="ThinkMotion ログインフォーム"></PageHeader>
      <div className={styles.thinkMotionLoginForm}>
        <ThinkMotionLoginForm></ThinkMotionLoginForm>
      </div>
    </>
  );
}
