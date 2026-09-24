import styles from "./ActionButton.module.css";

type ActionButtonProps = {
  children: React.ReactNode;
  onClick: () => void;
  variant?: "primary" | "outline" | "text";
  className?: string;
  disabled?: boolean;
};

export default function ActionButton({
  children,
  onClick,
  variant = "primary",
  className,
  disabled = false,
}: ActionButtonProps) {
  const classNames = `${styles.actionButton} ${styles[variant]} ${className ?? ""}`;

  return (
    <button
      type="button"
      className={classNames}
      onClick={onClick}
      disabled={disabled}
    >
      {children}
    </button>
  );
}
