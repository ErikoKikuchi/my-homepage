import PageHeader from "@/Components/Admin/UI/PageHeader";
import { LoginForm } from "@/Components/Admin/Auth/LoginForm";
import styles from "./Login.module.css";

export default function AdminLogin() {
    return (
        <div className={styles.main}>
            <PageHeader heading="管理者ログインフォーム"></PageHeader>
            <div className={styles.loginFormWrapper}>
                <LoginForm></LoginForm>
            </div>
        </div>
    );
}
