import { Link, useForm, usePage } from "@inertiajs/react";
import styles from "./AdminThinkMotionSiteNav.module.css";

export default function AdminThinkMotionNav() {
    const { props, url } = usePage<{
        adminAuthenticated: boolean;
    }>();
    const { post, processing } = useForm({});
    const dashboardUrl = "/thinkmotion/admin/dashboard";

    return (
        <nav
            aria-label="ThinkMotion管理者メニュー"
            className={styles.adminThinkMotionNav}
        >
            <Link
                href={props.adminAuthenticated ? dashboardUrl : "/admin/login"}
                className={styles.link}
            >
                ThinkMotion · 管理画面
            </Link>
            <ul className={styles.linkIndex}>
                {props.adminAuthenticated ? (
                    <>
                        <li>
                            <Link
                                href={dashboardUrl}
                                className={styles.link}
                                aria-current={
                                    url.split("?")[0] === dashboardUrl
                                        ? "page"
                                        : undefined
                                }
                            >
                                ダッシュボード
                            </Link>
                        </li>

                        <li>
                            <button
                                type="button"
                                className={styles.submitButton}
                                disabled={processing}
                                onClick={() =>
                                    post("/thinkmotion/admin/logout")
                                }
                            >
                                ログアウト
                            </button>
                        </li>
                    </>
                ) : (
                    <li>
                        <Link
                            href="/admin/login"
                            className={styles.submitButton}
                        >
                            ログイン
                        </Link>
                    </li>
                )}
            </ul>
        </nav>
    );
}
