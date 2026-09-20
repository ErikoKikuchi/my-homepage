import styles from "./LinkButton.module.css";
import Link from "next/link";

type LinkButtonVariant = "primary" | "outline" | "text";

interface LinkButtonProps {
  href: string;
  children: React.ReactNode;
  external?: boolean;
  variant?: LinkButtonVariant;
  className?: string;
}

export default function LinkButton({
  href,
  children,
  external = false,
  variant = "primary",
  className,
}: LinkButtonProps) {
  const classNames = `${styles.linkButton} ${styles[variant]} ${className ?? ""}`;
  return external ? (
    <a
      href={href}
      className={classNames}
      target="_blank"
      rel="noopener noreferrer"
    >
      {children}
    </a>
  ) : (
    <Link href={href} className={classNames}>
      {children}
    </Link>
  );
}
