import styles from "./ActionButton.module.css";

type ActionButtonProps = {
  children: React.ReactNode;
  onClick?: () => void;
  type?: "button" | "submit";
  variant?: "primary" | "outline" | "text";
  className?: string;
  disabled?: boolean;
};

export default function ActionButton({
  children,
  onClick,
  type = "button",
  variant = "primary",
  className,
  disabled = false,
}: ActionButtonProps) {
  const classNames = `${styles.actionButton} ${styles[variant]} ${className ?? ""}`;

  return (
    <button
      type={type}
      className={classNames}
      onClick={onClick}
      disabled={disabled}
    >
      {children}
    </button>
  );
}
