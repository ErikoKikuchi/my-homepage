import { useForm } from "@inertiajs/react";
import type { SubmitEvent } from "react";
import EmailInput from "../UI/Form/EmailInput";
import PasswordInput from "../UI/Form/PasswordInput";
import styles from "./LoginForm.module.css";

type LoginFormData = {
    email: string;
    password: string;
    section: "pilates" | "thinkmotion";
};

export function LoginForm() {
    const { data, setData, post, processing, errors, reset } =
        useForm<LoginFormData>({
            email: "",
            password: "",
            section: "pilates",
        });
    function handleSubmit(event: SubmitEvent<HTMLFormElement>) {
        event.preventDefault();

        if (processing) return;

        post("/admin/login", {
            onFinish: () => reset("password"),
        });
    }

    return (
        <form onSubmit={handleSubmit} className={styles.loginForm}>
            <EmailInput
                value={data.email}
                onChange={(value) => setData("email", value)}
                error={errors.email}
            ></EmailInput>
            <PasswordInput
                value={data.password}
                onChange={(value) => setData("password", value)}
                error={errors.password}
            ></PasswordInput>
            <fieldset
                className={styles.radioButtonGroup}
                aria-describedby={errors.section ? "section-error" : undefined}
            >
                <legend>ログイン先</legend>
                <label>
                    <input
                        type="radio"
                        name="section"
                        value="pilates"
                        checked={data.section === "pilates"}
                        onChange={() => setData("section", "pilates")}
                        required
                        className={styles.radioButton}
                    />
                    Pilates
                </label>
                <label>
                    <input
                        type="radio"
                        name="section"
                        value="thinkmotion"
                        checked={data.section === "thinkmotion"}
                        onChange={() => setData("section", "thinkmotion")}
                        required
                        className={styles.radioButton}
                    />
                    ThinkMotion
                </label>
                {errors.section && (
                    <p id="section-error" role="alert">
                        {errors.section}
                    </p>
                )}
            </fieldset>
            <button
                type="submit"
                disabled={processing}
                className={styles.submitButton}
            >
                {processing ? "ログイン中…" : "ログイン"}
            </button>
        </form>
    );
}
