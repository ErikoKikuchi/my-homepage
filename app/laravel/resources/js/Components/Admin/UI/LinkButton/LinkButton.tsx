import styles from "./LinkButton.module.css";
import { Link } from "@inertiajs/react";

type LinkButtonVariant = "primary" | "outline" | "text";

interface LinkButtonProps {
    href: string;
    children: React.ReactNode;
    external?: boolean;
    fullReload?: boolean;
    variant?: LinkButtonVariant;
    className?: string;
    "aria-current"?: "page";
}

export default function LinkButton({
    href,
    children,
    external = false,
    fullReload = false,
    variant = "primary",
    className,
    "aria-current": ariaCurrent,
}: LinkButtonProps) {
    const classNames = `${styles.linkButton} ${styles[variant]} ${className ?? ""}`;
    return external || fullReload ? (
        <a
            aria-current={ariaCurrent}
            href={href}
            className={classNames}
            target={external ? "_blank" : undefined}
            rel={external ? "noopener noreferrer" : undefined}
        >
            {children}
        </a>
    ) : (
        <Link href={href} className={classNames} aria-current={ariaCurrent}>
            {children}
        </Link>
    );
}
