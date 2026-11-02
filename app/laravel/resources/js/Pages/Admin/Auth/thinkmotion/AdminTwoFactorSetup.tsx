import { useForm } from "@inertiajs/react";
import type { SubmitEvent } from "react";
import PageHeader from "@/Components/Admin/UI/PageHeader";
import TwoFactorInput from "@/Components/Admin/UI/Form/TwoFactorInput";
import styles from "./AdminTwoFactorSetup.module.css";

type TwoFactorData = {
    two_factor_secret: string;
};

type Props = {
    qrCode: string;
};

export default function AdminThinkMotionTwoFactorSetup({ qrCode }: Props) {
    const { data, setData, post, processing, errors, reset } =
        useForm<TwoFactorData>({
            two_factor_secret: "",
        });
    function handleSubmit(event: SubmitEvent<HTMLFormElement>) {
        event.preventDefault();

        if (processing) return;

        post("/thinkmotion/admin/two-factor/setup", {
            onFinish: () => reset("two_factor_secret"),
        });
    }
    return (
        <form onSubmit={handleSubmit} className={styles.main}>
            <PageHeader heading="ThinkMotion管理者二要素認証初回セットアップ"></PageHeader>
            <div className={styles.twoFactorWrapper}>
                <p>
                    認証アプリでQRコードを読み取り、
                    表示された6桁のコードを入力してください。
                </p>

                {/* サーバー側で生成したQRコードのSVG */}
                <div dangerouslySetInnerHTML={{ __html: qrCode }} />

                <TwoFactorInput
                    value={data.two_factor_secret}
                    onChange={(value) => setData("two_factor_secret", value)}
                    error={errors.two_factor_secret}
                    label="認証コード"
                    autoComplete="one-time-code"
                    placeholder="6桁のコード"
                ></TwoFactorInput>
                <button type="submit" disabled={processing}>
                    {processing ? "確認中…" : "設定する"}
                </button>
            </div>
        </form>
    );
}
