"use client";

import { useState, type SubmitEvent } from "react";
import styles from "./ReservationForm.module.css";
import { reservationNotices, Agreements, NoticeId } from "./reservationNotices";
import { NoticeCheckList } from "./NoticeCheckList";
import ParticipantsSelect from "./ParticipantsSelect";
import PhoneInput from "@/components/pilates/form/PhoneInput";
import { useRouter } from "next/navigation";
import { createReservation } from "@/lib/api/auth/authPilates";
import LinkButton from "@/components/ui/LinkButton/LinkButton";

type ReservationFormProps = {
  date: string;
  time: string;
  initialPhone: string;
};
type ReservationFormValues = {
  phone: string;
  participants: string;
  participantsName: string;
  note: string;
  agreements: Agreements;
};
type FormErrors = Partial<Record<keyof ReservationFormValues, string>>;

const venueNote from 

export default function ReservationForm({
  date,
  time,
  initialPhone,
}: ReservationFormProps) {
  const router = useRouter();

  const [values, setValues] = useState<ReservationFormValues>({
    phone: initialPhone,
    participants: "1",
    participantsName: "",
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

  const handleAgreementChange = (id: NoticeId, checked: boolean) =>{
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
    try {
      await createReservation({
        date,
        time,
        phone: values.phone,
        participants: Number(values.participants),
        participantsName: values.participantsName,
        note: values.note,
      });
      router.push("/pilates/mypage"); // 完了画面のパスに合わせる
    } catch (e) {
      // ログインフォームの AuthApiError と同じ流れで処理する
      setSubmitError("予約の申請に失敗しました。時間をおいて再度お試しください。");
    } finally {
      setIsSubmitting(false);
    }
  };

  return (
    <form onSubmit={handleConfirm} noValidate>
      <section>
        <h2>1、開催場所のご案内</h2>
        <p>{venueNote}</p>
      </section>
  
      <PhoneInput
        label="当日の連絡先(電話番号・任意)"
        hint="LINEで連絡が取れる場合は、空欄でも構いません。"
        value={values.phone}
        onChange={(v) => setField("phone", v)}
        error={errors.phone}
      />
  
      <ParticipantsSelect
        value={values.participants}
        onChange={(v) => setField("participants", v)}
        error={errors.participants}
      />
  
      {/* 参加者名(任意)・参加者連絡先(任意)・備考(任意) */}
  
      <NoticeCheckList
        notices={reservationNotices}
        agreements={values.agreements}
        onChange={handleAgreementChange}
        error={errors.agreements}
      />
  
      <button type="submit">予約申請 確認画面へ</button>
  
      {isConfirmOpen && (
        /* 確認モーダル: values を表示し、キャンセルと「予約を申請する」を置く */
      )}
    </form>
  );
}
