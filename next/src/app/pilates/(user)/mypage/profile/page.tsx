import PageHeader from "@/components/public/common/PageHeader";
import styles from "./page.module.css";

export default async function AccountEditPage() {
  return (
    <>
      <div className={styles.main}>
        <PageHeader heading={"アカウント修正"}></PageHeader>
      </div>
    </>
  );
}
//二要素認証の推奨と名前変更
