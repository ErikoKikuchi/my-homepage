import { ResetPasswordForm } from "@/components/auth/ResetPasswordForm";
import PageHeader from "@/components/public/common/PageHeader";
import styles from "./page.module.css";

interface ResetPasswordPageProps {
  params: Promise<{ token: string }>;
}

export default async function ResetPasswordPage({
  params,
}: ResetPasswordPageProps) {
  const { token } = await params;

  return (
    <>
      <PageHeader heading="パスワード再設定"></PageHeader>
      <div className={styles.resetPasswordForm}>
        <ResetPasswordForm token={token} />
      </div>
    </>
  );
}
