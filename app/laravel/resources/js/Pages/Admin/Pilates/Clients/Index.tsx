import axios from "axios";
import { router } from "@inertiajs/react";
import { useEffect, useRef, useState } from "react";
import ActionButton from "@/Components/Admin/UI/ActionButton/ActionButton";
import { FieldError } from "../AdminPage";
import styles from "../AdminPage.module.css";
import { admin } from "../schedule";
import ClientList, { type AccountPage, type StandaloneClientPage } from "./ClientList";

const genders = [["female", "女性"], ["male", "男性"], ["other", "その他"]] as const;

type RegistrationTarget = { initialName: string; url: string; userId?: string };

function RegisterDialog({ target, close }: { target: RegistrationTarget; close: () => void }) {
    const dialog = useRef<HTMLDialogElement>(null);
    const [name, setName] = useState(target.initialName);
    const [gender, setGender] = useState("");
    const [error, setError] = useState("");
    const [processing, setProcessing] = useState(false);
    useEffect(() => { dialog.current?.showModal(); }, []);

    return (
        <dialog ref={dialog} className={styles.dialog} aria-labelledby="register-title" onCancel={(event) => {
            event.preventDefault();
            if (!processing) close();
        }}>
            <h2 id="register-title">クライアント登録</h2>
            <form className={styles.form} onSubmit={async (event) => {
                event.preventDefault();
                if (processing) return;
                setError("");
                setProcessing(true);
                try {
                    await axios.post(target.url, { user_id: target.userId, name, gender }, {
                        headers: { Accept: "application/json" },
                    });
                    close();
                    router.reload({ only: ["users", "pendingUsers", "standaloneClients", "provisionalClients"] });
                } catch (failure) {
                    setError(axios.isAxiosError(failure) ? failure.response?.data?.message ?? "登録できませんでした。" : "登録できませんでした。");
                } finally {
                    setProcessing(false);
                }
            }}>
                <label>氏名<input value={name} required onChange={(event) => setName(event.target.value)} /></label>
                <fieldset>
                    <legend>性別</legend>
                    <div className={styles.actions}>
                        {genders.map(([value, label]) => (
                            <label key={value}>
                                <input type="radio" name="gender" value={value} required checked={gender === value} onChange={() => setGender(value)} />
                                {label}
                            </label>
                        ))}
                    </div>
                </fieldset>
                <FieldError message={error} />
                <div className={styles.actions}>
                    <ActionButton variant="outline" disabled={processing} onClick={close}>キャンセル</ActionButton>
                    <ActionButton type="submit" disabled={processing}>登録する</ActionButton>
                </div>
            </form>
        </dialog>
    );
}

export default function ClientsIndex({ users, pendingUsers, standaloneClients, provisionalClients }: {
    users: AccountPage; pendingUsers: AccountPage; standaloneClients: StandaloneClientPage; provisionalClients: StandaloneClientPage;
}) {
    const [target, setTarget] = useState<RegistrationTarget | null>(null);

    return (
        <>
            <ClientList page={users} pendingUsers={pendingUsers} standaloneClients={standaloneClients} provisionalClients={provisionalClients}
                register={(user) => (
                    <ActionButton onClick={() => setTarget({
                        initialName: user.client?.name ?? user.profile?.display_name ?? "",
                        url: `${admin}/clients`,
                        userId: user.id,
                    })}>クライアント登録</ActionButton>
                )}
                registerStandalone={(client) => (
                    <ActionButton onClick={() => setTarget({
                        initialName: client.name,
                        url: `${admin}/clients/${client.id}/register`,
                    })}>クライアント登録</ActionButton>
                )}
            />
            {target && <RegisterDialog target={target} close={() => setTarget(null)} />}
        </>
    );
}
