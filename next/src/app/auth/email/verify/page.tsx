import { EmailVerify } from "@/features/auth/EmailVerify";
import PageHeader from "@/components/public/common/PageHeader";
import styles from "./page.module.css";

export default async function EmailVerifyForm() {
  return (
    <>
      <PageHeader heading="メール認証"></PageHeader>
      <div className={styles.emailVerify}>
        <div className={styles.emailVerifyDescription}>
          <p className={styles.descriptionText}>
            登録していただいたメールアドレスに認証メールを送付しました。
          </p>
          <p className={styles.descriptionText}>
            メール内認証を完了してください。
          </p>
        </div>
        <EmailVerify />
      </div>
    </>
  );
}
