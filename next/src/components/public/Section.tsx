// Section.tsx
import styles from "./Section.module.css";

interface SectionProps {
  label: string;
  children: React.ReactNode;
  className?: string;
  labelClassName?: string;
  animationDelay?: string;
}

export default function Section({
  label,
  children,
  className,
  labelClassName,
  animationDelay,
}: SectionProps) {
  return (
    <section
      className={`${styles.section} ${className ?? ""}`}
      style={animationDelay ? { animationDelay } : undefined}
    >
      <p className={`${styles.sectionTitle} ${labelClassName ?? ""}`}>
        {label}
      </p>
      {children}
    </section>
  );
}
