"use client";

import { useId, useState, type SubmitEvent } from "react";
import { useRouter } from "next/navigation";
import { reservationNotices, Agreements, NoticeId } from "./ReservationNotices";
import { NoticeCheckList } from "./NoticeCheckList";
import ParticipantsField from "./ParticipantsField";
import PhoneInput from "@/components/pilates/form/PhoneInput";
import styles from "./ReservationForm.module.css";
import { createReservation } from "@/lib/api/auth/authPilates";
import { AuthApiError } from "@/lib/api/auth/authApi";

export type ReservationDetail = {
  date: string;
  time: string;
  dateFormatted: string;
  timeFormatted: string;
  venueNote: string;
  venueFixed: boolean;
  name: string;
};

type ReservationFormProps = {
  initialPhone: string;
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
  initialPhone,
  detail,
}: ReservationFormProps) {
  const router = useRouter();
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
  const [isConfirmOpen, setIsConfirmOpen] = useState(false);
  const [isSubmitting, setIsSubmitting] = useState(false);
  const [submitError, setSubmitError] = useState<string | null>(null);

  const allAgreed = reservationNotices.every((n) => values.agreements[n.id]);

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
    setIsConfirmOpen(true);
  };

  const handleSubmit = async () => {
    setIsSubmitting(true);
    setSubmitError(null);

    const extraCount = Number(values.participants) - 1;
    const participantNames = Array.from(
      { length: extraCount },
      (_, i) => values.participantNames[i] ?? "",
    );

    try {
      await createReservation({
        date: detail.date,
        time: detail.time,
        phone: values.phone,
        participants: Number(values.participants),
        participantNames,
        note: values.note,
      });
      router.push("/pilates/mypage"); // 完了画面のパスに合わせる
    } catch (error) {
      if (error instanceof AuthApiError && "errors" in error.body) {
        const e = error.body.errors;
        const mapped: FormErrors = {
          phone: e.phone?.[0],
          participants: e.participants?.[0],
          note: e.note?.[0],
        };
        if (Object.values(mapped).some(Boolean)) {
          setErrors(mapped);
          setIsConfirmOpen(false);
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
    } finally {
      setIsSubmitting(false);
    }
  };

  return (
    <form onSubmit={handleConfirm} noValidate>
      <section>
        <h2>1、開催場所のご案内</h2>
        <p>{detail.venueNote}</p>
      </section>

      <PhoneInput
        label="当日の連絡先(電話番号・任意)"
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

      <div>
        <label htmlFor={noteId}>備考(任意)</label>
        <p id={noteHintId}>
          場所のご希望など、事前にお伝えしたいことがあればご記入ください。
        </p>
        <textarea
          id={noteId}
          value={values.note}
          onChange={(e) => setField("note", e.target.value)}
          aria-invalid={errors.note ? true : undefined}
          aria-describedby={
            errors.note ? `${noteHintId} ${noteErrorId}` : noteHintId
          }
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

      <button type="submit">予約申請 確認画面へ</button>

      {isConfirmOpen && (
        <div role="dialog" aria-modal="true" aria-labelledby="confirmTitle">
          <h2 id="confirmTitle">ご予約内容の確認</h2>
          {/* TODO: 表示直前に /api/user を再問い合わせして名前を表示 */}
          <p>
            {detail.dateFormatted} {detail.timeFormatted}
          </p>
          {/* TODO: 参加人数・参加者名・連絡先・備考の表示 */}
          {submitError && <p role="alert">{submitError}</p>}
          <button
            type="button"
            onClick={() => setIsConfirmOpen(false)}
            disabled={isSubmitting}
          >
            戻る
          </button>
          <button type="button" onClick={handleSubmit} disabled={isSubmitting}>
            予約を申請する
          </button>
        </div>
      )}
    </form>
  );
}
