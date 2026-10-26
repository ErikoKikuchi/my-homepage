import PageHeader from "@/Components/Admin/UI/PageHeader";
import LoginForm from "@/Components/Admin/Auth/LoginForm";
import styles from "./Login.module.css";

export default function LoginForm(){
    return (
    <>
      <PageHeader heading="管理者ログインフォーム"></PageHeader>
      <div className={styles.loginForm}>
        <LoginForm></LoginForm>
      </div>
    </>
  );
}
