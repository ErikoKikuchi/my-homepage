import PageHeader from "@/components/public/common/PageHeader";
import LinkButton from "@/components/ui/LinkButton/LinkButton";
import HopeHistory from "@/features/pilates/user/mypage/HopeHistory";
import { getHopeHistory } from "@/lib/api/pilates/mypageServer";
import styles from "../page.module.css";

export default async function GoalsPage() {
  const { hopes, hasClient } = await getHopeHistory();

  return (
    <div className={styles.main}>
      <PageHeader heading="からだの目標を編集" />
      <HopeHistory hopes={hopes} hasClient={hasClient} />
      <LinkButton href="/pilates/mypage" variant="outline">マイページに戻る</LinkButton>
    </div>
  );
}
