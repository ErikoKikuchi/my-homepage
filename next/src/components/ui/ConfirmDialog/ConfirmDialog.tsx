"use client";

import { useId } from "react";
import styles from "@/components/ui/ConfirmDialog/ConfirmDialog.module.css";
import ActionButton from "../ActionButton/ActionButton";

type ConfirmDialogProps = {
  dialogRef: React.RefObject<HTMLDialogElement | null>;
  title: string;
  children: React.ReactNode;
  confirmLabel: string;
  onConfirm: () => void;
  onClose: () => void;
  isSubmitting: boolean;
  submitError?: string | null;
};

export default function ConfirmDialog({
  dialogRef,
  title,
  children,
  confirmLabel,
  onConfirm,
  onClose,
  isSubmitting,
  submitError,
}: ConfirmDialogProps) {
  const titleId = useId();

  return (
    <dialog
      ref={dialogRef}
      aria-labelledby={titleId}
      onCancel={(e) => {
        if (isSubmitting) e.preventDefault();
      }}
      className={styles.confirmDialog}
    >
      <h2 id={titleId}>{title}</h2>

      {children}

      {submitError && <p role="alert">{submitError}</p>}

      <div className={styles.buttonGroup}>
        <ActionButton variant="text" onClick={onClose} disabled={isSubmitting}>
          戻る
        </ActionButton>

        <ActionButton onClick={onConfirm} disabled={isSubmitting}>
          {confirmLabel}
        </ActionButton>
      </div>
    </dialog>
  );
}
