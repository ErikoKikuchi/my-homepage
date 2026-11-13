import styles from "./ActionButton.module.css";
import type { AriaAttributes } from "react";

type ActionButtonProps = Pick<AriaAttributes, "aria-expanded" | "aria-controls"> & {
  children: React.ReactNode;
  onClick?: () => void;
  type?: "button" | "submit";
  variant?: "primary" | "outline" | "text";
  className?: string;
  disabled?: boolean;
  id?: string;
};

export default function ActionButton({
  children,
  onClick,
  type = "button",
  variant = "primary",
  className,
  disabled = false,
  id,
  "aria-expanded": expanded,
  "aria-controls": controls,
}: ActionButtonProps) {
  const classNames = `${styles.actionButton} ${styles[variant]} ${className ?? ""}`;

  return (
    <button
      type={type}
      id={id}
      className={classNames}
      onClick={onClick}
      disabled={disabled}
      aria-expanded={expanded}
      aria-controls={controls}
    >
      {children}
    </button>
  );
}
