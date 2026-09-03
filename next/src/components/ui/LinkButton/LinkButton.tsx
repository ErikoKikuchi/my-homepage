// next/src/components/ui/LinkButton/LinkButton.tsx
import styles from "./LinkButton.module.css";

type LinkButtonVariant = "primary" | "outline" | "text";

interface LinkButtonProps {
  href: string;
  children: React.ReactNode;
  external?: boolean;
  variant?: LinkButtonVariant;
}

export default function LinkButton({
  href,
  children,
  external = false,
  variant = "primary",
}: LinkButtonProps) {
  return (
    <a
      href={href}
      className={`${styles.linkButton} ${styles[variant]}`}
      {...(external ? { target: "_blank", rel: "noopener noreferrer" } : {})}
    >
      {children}
    </a>
  );
}
