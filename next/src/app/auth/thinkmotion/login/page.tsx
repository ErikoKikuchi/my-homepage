import styles from "./page.module.css";
import PageHeader from "@/components/public/common/PageHeader";
import { ThinkMotionLoginForm } from "@/components/auth/ThinkMotionLoginForm";

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
