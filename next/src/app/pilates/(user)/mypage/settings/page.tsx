import PageHeader from "@/components/public/common/PageHeader";
import LinkButton from "@/components/ui/LinkButton/LinkButton";
import AccountSettings from "@/features/pilates/user/mypage/AccountSettings";
import { getAccountSettings } from "@/lib/api/pilates/mypageServer";
import styles from "../page.module.css";

export default async function SettingsPage() {
  const settings = await getAccountSettings();

  return (
    <div className={styles.main}>
      <PageHeader heading="アカウント設定" />
      <AccountSettings settings={settings} />
      <LinkButton href="/pilates/mypage" variant="outline">マイページに戻る</LinkButton>
    </div>
  );
}
