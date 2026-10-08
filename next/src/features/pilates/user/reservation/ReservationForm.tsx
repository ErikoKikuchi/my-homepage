"use client";

import { useId, useState, useRef, type SubmitEvent } from "react";
import { useRouter } from "next/navigation";
import { reservationNotices, Agreements, NoticeId } from "./ReservationNotices";
import { NoticeCheckList } from "./NoticeCheckList";
import ParticipantsField from "./ParticipantsField";
import PhoneInput from "@/components/pilates/form/PhoneInput";
import styles from "./ReservationForm.module.css";
import { createReservation } from "@/lib/api/pilates/reservationClient";
import { AuthApiError } from "@/lib/api/auth/authApi";
import { ReservationDetail } from "@/types/pilates/reservation";
import { getCurrentPilatesUser } from "@/lib/api/auth/authPilates";
import ActionButton from "@/components/ui/ActionButton/ActionButton";
import type { NoticeKey } from "@/features/pilates/user/mypage/notices";

type ReservationFormProps = {
  initialPhone?: string;
  detail: ReservationDetail;
};

type ReservationFormValues = {
  phone: string;
  participants: string;
  participantNames: string[];
  note: string;
  agreements: Agreements;
};
type FormErrors = Partial<Record<keyof ReservationFormValues, string>>;

export default function ReservationForm({
  initialPhone = "",
  detail,
}: ReservationFormProps) {
  const router = useRouter();
  const dialogRef = useRef<HTMLDialogElement>(null);
  const noteId = useId();
  const noteHintId = `${noteId}-hint`;
  const noteErrorId = `${noteId}-error`;

  const [values, setValues] = useState<ReservationFormValues>({
    phone: initialPhone,
    participants: "1",
    participantNames: [],
    note: "",
    agreements: {
      place: false,
      publicFacility: false,
      line: false,
      cancel: false,
    },
  });
  const [errors, setErrors] = useState<FormErrors>({});
  const [isSubmitting, setIsSubmitting] = useState(false);
  const [submitError, setSubmitError] = useState<string | null>(null);
  const [confirmName, setConfirmName] = useState("");
  const [openError, setOpenError] = useState<string | null>(null);

  const allAgreed = reservationNotices.every((n) => values.agreements[n.id]);

  async function handleOpenConfirm() {
    setOpenError(null);
    setSubmitError(null);
    try {
      const user = await getCurrentPilatesUser();
      if (!user) {
        router.push("/auth/pilates/login");
        return;
      }
      setConfirmName(user.name);
      dialogRef.current?.showModal();
    } catch {
      setOpenError(
        "確認画面を開けませんでした。通信状況を確認して、もう一度お試しください。",
      );
    }
  }

  const setField = <K extends keyof ReservationFormValues>(
    key: K,
    value: ReservationFormValues[K],
  ) => setValues((prev) => ({ ...prev, [key]: value }));

  const handleParticipantsChange = (value: string) =>
    setValues((prev) => ({
      ...prev,
      participants: value,
      participantNames: prev.participantNames.slice(0, Number(value) - 1),
    }));

  const handleNameChange = (index: number, value: string) =>
    setValues((prev) => {
      const next = [...prev.participantNames];
      next[index] = value;
      return { ...prev, participantNames: next };
    });

  const handleAgreementChange = (id: NoticeId, checked: boolean) => {
    setValues((prev) => ({
      ...prev,
      agreements: { ...prev.agreements, [id]: checked },
    }));
    setErrors((prev) => ({ ...prev, agreements: undefined }));
  };

  const handleConfirm = (e: SubmitEvent<HTMLFormElement>) => {
    e.preventDefault();
    if (!allAgreed) {
      setErrors({ agreements: "すべての確認事項にチェックしてください。" });
      return;
    }
    setErrors({});
    handleOpenConfirm();
  };

  const handleSubmit = async () => {
    if (isSubmitting) return;
    setIsSubmitting(true);
    setSubmitError(null);

    const extraCount = Number(values.participants) - 1;
    const participantNames = Array.from(
      { length: extraCount },
      (_, i) => values.participantNames[i] ?? "",
    );
    const key: NoticeKey = "reserved";

    try {
      await createReservation({
        date: detail.date,
        time: detail.time,
        phone: values.phone,
        participants: Number(values.participants),
        participantNames,
        note: values.note,
      });
      dialogRef.current?.close();
      router.push(`/pilates/mypage?notice=${key}`);
    } catch (error) {
      if (
        error instanceof AuthApiError &&
        "errors" in error.body &&
        error.status === 401
      ) {
        const e = error.body.errors;
        const mapped: FormErrors = {
          phone: e.phone?.[0],
          participants: e.participants?.[0],
          note: e.note?.[0],
        };
        if (Object.values(mapped).some(Boolean)) {
          setErrors(mapped);
          dialogRef.current?.close();
          router.push("/auth/pilates/login");
          return;
        } else {
          setSubmitError("入力内容をご確認ください。");
        }
      } else if (error instanceof AuthApiError) {
        setSubmitError(error.body.message);
      } else {
        setSubmitError(
          "予約の申請に失敗しました。時間をおいて再度お試しください。",
        );
      }
      setIsSubmitting(false);
    }
  };

  return (
    <form onSubmit={handleConfirm} noValidate className={styles.main}>
      <section>
        <h2 className={styles.title}>1、開催場所のご案内</h2>
        <p className={styles.venueNote}>{detail.venueNote}</p>
      </section>

      <PhoneInput
        label="2、当日の連絡先(電話番号・任意)"
        hint="LINEで連絡が取れる場合は、空欄でも構いません。"
        value={values.phone}
        onChange={(v) => setField("phone", v)}
        error={errors.phone}
      />

      <ParticipantsField
        value={values.participants}
        participantNames={values.participantNames}
        onChange={handleParticipantsChange}
        onNameChange={handleNameChange}
        error={errors.participants}
      />

      <div className={styles.row}>
        <label htmlFor={noteId} className={styles.label}>
          4、備考(任意)
        </label>
        <p id={noteHintId} className={styles.hintText}>
          場所のご希望やお身体の状態など、事前にお伝えしたいことがあればご記入ください。
        </p>
        <textarea
          id={noteId}
          value={values.note}
          onChange={(e) => setField("note", e.target.value)}
          aria-invalid={errors.note ? true : undefined}
          aria-describedby={
            errors.note ? `${noteHintId} ${noteErrorId}` : noteHintId
          }
          className={styles.textarea}
        />
        {errors.note && (
          <p id={noteErrorId} role="alert">
            {errors.note}
          </p>
        )}
      </div>

      <NoticeCheckList
        notices={reservationNotices}
        agreements={values.agreements}
        onChange={handleAgreementChange}
        error={errors.agreements}
      />

      <button type="submit" id="openModal" className={styles.submitButton}>
        予約申請 確認画面へ
      </button>

      <dialog
        ref={dialogRef}
        className={styles.confirmDialog}
        aria-labelledby="confirmTitle"
        onCancel={(e) => {
          if (isSubmitting) e.preventDefault(); // 送信中はEscで閉じない
        }}
        id="reservationModal"
      >
        <h2 id="confirmTitle" className={styles.modalTitle}>
          ご予約内容の確認
        </h2>
        <p>{confirmName}さんのご予約</p>
        <p>
          日程：{detail.dateFormatted} {detail.timeFormatted}
        </p>
        <p>連絡先：{values.phone || "LINE"}</p>
        <p>
          参加者人数・参加者名：{values.participants}名（
          {[confirmName, ...values.participantNames].join("、")}）
        </p>
        <p>備考：{values.note}</p>
        {submitError && <p role="alert">{submitError}</p>}
        <div className={styles.buttonGroup}>
          <ActionButton
            variant="outline"
            onClick={() => dialogRef.current?.close()}
            disabled={isSubmitting}
          >
            戻る
          </ActionButton>
          <ActionButton onClick={handleSubmit} disabled={isSubmitting}>
            予約を申請する
          </ActionButton>
          {openError && <p role="alert">{openError}</p>}
        </div>
      </dialog>
    </form>
  );
}
