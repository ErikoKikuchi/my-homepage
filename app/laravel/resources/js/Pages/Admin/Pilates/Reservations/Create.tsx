import { useForm } from "@inertiajs/react";
import { useEffect, useRef, useState } from "react";
import LinkButton from "@/Components/Admin/UI/LinkButton/LinkButton";
import ActionButton from "@/Components/Admin/UI/ActionButton/ActionButton";
import AdminPage, { FieldError } from "../AdminPage";
import styles from "../AdminPage.module.css";
import { admin, dateInputValue, lessonTime, type LessonSlot, type NamedOption } from "../schedule";

type SearchResult = NamedOption & { phone: string | null; relationship_note: string | null };

export default function ReservationsCreate({ lessonSlot }: { lessonSlot: LessonSlot }) {
    const form = useForm({ client_id: "", name: "", phone: "", relationship_note: "", note: "" });
    const [results, setResults] = useState<SearchResult[]>([]);
    const [searchError, setSearchError] = useState("");
    const [showResults, setShowResults] = useState(false);
    const searchArea = useRef<HTMLDivElement>(null);
    const query = form.data.client_id ? "" : form.data.name.trim();

    useEffect(() => {
        if (!showResults) return;

        function closeOutsideSearch(event: Event) {
            if (event.target instanceof Node && !searchArea.current?.contains(event.target)) {
                setShowResults(false);
            }
        }

        document.addEventListener("pointerdown", closeOutsideSearch);
        document.addEventListener("focusin", closeOutsideSearch);
        return () => {
            document.removeEventListener("pointerdown", closeOutsideSearch);
            document.removeEventListener("focusin", closeOutsideSearch);
        };
    }, [showResults]);

    useEffect(() => {
        setResults([]);
        setSearchError("");
        if (!query) return;
        const controller = new AbortController();
        const timer = window.setTimeout(async () => {
            try {
                const response = await fetch(`${admin}/client/search?q=${encodeURIComponent(query)}`, {
                    headers: { Accept: "application/json" }, signal: controller.signal,
                });
                if (!response.ok) throw new Error();
                const clients: SearchResult[] = await response.json();
                if (!controller.signal.aborted) setResults(clients);
            } catch {
                if (!controller.signal.aborted) setSearchError("クライアントを検索できませんでした。");
            }
        }, 300);
        return () => {
            window.clearTimeout(timer);
            controller.abort();
        };
    }, [query]);

    return (
        <AdminPage heading="代理予約登録">
            <p>{dateInputValue(lessonSlot.date)} {lessonTime(lessonSlot.lesson_template)}</p>
            <form className={styles.form} onSubmit={(event) => {
                event.preventDefault();
                form.post(`${admin}/lesson-slots/${lessonSlot.id}/reservations`);
            }}>
                <div ref={searchArea}>
                    <label htmlFor="name">お名前（必須）</label>
                    <input id="name" required autoComplete="off" placeholder="名前・フリガナ・電話番号で検索可能"
                        value={form.data.name} onFocus={() => setShowResults(true)} onChange={(event) => {
                            setShowResults(true);
                            form.setData({ ...form.data, client_id: "", name: event.target.value });
                        }} />
                    {showResults && query && results.length > 0 && <ul>
                        {results.map((client) => <li key={client.id}>
                            <ActionButton variant="text" onClick={() => {
                                form.setData({ ...form.data, client_id: client.id, name: client.name, phone: client.phone ?? "", relationship_note: "" });
                            }}>{client.name}{client.phone && `（${client.phone}）`}{client.relationship_note && ` – ${client.relationship_note}`}</ActionButton>
                        </li>)}
                    </ul>}
                    {form.data.client_id && <div className={styles.actions}>
                        <p role="status">既存クライアントを選択中</p>
                        <ActionButton variant="text" onClick={() => {
                            form.setData({ ...form.data, client_id: "", name: "", phone: "" });
                        }}>選択を解除</ActionButton>
                    </div>}
                    <FieldError message={form.errors.name ?? form.errors.client_id ?? searchError} />
                </div>
                <div>
                    <label htmlFor="phone">電話番号（任意）</label>
                    <input id="phone" type="tel" value={form.data.phone} readOnly={Boolean(form.data.client_id)}
                        onChange={(event) => form.setData("phone", event.target.value)} />
                    <FieldError message={form.errors.phone} />
                </div>
                <div>
                    <label htmlFor="relationship_note">関係性メモ</label>
                    <input id="relationship_note" value={form.data.relationship_note}
                        onChange={(event) => form.setData("relationship_note", event.target.value)} placeholder="紹介元・家族情報" />
                    <FieldError message={form.errors.relationship_note} />
                </div>
                <div>
                    <label htmlFor="note">備考（任意）</label>
                    <textarea id="note" value={form.data.note} onChange={(event) => form.setData("note", event.target.value)} />
                    <FieldError message={form.errors.note} />
                </div>
                <div className={styles.actions}>
                    <ActionButton type="submit" disabled={form.processing}>登録する</ActionButton>
                    <LinkButton href={`${admin}/calendar`} variant="outline">キャンセル</LinkButton>
                </div>
            </form>
        </AdminPage>
    );
}
