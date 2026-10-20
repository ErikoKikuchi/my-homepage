"use client";

import { useState, useRef } from "react";
import { useRouter } from "next/navigation";
import { AuthApiError } from "@/lib/api/auth/authApi";
import { getCurrentPilatesUser } from "@/lib/api/auth/authPilates";
import ConfirmDialog from "@/components/ui/ConfirmDialog/ConfirmDialog";
import styles from "@/features/pilates/user/reservation/BookingDetailClient.module.css";
import { cancelReservation } from "@/lib/api/pilates/reservationClient";

type BookingDetailProps = {
  uuid: string;
};

export default function BookingDetailClient({ uuid }: BookingDetailProps) {
  const dialogRef = useRef<HTMLDialogElement>(null);
  const router = useRouter();
  const [isSubmitting, setIsSubmitting] = useState(false);
  const [submitError, setSubmitError] = useState<string | null>(null);
  const [openError, setOpenError] = useState<string | null>(null);

  async function handleOpenConfirm() {
    setOpenError(null);
    setSubmitError(null);
    try {
      const user = await getCurrentPilatesUser();
      if (!user) {
        router.push("/auth/pilates/login");
        return;
      }
      dialogRef.current?.showModal();
    } catch {
      setOpenError(
        "確認画面を開けませんでした。通信状況を確認して、もう一度お試しください。",
      );
    }
  }

  const handleSubmit = async () => {
    if (isSubmitting) return;
    setIsSubmitting(true);
    setSubmitError(null);

    try {
      await cancelReservation(uuid);
      dialogRef.current?.close();
      router.push("/pilates/mypage");
      router.refresh();
    } catch (error) {
      if (error instanceof AuthApiError && error.status === 401) {
        router.push("/auth/pilates/login");
        return;
      }
      if (error instanceof AuthApiError) {
        setSubmitError(error.body.message);
      } else {
        setSubmitError(
          "キャンセルの申請に失敗しました。時間をおいて再度お試しください。",
        );
      }
    } finally {
      setIsSubmitting(false);
    }
  };
  return (
    <>
      <button
        type="button"
        id="openModal"
        onClick={handleOpenConfirm}
        disabled={isSubmitting}
        className={styles.submitButton}
      >
        キャンセル画面へ
      </button>
      <ConfirmDialog
        dialogRef={dialogRef}
        title="予約キャンセルの確認"
        confirmLabel="キャンセルの確定"
        onConfirm={handleSubmit}
        onClose={() => dialogRef.current?.close()}
        isSubmitting={isSubmitting}
        submitError={submitError}
      >
        <p>この予約をキャンセルしますか？</p>
        <p>キャンセルを確定すると、この予約は取り消されます。</p>
      </ConfirmDialog>
      {openError && <p role="alert">{openError}</p>}
    </>
  );
}
