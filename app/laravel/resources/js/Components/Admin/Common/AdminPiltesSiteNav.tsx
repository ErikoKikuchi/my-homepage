import { Link, useForm, usePage } from "@inertiajs/react";
import styles from ./AdminPilatesSiteNav.module.css

export default function AdminPilatesNav() {
    const { props, url } = usePage<{
        adminAuthenticated: boolean;
    }>();
    const { post, processing } = useForm({});
    const dashboardUrl = "/pilates/admin/home";

    return (
        <nav aria-label="ピラティス管理者メニュー" className={styles.adminPilatesNav}>
            <Link
                href={props.adminAuthenticated ? dashboardUrl : "/admin/login"}
                className={styles.link}
            >
                からだ散歩 · 管理画面
            </Link>
            <ul className={styles.linkIndex}>
            {props.adminAuthenticated ? (
                    <>
                        <li>
                            <Link
                                href={dashboardUrl}
                                className={linkClass}
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
                                className={`${linkClass} cursor-pointer disabled:cursor-wait disabled:opacity-50`}
                                disabled={processing}
                                onClick={() =>
                                    post("/pilates/admin/logout")
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
                            className={linkClass}
                        >
                            ログイン
                        </Link>
                    </li>
                )}
            </ul>
        </nav>
    );
}
